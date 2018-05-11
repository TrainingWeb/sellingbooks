<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\User;
use Illuminate\Http\Request;
use Validator;

class UserController extends APIBaseController
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        $users = User::whereIn('role', [0, 1])->paginate(18);
        if (count($users) < 1) {
            return $this->sendMessage('Found 0 users');
        }
        return $this->sendData($users);
    }

    public function show($id)
    {
        $user = User::whereIn('role', [0, 1])->find($id);
        if (!$user) {
            return $this->sendErrorNotFound('User not found !');
        }
        return $this->sendData($user);
    }

    public function destroy(Request $request, $id)
    {
        $user = User::whereIn('role', [0, 1])->find($id);
        if (!$user) {
            return $this->sendErrorNotFound('User not found !');
        }
        if ($user->id == $request->user()->id) {
            return $this->sendMessage('You cannot delete your account');
        }
        if ($user->role == 1) {
            return $this->sendMessage('Cannot delete another administrator !');
        }
        $user->delete();
        return $this->sendMessage('User deleted successfully !');
    }

    public function store(Request $request)
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
        if ($request->hasFile('avatar')) {
            $user->avatar = $request->file('avatar')->store('/public/images');
        }
        $user->birthday = $input['birthday'];
        $user->address = $input['address'];
        $user->phone = $input['phone'];
        $user->email = $input['email'];
        $user->role = 0;
        $user->password = bcrypt($input['password']);
        $user->save();
        return $this->sendResponse($user, 'Created user successfully !');
    }

    public function update(Request $request, $id)
    {
        $user = User::whereIn('role', [0, 1])->find($id);
        if (!$user) {
            return $this->sendErrorNotFound('User not found !');
        }
        $input = null;
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'unique:users,email,' . $user->id,
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
        $user->name = $input['name'];
        if ($request->hasFile('avatar')) {
            $user->avatar = $request->file('avatar')->store('/public/images');
        }
        $user->birthday = $input['birthday'];
        $user->address = $input['address'];
        $user->phone = $input['phone'];
        $user->email = $input['email'];
        $user->password = bcrypt($input['password']);
        $user->save();
        return $this->sendMessage('Updated successfully !');
    }

}
