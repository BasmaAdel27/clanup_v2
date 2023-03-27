<?php

namespace App\Notifications\Group;

use App\Models\GroupMembership;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembershipChanged extends Notification implements ShouldQueue
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
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the notification's message line
     *
     * @return string
     */
    protected function getLine()
    {
        $membership = $this->membership->membership;
        if (in_array($membership, [GroupMembership::CO_ORGANIZER, GroupMembership::ASSISTANT_ORGANIZER, GroupMembership::EVENT_ORGANIZER, GroupMembership::MEMBER])) {
            $line = __('Your membership changed for the group <strong>:group_name</strong>, your are now <strong>:role_name</strong> of the group.', ['group_name' => $this->membership->group->name, 'role_name' => $this->membership->getRoleName()]);
        } else if ($membership == GroupMembership::REMOVED) {
            $line = __('You are removed from the group <strong>:group_name</strong> by organizers. If you are thinking that this is a mistake, you can re-join the group by sending a request to the organizers.', ['group_name' => $this->membership->group->name]);
        } else if ($membership == GroupMembership::BLACKLISTED) {
            $line = __('You are banned from the group <strong>:group_name</strong> by organizers. If you are thinking that this is a mistake, please contact with :app_name Support Team.', ['group_name' => $this->membership->group->name, 'app_name' => get_system_setting('application_name')]);
        } else {
            $line = '';
        }

        return $line;
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
                    ->subject(__('Your membership has been changed for :group_name', ['group_name' => $this->membership->group->name]))
                    ->greeting(__('Your membership has been changed for :group_name', ['group_name' => $this->membership->group->name]))
                    ->line($this->getLine());
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
        ];
    }
}
