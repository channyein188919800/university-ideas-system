<?php

namespace App\Notifications;

use App\Models\Comment;
use App\Models\Idea;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CommentAddedNotification extends Notification
{
    use Queueable;

    protected $idea;
    protected $comment;

    public function __construct(Idea $idea, Comment $comment)
    {
        $this->idea = $idea;
        $this->comment = $comment;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $commenterName = $this->comment->is_anonymous ? 'Anonymous' : $this->comment->user->name;
        
        return (new MailMessage)
            ->subject('New Comment on Your Idea')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new comment has been added to your idea.')
            ->line('Idea: ' . $this->idea->title)
            ->line('Comment by: ' . $commenterName)
            ->line('Comment: ' . Str::limit($this->comment->content, 100))
            ->action('View Comment', route('ideas.show', $this->idea))
            ->line('Thank you for contributing to the improvement of our university!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'idea_id' => $this->idea->id,
            'comment_id' => $this->comment->id,
            'title' => $this->idea->title,
            'message' => 'New comment on your idea',
            'url' => route('ideas.show', $this->idea),
        ];
    }
}
