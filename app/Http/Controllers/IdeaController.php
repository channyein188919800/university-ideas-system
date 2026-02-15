<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Category;
use App\Models\Document;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\IdeaSubmittedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IdeaController extends Controller
{
    public function index(Request $request)
    {
        $query = Idea::published()->with(['user', 'department', 'categories']);
        
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }
        
        if ($request->has('department')) {
            $query->byDepartment($request->department);
        }
        
        if ($request->has('sort')) {
            switch ($request->sort) {
                case 'popular':
                    $query->popular();
                    break;
                case 'views':
                    $query->mostViewed();
                    break;
                default:
                    $query->latest();
            }
        } else {
            $query->latest();
        }
        
        $ideas = $query->paginate(5);
        $categories = Category::active()->ordered()->get();
        
        return view('ideas.index', compact('ideas', 'categories'));
    }

    public function create()
    {
        if (!Auth::user()->canSubmitIdea()) {
            return redirect()->back()->with('error', 'Idea submission is now closed.');
        }
        
        $categories = Category::active()->ordered()->get();
        return view('ideas.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!Auth::user()->canSubmitIdea()) {
            return redirect()->back()->with('error', 'Idea submission is now closed.');
        }
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'categories' => 'required|array|min:1',
            'categories.*' => 'exists:categories,id',
            'is_anonymous' => 'boolean',
            'documents' => 'nullable|array',
            'documents.*' => 'file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif',
        ]);
        
        $idea = new Idea();
        $idea->title = $validated['title'];
        $idea->description = $validated['description'];
        $idea->user_id = Auth::id();
        $idea->department_id = Auth::user()->department_id;
        $idea->is_anonymous = $request->boolean('is_anonymous');
        $idea->status = 'approved';
        $idea->save();
        
        $idea->categories()->attach($validated['categories']);
        
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $file) {
                $originalName = $file->getClientOriginalName();
                $fileName = Str::random(40) . '.' . $file->getClientOriginalExtension();
                $path = $file->storeAs('documents/' . $idea->id, $fileName, 'public');
                
                Document::create([
                    'idea_id' => $idea->id,
                    'original_name' => $originalName,
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }
        
        $this->notifyQaCoordinator($idea);
        
        return redirect()->route('ideas.show', $idea)->with('success', 'Idea submitted successfully!');
    }

    public function show(Idea $idea)
    {
        $idea->incrementViews();
        
        $userVote = null;
        if (Auth::check()) {
            $userVote = $idea->getUserVote(Auth::id());
        }
        
        $comments = $idea->comments()->with('user')->paginate(10);
        
        return view('ideas.show', compact('idea', 'userVote', 'comments'));
    }

    public function vote(Request $request, Idea $idea)
    {
        $validated = $request->validate([
            'vote_type' => 'required|in:up,down',
        ]);
        
        $existingVote = $idea->getUserVote(Auth::id());
        
        if ($existingVote) {
            if ($existingVote->vote_type === $validated['vote_type']) {
                $existingVote->delete();
            } else {
                $existingVote->update(['vote_type' => $validated['vote_type']]);
            }
        } else {
            $idea->votes()->create([
                'user_id' => Auth::id(),
                'vote_type' => $validated['vote_type'],
            ]);
        }
        
        $idea->updateVoteCounts();
        
        return redirect()->back()->with('success', 'Vote recorded successfully!');
    }

    protected function notifyQaCoordinator(Idea $idea)
    {
        $department = $idea->department;
        
        if ($department && $department->qa_coordinator_id) {
            $qaCoordinator = User::find($department->qa_coordinator_id);
            if ($qaCoordinator) {
                Notification::send($qaCoordinator, new IdeaSubmittedNotification($idea));
            }
        }
    }
}
