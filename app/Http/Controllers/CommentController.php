<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Idea;
use App\Notifications\CommentAddedNotification;
use App\Support\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    public function store(Request $request, Idea $idea)
    {
        if ($idea->hidden && (!Auth::check() || !Auth::user()->isQaManager())) {
            abort(404);
        }

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

    public function toggleHidden(Comment $comment)
    {
        if (!Auth::check() || !Auth::user()->isQaManager()) {
            abort(403, 'Unauthorized access.');
        }

        $comment->update([
            'hidden' => !$comment->hidden,
        ]);

        $comment->idea?->updateCommentsCount();

        AuditLogger::log(
            $comment->hidden ? 'HIDE_COMMENT' : 'UNHIDE_COMMENT',
            ($comment->hidden ? 'Hidden' : 'Unhidden') . " comment #{$comment->id}.",
            $comment
        );

        return redirect()->back()->with(
            'success',
            $comment->hidden ? 'Comment has been hidden successfully.' : 'Comment has been restored successfully.'
        );
    }
}
