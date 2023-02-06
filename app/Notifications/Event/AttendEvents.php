<?php

namespace App\Notifications\Event;

use App\Models\User;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AttendEvents extends Notification
{
    use Queueable;
    protected $rsvp;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($rsvp)
    {

        $this->rsvp=$rsvp;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail','database'];
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
            ->subject(__(':new_member attended to :event_name', ['new_member'=>$this->rsvp->user->full_name,'event_name' => $this->rsvp->event->title]))
            ->greeting(__(':new_member attended to :event_name', ['new_member'=>$this->rsvp->user->full_name,'event_name' => $this->rsvp->event->title]))
            ->action(__('See all attendees'), route('groups.events.attendees', ['group' => $this->rsvp->event->group->slug,'event'=>$this->rsvp->event->uid]))
            ;
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
            'rsvp_id'=>$this->rsvp->id,
        ];
    }
}
