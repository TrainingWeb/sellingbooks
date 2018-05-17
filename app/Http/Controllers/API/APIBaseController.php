<?php
namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller as Controller;
use App\User;
use Hash;
use Validator;

class APIBaseController extends Controller
{
    public function ValidationCreateUserFromIndex($input)
    {
        return $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ], [
            'name.required' => 'Please enter name',
            'email.required' => 'Please enter email.',
            'email.unique' => 'Email alreay exits, please enter another email !',
            'password.required' => 'Password can not null.',
        ]);
    }

    public function CreateUserFromIndex($input)
    {
        return User::create([
            'name' => $input['name'],
            'email' => $input['email'],
            'role' => 0,
            'password' => Hash::make($input['password']),
        ]);
    }

    public function ValidationCheckInfo($input)
    {
        return $validator = Validator::make($input, [
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
        ], [
            'name.required' => 'Name cannot be null',
            'phone.required' => 'Phone cannot blank !',
            'address.required' => 'Address cannot blank !',
        ]);
    }

    public function UpdateDataUserCheckInfo($input, $id)
    {
        $user = User::find($id);
        return $user->update([
            'name' => $input['name'],
            'phone' => $input['phone'],
            'address' => $input['address'],
        ]);
    }

    public function ValidationUpdateDataInfoUser($input, $id)
    {
        $user = User::find($id);
        return $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|unique:users,email,' . $user->id,
            'password' => 'required',
            'phone' => 'unique:users,phone,' . $user->id,
        ], [
            'name.required' => 'Please enter name',
            'email.required' => 'Please enter email',
            'password.required' => 'Please enter password',
            'phone.unique' => 'This phone number already exits ! Please enter another phone number,' . $user->id,
        ]);
    }

    public function UpdateDataInfoUser($input, $id, $avatar)
    {
        $user = User::find($id);
        return $user->update([
            'name' => $input['name'],
            'email' => $input['email'],
            'password' => $input['password'],
            'birthday' => $input['birthday'],
            'address' => $input['address'],
            'phone' => $input['phone'],
            'avatar' => $avatar,
        ]);
    }

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
            return response()->json('Found 0 books.', 200);
        }
        return response()->json($books);
    }

    public function sortt($result, $data)
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
            return response()->json(['Message'=>'Found 0 books', 'data'=>$data]);
        }
        return response()->json(['books'=>$books, 'data'=>$data]);
    }

    public function sendData($result)
    {
        $response = [
            'status' => true,
            'data' => $result,
        ];
        return response()->json($response, 200);
    }

    public function sendMessage($message)
    {
        $response = [
            'status' => true,
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
