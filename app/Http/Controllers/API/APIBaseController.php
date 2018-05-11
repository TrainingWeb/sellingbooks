<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;

class APIBaseController extends Controller
{
    public function sort($result)
    {
        switch (request()->sort) {
            case 'atoz':
                $books = $result->with('author')->with('storage')->whereIn('highlights', [0, 1])->orderBy('name')->paginate(18);
                break;
            case 'atozdesc':
                $books = $result->with('author')->with('storage')->whereIn('highlights', [0, 1])->orderBy('name', 'DESC')->paginate(18);
                break;
            case 'price';
                $books = $result->with('author')->with('storage')->whereIn('highlights', [0, 1])->orderBy('price')->paginate(18);
                break;
            case 'pricedesc';
                $books = $result->with('author')->with('storage')->whereIn('highlights', [0, 1])->orderBy('price', 'DESC')->paginate(18);
                break;
            default:
                $books = $result->with('author')->with('storage')->whereIn('highlights', [0, 1])->paginate(18);
        }
        if (count($books) < 1) {
            return $this->sendMessage('Found 0 books.');
        }
        return response()->json($books);
    }

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
