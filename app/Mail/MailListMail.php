<?php

namespace App\Mail;

use App\Models\MailList;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Attachment;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MailListMail extends Mailable
{
    use Queueable, SerializesModels;
    public $tries = 3;
    /**
     * Create a new message instance.
     */
    public function __construct(protected MailList $message)
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->message->send_title,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'MailList.send',
            with:['body'=>$this->message->send_body]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments()
    {
        if($this->message->send_file!=null)
        return [
            Attachment::fromPath(storage_path('uploads/'.$this->message->send_file))
        ];
    }

}
