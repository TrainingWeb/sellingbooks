<?php

namespace App\Http\Controllers;

use App\User;
use App\Book;
use App\Storage;
use App\OrderDetail;
use Illuminate\Http\Request;
use App\Http\Controllers\API\APIBaseController as APIBaseController;

class AdminController extends APIBaseController
{
    public function bookquantity()
    {
        $storage = Storage::sum('quantity');
        return response()->json(['totalbooks'=>$storage], 200);
    }

    public function bandUser(Request $request, $id)
    {
        $user = User::find($id);
        if($user->id == $request->user()->id){
            return $this->sendMessage('You are can not band yourself !');
        }elseif($user->role == 1){
            return $this->sendMessage('You are can not band another administrator !');
        }
        if (is_null($user)) {
            return $this->sendErrorNotFound('User not found !');
        }
        if ($user->role == 2) {
            return $this->sendMessage('User ' . $user->name . ' got banned before !');
        }
        $user->role = 2;
        $user->save();
        return $this->sendMessage('User '. $user->name .' just banned successfully !');
    }

    
}
