<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionCancelled extends Notification implements ShouldQueue
{
    use Queueable;

    protected $subscription;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($subscription)
    {
        $this->subscription = $subscription;
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
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        return (new MailMessage)
                    ->subject(__('Your :app_name subscription cancelled', ['app_name' => get_system_setting('application_name')]))
                    ->greeting(__('Your :app_name subscription cancelled', ['app_name' => get_system_setting('application_name')]))
                    ->line(
                        __('Your subscripton for <strong>:plan_name</strong> has been cancelled.', ['plan_name' => $this->subscription->plan->name])
                    );
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array
     */
    public function toArray()
    {
        return [
            'subscription_id' => $this->subscription->id,
            'plan_name' => $this->subscription->plan->name
        ];
    }
}
