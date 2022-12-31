<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordChanged extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Get the notification's delivery channels.
     *
     * @return array
     */
    public function via()
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail()
    {
        return (new MailMessage)
                    ->subject(__('Your password has been changed.'))
                    ->greeting(__('Your password has been changed.'))
                    ->action(__('Login to your account'), route('login'))
                    ->line(
                        __('If you are thinking that this is a mistake, please contact with :app_name Support Team.', ['app_name' => get_system_setting('application_name')])
                    );
    }
}
