<?php

namespace App\Notifications\Group;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContentVisibilityChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $group;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($group)
    {
        $this->group = $group;
    }

    /**
     * Get the notification's delivery channels.
     *
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
        $user = User::find($notifiable->id) ?? null;

        return (new MailMessage)
                    ->subject(__(':group_name has been Closed Group', ['group_name' => $this->group->name]))
                    ->greeting(__(':group_name content visibility has been changed as closed.', ['group_name' => $this->group->name]))
                    ->line(
                        __('From now on, this group will be closed and can not be changed as public again. Some of the information is still accessible by public but your information can only be seen by the members of the group and the new members need to be approved before they can join the group.')
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
            'group_id' => $this->group->id
        ];
    }
}
