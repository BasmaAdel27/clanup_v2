<?php

namespace App\Notifications\Event;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DateTimeChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->event = $event;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $user = User::find($notifiable->id) ?? null;
        return (new MailMessage)
                    ->subject(__(':event_title time have been changed', ['event_title' => $this->event->title]))
                    ->greeting(__(':event_title time have been changed', ['event_title' => $this->event->title]))
                    ->line(
                        __('<strong>:event_title</strong> will starts at <strong>:starts_date</strong> and ends at <strong>:ends_date</strong>', [
                            'event_title' => $this->event->title, 
                            'starts_date' => convertToLocal($this->event->starts_at, 'M d, Y H:i', false, $user),
                            'ends_date' => convertToLocal($this->event->ends_at, 'M d, Y H:i', false, $user),
                        ])
                    );
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $user = User::find($notifiable->id) ?? null;
        return [
            'event_id' => $this->event->id,
        ];
    }
}
