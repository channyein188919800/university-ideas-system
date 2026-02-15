<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Idea;
use App\Models\Setting;
use App\Notifications\CommentAddedNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    public function store(Request $request, Idea $idea)
    {
        if (!Auth::user()->canComment()) {
            return redirect()->back()->with('error', 'Commenting is now closed.');
        }
        
        $validated = $request->validate([
            'content' => 'required|string|max:5000',
            'is_anonymous' => 'boolean',
        ]);
        
        $comment = new Comment();
        $comment->idea_id = $idea->id;
        $comment->user_id = Auth::id();
        $comment->content = $validated['content'];
        $comment->is_anonymous = $request->boolean('is_anonymous');
        $comment->save();
        
        $idea->updateCommentsCount();
        
        $this->notifyIdeaAuthor($idea, $comment);
        
        return redirect()->back()->with('success', 'Comment added successfully!');
    }

    protected function notifyIdeaAuthor(Idea $idea, Comment $comment)
    {
        if ($idea->user_id !== Auth::id()) {
            $author = $idea->user;
            if ($author) {
                Notification::send($author, new CommentAddedNotification($idea, $comment));
            }
        }
    }
}
