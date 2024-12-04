<?php

namespace App\Services\Admin\PushNotification;

use Google_Client;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Google\Service\FirebaseCloudMessaging;
use Google_Service_FirebaseCloudMessaging;
use Google\Service\FirebaseCloudMessaging\Notification;
use Google\Service\FirebaseCloudMessaging\SendMessageRequest;
use Google\Service\FirebaseCloudMessaging\Message as FcmMessage;

class FirebasePushNotificationService
{
    const ACCESS_TOKEN_FILE_PATH = 'cd-website_token.txt';

    protected $fcmTokens;

    protected $notification;

    protected $key;

    protected $title;

    protected $body;

    protected $client;

    protected $fcm;

    public function __construct()
    {
        $googleClient = new Google_Client();

        $googleClient->setAuthConfig(storage_path('pushnotification/cd-website-347912-firebase-adminsdk-26fyd-e63932ba6d.json'));

        $googleClient->addScope('https://www.googleapis.com/auth/firebase.messaging');

        $accessToken = $googleClient->fetchAccessTokenWithAssertion()['access_token'];
        Storage::disk('local')->put(self::ACCESS_TOKEN_FILE_PATH, $accessToken);
        $client = new Client([
            'base_uri' => config('services.fcm.base_url').config('services.fcm.project_id').'/',
            'headers' => [
                'Authorization' => 'Bearer '.$accessToken,
                'Content-Type' => 'application/json',
            ],

        ]);

        $this->fcm = new FirebaseCloudMessaging($googleClient);
    }

    /**
     * Send a push notification to a topic.
     *
     * @param  string  $topic
     * @param  Notification  $notification
     * @return void
     */
    public function sendTopicNotification($topic, $title, $body, $image = null)
    {
        try {
            $message = new FcmMessage();
            $message->setNotification(
                new Notification([
                    'title' => $title,
                    'body' => $body,
                    'image' => $image,
                ])

            );
            $data = [
                'title' => $title,
                'message' => $body,
            ];

            if($image){
                $data['image'] = $image;
            }

            $message->setData($data);
            $message->setTopic($topic);
            $sendMessageRequest = new SendMessageRequest();
            $sendMessageRequest->setMessage($message);

            $response = $this->fcm->projects_messages->send(
                'projects/'.config('services.fcm.project_id'),
                $sendMessageRequest
            );

            Log::info(json_encode($response));
        } catch (\Google_Service_Exception $ex) {
            // Handle the exception
            logger($ex);
        }
    }

    public function sendNotification($title, $body, $fcmTokens)
    {

        if (is_array($fcmTokens)) {
            foreach ($fcmTokens as $token) {
                $this->sendSingleNotification($title, $body, $token);
            }
        } else {
            $this->sendSingleNotification($title, $body, $fcmTokens);
        }
    }

    private function sendSingleNotification($title, $body, $fcmToken)
    {

        try {
            $message = new FcmMessage();
            $message->setNotification(
                new Notification([
                    'title' => $title,
                    'body' => $body,
                ])
            );
            $data = [
                'title' => $title,
                'message' => $body,
            ];
            $message->setData($data);
            $message->setToken($fcmToken);

            $sendMessageRequest = new SendMessageRequest();
            $sendMessageRequest->setMessage($message);

            $response = $this->fcm->projects_messages->send(
                'projects/'.config('services.fcm.project_id'),
                $sendMessageRequest
            );
            Log::info(json_encode($response));
        } catch (\Google_Service_Exception $ex) {
            // Handle the exception
            logger($ex);
        }
    }
}
