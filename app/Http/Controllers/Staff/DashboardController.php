<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Idea;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $myIdeas = Idea::where('user_id', $user->id)->latest()->take(5)->get();
        $myComments = Comment::where('user_id', $user->id)->latest()->take(5)->get();
        
        $stats = [
            'my_ideas' => Idea::where('user_id', $user->id)->count(),
            'my_comments' => Comment::where('user_id', $user->id)->count(),
            'my_votes' => $user->votes()->count(),
            'total_views' => Idea::where('user_id', $user->id)->sum('views_count'),
        ];
        
        $ideaClosureDate = Setting::getIdeaClosureDate();
        $finalClosureDate = Setting::getFinalClosureDate();
        $canSubmitIdea = $user->canSubmitIdea();
        $canComment = $user->canComment();
        
        return view('staff.dashboard', compact('myIdeas', 'myComments', 'stats', 'ideaClosureDate', 'finalClosureDate', 'canSubmitIdea', 'canComment'));
    }
}
