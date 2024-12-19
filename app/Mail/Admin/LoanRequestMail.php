<?php

namespace App\Mail\Admin;

use App\Models\BankSuggestionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LoanRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\BankSuggestionRequest
     */
    public $loan_request;

    public $status;

    /**
     * Create a new message instance.
     *
     * @param BankSuggestionRequest $loan_request
     * @param string $status
     */
    public function __construct(BankSuggestionRequest $loan_request, string $status)
    {
        $this->loan_request = $loan_request;
        $this->status = $status;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if ($this->status === BankSuggestionRequest::STATUS_ACCEPTED) {
            return $this->markdown('emails.admin.loan_accepted')
                ->subject('Your Loan Request has been Accepted');
        } elseif ($this->status === BankSuggestionRequest::STATUS_REJECTED) {
            return $this->markdown('emails.admin.loan_rejected')
                ->subject('Your Loan Request has been Rejected');
        }
    }
}
