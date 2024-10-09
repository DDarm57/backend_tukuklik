<?php

namespace App\Notifications\Customer;

use App\Mail\NegotiationMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Negotiation extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $quotation;
    public $data;

    public function __construct($quotation, $data)
    {
        $this->quotation = $quotation;
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

    public function viaConnections(): array
    {
        return [
            'mail'      => 'database',
            'database'  => 'sync',
            'firebase'  => 'sync'
        ];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $this->data['name'] = $this->quotation->user->name;
        return (new NegotiationMail($this->quotation, $this->data))
                 ->to($this->quotation->user->email);
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
            'title'         => $this->data['subject']
        ];
    }
}
