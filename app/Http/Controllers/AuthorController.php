<?php
namespace App\Http\Controllers;

use Validator;
use App\Author;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\APIBaseController as APIBaseController;

class AuthorController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $author = Author::paginate(18);
        if (count($author) < 1) {
            return $this->sendMessage('Found 0 author');
        }
        return $this->sendData($author);
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
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:authors',
            'phone' => 'unique:authors',
            'email' => 'unique:authors',
        ], [
            'name.required' => 'Please enter name',
            'name.unique' => 'Name already for another auuthor ! Please enter another name.',
            'phone.unique' => 'Phone already for another author ! Please enter another phone.',
            'email.unique' => 'Email already for another author ! Please enter another email.',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $author = new Author;
        $author->name = $input['name'];
        $author->slug = str_slug($author->name);
        $author->description = $input['description'];
        $author->phone = $input['phone'];
        $author->address = $input['address'];
        $author->email = $input['email'];
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $file->move('./images', $file->getClientOriginalName('avatar'));
            $avatar = $file->getClientOriginalName('avatar');
            $author->avatar = $avatar;
        }
        $author->save();
        return $this->sendMessage('Author '.$author->name.' created successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $author = Author::find($id);
        if (is_null($author)) {
            return $this->sendErrorNotFound('Author not found.');
        }
        return $this->sendData($author);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $author = Author::find($id);
        if (is_null($author)) {
            return $this->sendErrorNotFound('Author not found.');
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
        ], [
            'name.required' => 'Please enter name',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $validator = Validator::make($input, [
            'name' => 'unique:authors,name,' . $author->id,
            'phone' => 'unique:authors,phone,' . $author->id,
            'email' => 'unique:authors,email,' . $author->id,
        ], [
            'name.unique' => 'Name already for another author ! Please enter another name.',
            'phone.unique' => 'Phone already for another author ! Please enter another name.',
            'email.unique' => 'Email already for another author ! Please enter another name.',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $author->name = $input['name'];
        $author->slug = str_slug($author->name);
        $author->description = $input['description'];
        $author->phone = $input['phone'];
        $author->address = $input['address'];
        $author->email = $input['email'];
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $file->move('./images', $file->getClientOriginalName('avatar'));
            $avatar = $file->getClientOriginalName('avatar');
            $author->avatar = $avatar;
        }
        $author->save();
        return $this->sendMessage('Author '.$author->name.' updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Author  $author
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $author = Author::find($id);
        if (is_null($author)) {
            return $this->sendErrorNotFound('Author not found.');
        }
        $author->delete();
        return $this->sendMessage('Author '.$id.' deleted successfully.');
    }
}
