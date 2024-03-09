<?php

namespace App\Jobs;

use App\Mail\MailListMail;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class MailListJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $tries = 3;
    /**
     * Create a new job instance.
     */
    public function __construct(protected $message)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {


        Mail::to($this->message->send_to)->cc($this->message->send_cc)->bcc($this->message->send_bcc)
        ->later(now()->setDateTimeFrom($this->message->send_date),new MailListMail($this->message));

    }
    public function failed(Exception $exception)
    {
        session(['job_failed_message' => 'Failed to process job']);
    }
}
