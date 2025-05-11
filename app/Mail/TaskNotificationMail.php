<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TaskNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $messageText;

    /**
     * Create a new message instance.
     */
    public function __construct(string $messageText)
    {
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject('Nova Notificação de Tarefa')
                    ->view('emails.task_notification')
                    ->with(['messageText' => $this->messageText]);
    }
   
}
