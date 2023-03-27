<?php

namespace App\Notifications\Group;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MemberLeaved extends Notification implements ShouldQueue
{
    use Queueable;

    protected $membership;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($membership)
    {
        $this->membership = $membership;
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
        return (new MailMessage)
                    ->subject(__('One member leaved :group_name', ['group_name' => $this->membership->group->name]))
                    ->greeting(__('One member leaved :group_name', ['group_name' => $this->membership->group->name]))
                    ->action(__('See all members'), route('groups.members', ['group' => $this->membership->group->slug]))
                    ->line(
                        __('<strong>:member_name</strong> leaved :group_name', ['member_name' => $this->membership->user->full_name, 'group_name' => $this->membership->group->name])
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
            'group_id' => $this->membership->group->id,
            'member_id' => $this->membership->user->id,
        ];
    }
}
