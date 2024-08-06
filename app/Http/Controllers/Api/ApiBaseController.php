<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;

class ApiBaseController extends Controller
{
    /**
     * @param mixed $result
     * @param stirng $message
     * @param int $code
     * 
     * @return \Illuminate\Http\Response
     */
    protected function success($result, $message, $code = 200)
    {
        $response['status'] = $code;
        $response['message'] = $message;

        if (is_array($result) && array_key_exists('common_data', $result)) {
            $response['common_data'] = $result['common_data'];    
        }
        
        $response['data'] = $result['data'];
        
        return response()->json($response)->setStatusCode($code);
    }

    /**
     * @param string $errors
     * @param int $code
     * 
     * @return \Illuminate\Http\Response
     */
    protected function error($errors, $code = 404)
    {
        $response = [
            'status'  => $code,
            'message' => $errors
        ];

        return response()->json($response)->setStatusCode($code);   
    }
}