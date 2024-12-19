<?php

namespace App\Http\Controllers;

use App\Models\SmtpSetting;
use App\Mail\Admin\TestMail;
use Illuminate\Http\Request;
use App\Jobs\SendAdminMailJob;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MailControllerTest extends Controller
{
    // function sendEmail(){
    //     $to = "nabeelcryf@gmail.com";
    //     $msg = 'dummy mail';
    //     $subject = "dummy";
    //     Mail::to($to)->send(new TestMail($msg, $subject));
    // }

    public function sendEmail()
    {
        $emailId = "nabeelcryf@gmail.com";

        $settings = SmtpSetting::checkSmtpConfig();
        Log::info('SMTP Configuration:', ['settings' => $settings]);

        if (!$settings) {
            return response()->json(['message' => 'SMTP configuration is missing or incomplete'], 500);
        }

        $details = [
            'title' => 'Test Mail',
            'page'  => 'emails.admin.testmail',
            'cc'    => [],
        ];

        try {
            Log::info('Attempting to dispatch SendAdminMailJob');
            dispatch(new SendAdminMailJob($details, $emailId));
            Log::info('SendAdminMailJob dispatched successfully');

            return response()->json(['message' => 'Test email dispatched successfully'], 200);

        } catch (\Exception $e) {
            Log::error('Error dispatching email job: ' . $e->getMessage());
            return response()->json(['message' => 'Failed to dispatch email job'], 500);
        }
    }
}
