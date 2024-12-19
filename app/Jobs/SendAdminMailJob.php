<?php

namespace App\Jobs;

use App\Mail\Admin\SendAdminMail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\SmtpSetting;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendAdminMailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $details;

    protected $emailId;

    public function __construct(array $details, string $emailId)
    {
        $this->details = $details;
        $this->emailId = $emailId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        try {
            $resp = Mail::to($this->emailId)->cc($this->details['cc'] ?? [])->send(new SendAdminMail($this->details));
            if ($resp) {
                Log::info('Email sent successfully', ['recipient' => $this->emailId]);
            } else {
                Log::error('Failed to send email', ['recipient' => $this->emailId]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending email: ' . $e->getMessage(), ['recipient' => $this->emailId]);
        }
    }
}
