<?php

// app/Http/Controllers/API/BaseController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller as Controller;

class BaseController extends Controller
{
    /**
    * success response method.
    *
    * @param  $result
    * @param  $message
    * @return \Illuminate\Http\Response
    */
    public function sendResponse($result, $message)
    {
        $response = [
            'success' => true,
            'data'    => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    /**
    * return error response.
    *
    * @param  $error
    * @param  $errorMessages
    * @param  $code
    * @return \Illuminate\Http\Response
    */
    public function sendError($error, $errorMessages = [], $code = 404)
    {
        $response = [
            'success' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }
        return response()->json($response, $code);
    }
}

