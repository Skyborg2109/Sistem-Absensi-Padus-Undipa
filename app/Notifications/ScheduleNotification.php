<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ScheduleNotification extends Notification
{
    use Queueable;

    protected $schedule;

    /**
     * Create a new notification instance.
     */
    public function __construct($schedule)
    {
        $this->schedule = $schedule;
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
            'id' => $this->schedule->id,
            'title' => $this->schedule->title,
            'type' => 'jadwal',
            'message' => 'Jadwal latihan baru telah ditambahkan: ' . $this->schedule->title,
            'url' => route('member.dashboard'),
            'icon' => 'calendar_month'
        ];
    }
}
