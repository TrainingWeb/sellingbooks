<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use Illuminate\Http\Request;
use Validator;

class CategoryController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::paginate(10);
        if (count($categories) < 1) {
            return $this->sendMessage('Found 0 categories');
        }
        return $this->sendData($categories);
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
            'name' => 'required|unique:categories',
            'description' => 'required',
        ], [
            'name.required' => 'Please enter name',
            'description.required' => 'Please enter description',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $category = new Category;
        $category->name = $input['name'];
        $category->id_group = $input['id_group'];
        $category->slug = str_slug($input['name']);
        $category->description = $input['description'];
        $category->save();
        return $this->sendMessage('Category '.$category->name.' created successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendErrorNotFound('Category not found.');
        }
        return $this->sendData($category);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendErrorNotFound('Category not found.');
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:categories,name,'. $category->id,
            'description' => 'required',
        ], [
            'name.required' => 'Please enter name',
            'description.required' => 'Please enter description',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $category->name = $input['name'];
        $category->id_group = $input['id_group'];
        $category->slug = str_slug($input['name']);
        $category->description = $input['description'];
        $category->save();
        return $this->sendMessage('Category '.$category->name.' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Category  $category
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $category = Category::find($id);
        if (is_null($category)) {
            return $this->sendErrorNotFound('Category not found.');
        }
        $category->delete();
        return $this->sendMessage('Category '.$id.' deleted successfully');
    }

    public function categorywithcountbooks()
    {
        $countbook = Category::withCount('books')->paginate(10);
        return $this->sendData($countbook);
    }
}