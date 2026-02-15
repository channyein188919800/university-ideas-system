<?php

namespace App\Notifications;

use App\Models\Idea;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class IdeaSubmittedNotification extends Notification
{
    use Queueable;

    protected $idea;

    public function __construct(Idea $idea)
    {
        $this->idea = $idea;
    }

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $authorName = $this->idea->is_anonymous ? 'Anonymous' : $this->idea->user->name;
        
        return (new MailMessage)
            ->subject('New Idea Submitted in Your Department')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new idea has been submitted in your department.')
            ->line('Title: ' . $this->idea->title)
            ->line('Author: ' . $authorName)
            ->line('Submitted: ' . $this->idea->created_at->format('M d, Y H:i'))
            ->action('View Idea', route('ideas.show', $this->idea))
            ->line('Please review and encourage staff to contribute to the discussion.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'idea_id' => $this->idea->id,
            'title' => $this->idea->title,
            'message' => 'New idea submitted in your department',
            'url' => route('ideas.show', $this->idea),
        ];
    }
}
