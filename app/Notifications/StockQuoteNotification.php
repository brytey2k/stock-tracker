<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Collection;

class StockQuoteNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private Collection $quote)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Hi ' . $notifiable->name)
            ->subject('Stock quote received')
            ->line('Your stock quote details are here:')
            ->line('Name: ' . $this->quote->get('name'))
            ->line('Symbol: ' . $this->quote->get('symbol'))
            ->line('Open: ' . $this->quote->get('open'))
            ->line('High: ' . $this->quote->get('high'))
            ->line('Low: ' . $this->quote->get('low'))
            ->line('Close: ' . $this->quote->get('close'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
