<?php

namespace App\Notifications\User;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccess extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

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
                    ->subject(__('Your payment :order_id has been successfully received for :plan_name', ['order_id' => $this->order->id, 'plan_name' => $this->order->plan->name]))
                    ->greeting(__('Your payment :order_id has been successfully received for :plan_name', ['order_id' => $this->order->id, 'plan_name' => $this->order->plan->name]))
                    ->action(__('Manage Your Subscription'), route('account.settings.organizer'))
                    ->line(
                        __('This subscription will automatically renew every month until canceled. Get more details on renewal and cancellation on the Organizer Subscription section of your account. If you have any questions, please use our Help Center to find the answer to your question or to contact our team.')
                    );
    }
}
