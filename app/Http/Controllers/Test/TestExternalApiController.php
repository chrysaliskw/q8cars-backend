<?php

namespace App\Http\Controllers\Test;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\Api\ApiBaseController;
use Illuminate\Support\Facades\Log;

class TestExternalApiController extends ApiBaseController
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        // Define your valid API key (store it securely in environment variables)
        $validApiKey = 'AXJPJ0600M'; // Replace with your actual API key
       
 
        if($request->header('X-API-Key') == null) {
            return $this->error('Invalid API Key', Response::HTTP_UNPROCESSABLE_ENTITY);
        }
        $apiKey = $request->header('X-API-Key');
        if ($validApiKey != $apiKey) {
           return $this->error('Invalid API Key', Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        Log::info("==Called api from react==");

        $data = [
            'userId' => $request->userId,
            'organizationId' => (int)$request->organizationId,
            'event' => $request->event,
            'shiftId' => rand(00000,99999),
        ];

        Log::info("calling function");

        Log::info($data);
        // Assuming you have raw JSON data
        $rawData = json_encode($data);

        // Set the API URL
        $apiUrl = 'https://us-central1-carecrown-wl-staging.cloudfunctions.net/clockInUpdateHttp';

        // Send the request with raw JSON
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'X-API-Key' => $validApiKey,
        ])->post($apiUrl, $data);

        if ($response->failed()) {
            Log::error('HTTP error: ' . $response->status() . ' - Response: ' . $response->body());
            return $this->error('API call failed with HTTP status ' . $response->status(), Response::HTTP_FORBIDDEN);
        } else {
            Log::info($response->json());
        }

        // Fetch data or perform any other actions
        $res = [
            'status' => 200,
            'message' => 'Challenge progress updated',
            'userId' => $request->userId,
            'organizationId' => $request->organizationId,
            'shiftId' => $data['shiftId'],
            'event' => $request->event,
        ];
  
        return $this->success(['data' => $res], 'Api call sucess', Response::HTTP_OK);

    }
}
