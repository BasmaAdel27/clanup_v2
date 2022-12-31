<?php

namespace App\Notifications\Event;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddressChanged extends Notification implements ShouldQueue
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
                    ->subject(__(':event_title address has been changed', ['event_title' => $this->event->title]))
                    ->greeting(__(':event_title address has been changed', ['event_title' => $this->event->title]))
                    ->line(
                        __('<strong>:event_title</strong> will held :type at :address, and will starts at :starts_date and ends at :ends_date', [
                            'event_title' => $this->event->title, 
                            'type' => $this->event->is_online ? __('Online') : __('In-person'),
                            'address' => $this->event->is_online ? $this->event->online_meeting_link : $this->event->address->address_1,
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
        return [
            'event_id' => $this->event->id,
        ];
    }
}
