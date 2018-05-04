<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;

class APIBaseController extends Controller
{
    public function sendData($result)
    {
        $response = [
            'data' => $result,
        ];
        return response()->json($response, 200);
    }

    public function sendMessage($message)
    {
        $response = [
            'message' => $message,
        ];

        return response()->json($response, 200);
    }

    public function sendResponse($result, $message)
    {
        $response = [
            'status' => true,
            'data' => $result,
            'message' => $message,
        ];
        return response()->json($response, 200);
    }

    public function sendErrorNotFound($message)
    {
        $response = [
            'status' => false,
            'message' => $message,
        ];

        return response()->json($response, 404);
    }
    public function sendErrorValidation($error, $errorMessages = [], $code = 422)
    {
        $response = [
            'status' => false,
            'message' => $error,
        ];

        if (!empty($errorMessages)) {
            $response['data'] = $errorMessages;
        }

        return response()->json($response, 422);
    }
    public function sendErrorBookQuantity($result, $message = [])
    {
        $message = null;
        $response = [
            'status' => false,
            'id_book' => $result,
            'message' => $message,
        ];

        return response()->json($response, 200);
    }
    public function sendErrorPermission($message)
    {
        $response = [
            'status' => false,
            'message' => $message,
        ];
        return response()->json($response, 403);
    }

    public function sendErrorAuth($message)
    {
        $response = [
            'status' => false,
            'message' => $message,
        ];
        return response()->json($response, 401);
    }
}
