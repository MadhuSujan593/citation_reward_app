<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaperCitedNotification extends Notification
{
    use Queueable;

    public $paper;
    public $citer;

    /**
     * Create a new notification instance.
     */
    public function __construct($paper, $citer)
    {
        $this->paper = $paper;
        $this->citer = $citer;
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
            ->subject("New Citation Claim: {$this->paper->title} - Citation Hub")
            ->view('emails.paper-cited', [
                'funder' => $notifiable,
                'citer' => $this->citer,
                'paper' => $this->paper,
                'amount' => 100
            ]);
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
