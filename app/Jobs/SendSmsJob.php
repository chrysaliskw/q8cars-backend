<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendSmsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $contactNumber;
    protected $message;
    protected $lang;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($contactNumber, $msg)
    {
        $this->contactNumber = $contactNumber;
        $this->message = $msg;
        $this->lang = 1;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $username = config('services.sms.username');
        $sender = config('services.sms.senderId');
        $password = config('services.sms.password');

        $url = "https://www.kwtsms.com/API/send/";

        $response = Http::post($url, [
            "username" => $username,
            "password" => $password,
            "sender" => $sender,
            "message" => $this->message,
            "mobile" => $this->contactNumber,
            "lang" => $this->lang,
            "test" => 1,
        ], [
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
        ]);

        // Access the response body
        $responseBody = $response->body();

        Log::info($responseBody);
    }
}
