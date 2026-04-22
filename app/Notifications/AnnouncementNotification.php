<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AnnouncementNotification extends Notification
{
    use Queueable;

    protected $announcement;

    /**
     * Create a new notification instance.
     */
    public function __construct($announcement)
    {
        $this->announcement = $announcement;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'id' => $this->announcement->id,
            'title' => $this->announcement->title,
            'type' => $this->announcement->type,
            'message' => 'Ada ' . $this->announcement->type . ' baru: ' . $this->announcement->title,
            'url' => route('member.announcements.index'),
            'icon' => $this->announcement->type == 'peraturan' ? 'gavel' : 'campaign'
        ];
    }
}
