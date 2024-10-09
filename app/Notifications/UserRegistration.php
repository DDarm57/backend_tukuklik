<?php

namespace App\Notifications;

use App\Mail\UserVerificationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class UserRegistration extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */

    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database', 'firebase'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function viaConnections(): array
    {
        return [
            'mail'      => 'database',
            'database'  => 'sync',
            'firebase'  => 'sync'
        ];
    }

    public function toMail($notifiable)
    {
        return (new UserVerificationMail($this->data))
                ->to($this->data['user']->email);
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
            'url'           => $this->data['url'],
            'message'       => $this->data['message'],
            'title'         => 'Customer Baru'
        ];
    }
}
