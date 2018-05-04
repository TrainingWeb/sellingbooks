<?php

namespace App\Http\Controllers;

use App\User;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\APIBaseController as APIBaseController;

class UserController extends APIBaseController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $api_token = Auth::user()->createToken('test')->accessToken;
            return response()->json(['api_token' => $api_token]);
        } else {
            return $this->sendMessage('Email or password is not correct !');
        }
    }

    public function create(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ], [
            'name.required' => 'Please enter name',
            'email.required' => 'Please enter email.',
            'email.unique' => 'Email alreay exits, please enter another email !',
            'password.required' => 'Password can not null.',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $user = new User;
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->role = 0;
        $user->password = bcrypt($input['password']);
        $user->save();
        Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        $api_token = Auth::user()->createToken('test1')->accessToken;
        return response()->json(['api_token' => $api_token, 'role'=>0, 'message' => 'User created successfully !']);
    }

    public function update(Request $request)
    {
        $user = User::find($request->user()->id);
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'unique:users,email,' . $user->id,
            'password' => 'required',
            'age' => 'required',
            'address' => 'required',
            'phone' => 'required|unique:users,phone,' . $user->id,
        ], [
            'name.required' => 'Please enter name',
            'email.required' => 'Please enter email',
            'password.required' => 'Please enter password',
            'age.required' => 'Please enter age',
            'address.required' => 'Please enter address',
            'phone.required' => 'Please enter phone',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $file->move('./images', $file->getClientOriginalName('avatar'));
            $avatar = $file->getClientOriginalName('avatar');
            $user->avatar = $avatar;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->age = $request->age;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->save();
        return $this->sendMessage('Updated ' . $user->name . ' successfully !');
    }

    public function changUserRole(Request $request, $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendErrorNotFound('User not found !');
        }
        $user->role = $request->role;
        $user->save();
        return $this->sendMessage('Changed role of user ' . $user->name . ' successfully !');
    }

    public function show(Request $request)
    {
        $user = User::find($request->user()->id);
        if (is_null($user)) {
            return $this->sendErrorNotFound('User not found !');
        }
        if ($user->role == 1) {
            $role = 1;
        } elseif ($user->role == 0) {
            $role = 0;
        } else {
            $role = null;
        }
        return $this->sendData(['user' => $user, 'role' => $role]);

    }
}
