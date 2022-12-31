<?php

namespace App\Notifications\Event;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Spatie\IcalendarGenerator\Enums\ParticipationStatus;

class Announcement extends Notification implements ShouldQueue
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
        $user = User::find($notifiable->id) ?? new User();
        return (new MailMessage)
                    ->subject(__('Upcoming event: :event_title', ['event_title' => $this->event->title]))
                    ->greeting(__('Upcoming event: :event_title', ['event_title' => $this->event->title]))
                    ->line(
                        __('<strong>:event_title</strong> will starts at <strong>:starts_date</strong> and ends at <strong>:ends_date</strong>', [
                            'event_title' => $this->event->title, 
                            'starts_date' => convertToLocal($this->event->starts_at, 'M d, Y H:i', false, $user),
                            'ends_date' => convertToLocal($this->event->ends_at, 'M d, Y H:i', false, $user),
                        ])
                    )
                    ->action(__('See event details'), route('groups.events.show', ['group' => $this->event->group->slug, 'event' => $this->event->uid]))
                    ->attachData($this->event->getICalDataForUser($user, ParticipationStatus::needs_action()), 'invite.ics', [
                        'mime' => 'text/calendar; charset=UTF-8; method=REQUEST',
                    ]);
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
