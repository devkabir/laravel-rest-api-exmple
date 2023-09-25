<?php

namespace App\Notifications;

use App\Models\Comment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewCommentAddedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Comment $comment;

    public function __construct(Comment $comment)
    {
        $this->comment = $comment;

    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->line('A new comment was added to task: '.$this->comment->task->title)
            ->action('View Task', config('app.frontend_url') . '/tasks/details/'. $this->comment->task->id)
            ->line('Thank you for using our application!');

    }


}
