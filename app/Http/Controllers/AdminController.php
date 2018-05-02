<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\OrderDetail;
use App\Storage;
use App\User;

class AdminController extends APIBaseController
{
    public function bookquantity()
    {
        $storage = Storage::sum('quantity');
        return $this->sendData($storage);
    }

    public function bandUser($id)
    {
        $user = User::find($id);
        $user->role = 2;
        $user->save();
        return $this->sendResponse($user, 'User just banned successfully !');
    }
}
