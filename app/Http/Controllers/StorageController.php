<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\Storage;
use Illuminate\Http\Request;
use Validator;

class StorageController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $storage = Storage::paginate(15);
        if (count($storage) < 1) {
            return $this->sendMessage('Found 0 storage');
        }
        return $this->sendData($storage);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Storage  $storage
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $storage = Storage::find($id);
        if(is_null($storage)){
            return $this->sendErrorNotFound('Storage not found.');
        }
        return $this->sendData($storage);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Storage  $storage
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Storage  $storage
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     $storage = Storage::find($id);
    //     if(is_null($storage)){
    //         return $this->sendErrorNotFound('Storage not found.');
    //     }
    //     $input = $request->all();
    //     $validator = Validator::make($input, [
    //         'quantity' => 'required',
    //         'id_book' => 'required',
    //     ], [
    //         'quantity.required' => 'Please enter quantity',
    //         'id_book.required' => 'Please choose book',
    //     ]);
    //     if ($validator->fails()) {
    //         return $this->sendErrorValidation('Validation Error.', $validator->errors());
    //     }
    //     $storage->quantity = $input['quantity'];
    //     $storage->id_book = $input['id_book'];
    //     $storage->save();
    //     return $this->sendMessage('Storage '.$id.' updated successfully');
    // }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Storage  $storage
     * @return \Illuminate\Http\Response
     */
    // public function destroy($id)
    // {
    //     $storage = Storage::find($id);
    //     if(is_null($storage)){
    //         return $this->sendErrorNotFound('Storage not found.');
    //     }
    //     $storage->delete();
    //     return $this->sendMessage('Storage '.$id.' deleted successfully');
    // }
}
