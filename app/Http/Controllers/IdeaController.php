<?php

namespace App\Http\Controllers;

use App\Models\Idea;
use App\Models\Category;
use App\Models\Document;
use App\Models\Setting;
use App\Models\User;
use App\Notifications\IdeaSubmittedNotification;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IdeaController extends Controller
{
    public function index(Request $request)
    {
        $showMyIdeas = $request->has('my_ideas') && Auth::check();
        $query = Idea::query()->with(['user', 'department', 'categories']);

        if (!$showMyIdeas) {
            $query->published();
        }

        if (!Auth::check() || !Auth::user()->isQaManager()) {
            $query->visible();
        }
        
        if ($request->has('category')) {
            $query->byCategory($request->category);
        }
        
        if ($request->has('department')) {
            $query->byDepartment($request->department);
        }

        if ($showMyIdeas) {
            $query->where('user_id', Auth::id());
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
        $idea->department_id = Auth::user()->isQaManager() ? null : Auth::user()->department_id;
        $idea->is_anonymous = $request->boolean('is_anonymous');
        $idea->status = Auth::user()->isStaff() ? 'pending' : 'approved';
        $idea->save();

        AuditLogger::log(
            'SUBMIT_IDEA',
            "Submitted idea #{$idea->id}: {$idea->title}.",
            $idea
        );
        
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
        
        if (Auth::user()->isStaff()) {
            return redirect()->route('staff.ideas.show', $idea)->with('success', 'Idea submitted successfully!');
        }

        return redirect()->route('ideas.show', $idea)->with('success', 'Idea submitted successfully!');
    }
/**
 * Show the form for editing the specified idea (for staff)
 */
public function edit(Idea $idea)
{
    $user = Auth::user();

    // Only staff can edit their own pending ideas
    if (!$user || !$user->isStaff()) {
        abort(403, 'Unauthorized access.');
    }

    // Check if user owns this idea
    if ($idea->user_id !== $user->id) {
        abort(403, 'You do not have permission to edit this idea.');
    }

    // Check if idea can be edited (only pending or under_review)
    if (!in_array($idea->status, ['pending', 'under_review'])) {
        return redirect()->route('staff.ideas.show', $idea)
            ->with('error', 'This idea cannot be edited because it has already been ' . $idea->status . '.');
    }

    $categories = Category::active()->ordered()->get();
    
    return view('staff.ideas.edit', compact('idea', 'categories'));
}

/**
 * Update the specified idea in storage (for staff)
 */
public function update(Request $request, Idea $idea)
{
    $user = Auth::user();

    // Only staff can edit their own pending ideas
    if (!$user || !$user->isStaff()) {
        abort(403, 'Unauthorized access.');
    }

    // Check if user owns this idea
    if ($idea->user_id !== $user->id) {
        abort(403, 'You do not have permission to edit this idea.');
    }

    // Check if idea can be edited
    if (!in_array($idea->status, ['pending', 'under_review'])) {
        return redirect()->route('staff.ideas.show', $idea)
            ->with('error', 'This idea cannot be edited because it has already been ' . $idea->status . '.');
    }

    $validated = $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'categories' => 'required|array|min:1',
        'categories.*' => 'exists:categories,id',
        'is_anonymous' => 'boolean',
    ]);

    // Update idea
    $idea->update([
        'title' => $validated['title'],
        'description' => $validated['description'],
        'is_anonymous' => $request->boolean('is_anonymous'),
    ]);

    // Sync categories (remove old and add new)
    $idea->categories()->sync($validated['categories']);

    AuditLogger::log(
        'UPDATE_IDEA',
        "Updated idea #{$idea->id}: {$idea->title}.",
        $idea
    );

    return redirect()->route('staff.ideas.show', $idea)
        ->with('success', 'Idea updated successfully!');
}
    public function show(Idea $idea)
    {
        if ($idea->hidden && (!Auth::check() || !Auth::user()->isQaManager())) {
            abort(404);
        }

        $idea->incrementViews();
        
        $userVote = null;
        if (Auth::check()) {
            $userVote = $idea->getUserVote(Auth::id());
        }
        
        $commentsQuery = $idea->comments()->with('user');
        if (!Auth::check() || !Auth::user()->isQaManager()) {
            $commentsQuery->where('hidden', false);
        }

        $comments = $commentsQuery->paginate(10);
        
        return view('ideas.show', compact('idea', 'userVote', 'comments'));
    }

    public function vote(Request $request, Idea $idea)
    {
        if ($idea->hidden && (!Auth::check() || !Auth::user()->isQaManager())) {
            abort(404);
        }

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

   public function destroy(Request $request, Idea $idea)
{
    $user = Auth::user();
    if (!$user) {
        abort(403);
    }

    // Allow admin to delete any idea, or staff to delete their own ideas
    if (!$user->isAdmin() && !($user->isStaff() && $idea->user_id === $user->id)) {
        abort(403);
    }

    // For staff: check if idea can be deleted (only pending or under_review)
    if ($user->isStaff() && !in_array($idea->status, ['pending', 'under_review'])) {
        return redirect()->back()->with('error', 'This idea cannot be deleted because it has already been ' . $idea->status . '.');
    }

    $validated = $request->validate([
        'reason' => 'nullable|string|max:255',
    ]);

    $ideaId = $idea->id;
    $title = $idea->title;
    $reason = $validated['reason'] ?? 'No reason provided.';

    // Delete associated documents from storage
    foreach ($idea->documents as $document) {
        if ($document->file_path && Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }
        $document->delete();
    }

    $idea->delete();

    AuditLogger::log(
        'DELETE_IDEA',
        "Removed Idea #{$ideaId} ({$title}). Reason: {$reason}",
        $idea
    );

    // Check if the request came from "My Ideas" page
    $fromMyIdeas = $request->has('from_my_ideas') || str_contains(url()->previous(), 'my_ideas=1');
    
    // If coming from My Ideas or staff is deleting their own idea, stay on My Ideas page
    if ($fromMyIdeas || ($user->isStaff() && !$user->isAdmin())) {
        return redirect()->route('ideas.index', ['my_ideas' => 1])
            ->with('success', 'Idea deleted successfully!');
    }

    return redirect()->route('ideas.index')->with('success', 'Idea deleted successfully!');
}

    public function toggleHidden(Idea $idea)
    {
        if (!Auth::check() || !Auth::user()->isQaManager()) {
            abort(403, 'Unauthorized access.');
        }

        $idea->update([
            'hidden' => !$idea->hidden,
        ]);

        AuditLogger::log(
            $idea->hidden ? 'HIDE_IDEA' : 'UNHIDE_IDEA',
            ($idea->hidden ? 'Hidden' : 'Unhidden') . " idea #{$idea->id}: {$idea->title}.",
            $idea
        );

        return redirect()->back()->with(
            'success',
            $idea->hidden ? 'Idea has been hidden successfully.' : 'Idea has been unhidden successfully.'
        );
    }
}
