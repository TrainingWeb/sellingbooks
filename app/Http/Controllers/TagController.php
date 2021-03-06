<?php

namespace App\Http\Controllers;

use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\Tag;
use Illuminate\Http\Request;
use Validator;

class TagController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = Tag::paginate(15);
        if (count($tags) < 1) {
            return $this->sendMessage('Found 0 tags');
        }
        return $this->sendData($tags);
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
            'name' => 'required|unique:tags',
            'slug' => 'required',
            'description' => 'required',
        ], [
            'name.required' => 'Please enter name',
            'description.required' => 'Please enter description',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $tag = new Tag;
        $tag->name = $input['name'];
        $tag->slug = str_slug($input['name']);
        $tag->description = $input['description'];
        $tag->save();
        return $this->sendMessage('Tag '.$tag->id.' created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $tag = Tag::find($id);
        if (is_null($tag)) {
            return $this->sendErrorNotFound('Tag not found.');
        }
        return $this->sendData($tag);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $tag = Tag::find($id);
        if (is_null($tag)) {
            return $this->sendErrorNotFound('Tag not found.');
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:tags,name,'. $tag->id,
            'description' => 'required',
        ], [
            'name.required' => 'Please enter name',
            'description.required' => 'Please enter description',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }

        $tag->update($input);
        return $this->sendMessage('Tag '.$tag->name.' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Tag  $tag
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $tag = Tag::find($id);
        if (is_null($tag)) {
            return $this->sendErrorNotFound('Tag not found.');
        }
        $tag->delete();
        return $this->sendMessage('Tag '.$id.' deleted successfully');
    }
}
