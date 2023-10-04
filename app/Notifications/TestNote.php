<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNote extends Notification
{
    use Queueable;

    private $invoice_id;
    /**
     * Create a new notification instance.
     */
    public function __construct($invoice_id)
    {
        $this->invoice_id= $invoice_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }
    public function toArray(object $notifiable): array
    {
        return [
            'invoice_id'=>$this->invoice_id,
            'title'=>'تم اضافة فاتورة جديد بواسطة :',
            'user'=> auth()->user()->name,
        ];
    }
}
