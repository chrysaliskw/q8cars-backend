<?php

namespace App\Mail\Admin;


use App\Models\Admin;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPasswordLink extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * @var \App\Models\Admin
     */
    public $admin;

  
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Admin $admin)
    {
      
        $this->admin = $admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // return $this->markdown('emails.admin.reset_password_link');
        return $this->markdown('emails.admin.reset_password_link_template');
    }
}
