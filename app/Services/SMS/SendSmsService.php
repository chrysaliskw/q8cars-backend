<?php
namespace App\Services\SMS;

use App\Contracts\SmsInterface;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class SendSmsService 
{

    /**
     * @var string
     */

    private $username;
    /**
     * @var string
     */
    private $password;

    /**
     * Mobile to message mapping array. The below is the sample structure
     * [
     *    '91963215487' => 'Mefriend, Your OTP to login: 1234',
     * ]
     *
     * @var array
     */
    private $mobilesMessageMap = [];

    /**
     * @var string
     */
    private $senderId;

    /**
     * @var array
     */
    private $post;

    /**
     * @var bool
     */
    private $isError;

    /**
     * @var string
     */
    private $errorMessage;

    const BASE_URL = 'https://mshastra.com/sendsms_api_json.aspx';

    public function __construct(array $mobilesMessageMap)
    {
        $this->username = config('services.sms_gateway.mobishastra.username');
        $this->password = config('services.sms_gateway.mobishastra.password');
        $this->senderId = config('services.sms_gateway.mobishastra.sender_id');
        $this->mobilesMessageMap = $mobilesMessageMap;
        $this->post = [];
        $this->isError = false;
        $this->errorMessage = '';
        Log::info("Calling SMS Gateway for");
        Log::info($mobilesMessageMap);
    }

    /**
     * Sends the SMS
     *
     * @return void
     */
    public function send()
    {
        $this->loadPostData()->call();
    }

    /**
     * Loads the post data.
     *
     * @return MobishastraSmsService
     */
    private function loadPostData():MobishastraSmsService
    {

        foreach($this->mobilesMessageMap as $mobile => $message){
            $this->post []= [
                'user' => $this->username,
                'pwd' => $this->password,
                'number' => $mobile,
                'msg' => $message,
                'sender' => $this->senderId,
                'language' => 'Unicode',
            ];
        }

        return $this;
    }

    /**
     * Makes the SMS API call.
     *
     * @return void
     */
    private function call()
    {
        Log::info(json_encode($this->post));

        $response = Http::withBody(json_encode($this->post), 'application/json')
            ->post(self::BASE_URL);

        // dd($response);

        if (!$response->successful()) {
            $this->isError = true;
            $this->errorMessage = $response->json()['message'];
            Log::error('SMS gateway error', $response->json());
        }

        Log::info($response->json());

        return $response->json();
    }
}