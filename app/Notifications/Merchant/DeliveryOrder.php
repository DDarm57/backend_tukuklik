<?php

namespace App\Notifications\Merchant;

use App\Helpers\Helpers;
use App\Mail\DeliveryOrderMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DeliveryOrder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public $deliveryOrder; 
    public $data;

    public function __construct($deliveryOrder, $data)
    {
        $this->deliveryOrder = $deliveryOrder;
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
        $this->data['name'] = $this->deliveryOrder->purchaseOrder->quotation->merchant->pic->name;
        return (new DeliveryOrderMail($this->deliveryOrder, $this->data))
                 ->to($this->deliveryOrder->purchaseOrder->quotation->merchant->pic->email)
                 ->bcc(Helpers::additionalMail());
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
