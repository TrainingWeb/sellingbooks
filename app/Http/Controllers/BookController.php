<?php

namespace App\Http\Controllers;

use App\Book;
use App\History;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\Storage;
use App\Tag;
use Illuminate\Http\Request;
use Validator;

class BookController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::whereIn('highlights', [0, 1])->paginate(18);
        if (count($books) < 1) {
            return $this->sendMessage('Found 0 book');
        }
        return $this->sendData($books);
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
            'name' => 'required',
            'image' => 'required|mimes:jpeg,bmp,png',
            'price' => 'required',
            'highlights' => 'required',
            'description' => 'required',
            'id_author' => 'required',
            'id_category' => 'required',
        ], [
            'name.required' => 'Please enter book name',
            'image.required' => 'Please enter book image',
            'image.mimes' => 'Image must be image type',
            'price.required' => 'Please enter price',
            'highlights.required' => 'Please choose type highlights',
            'description.required' => 'Please enter book description',
            'id_author.required' => 'Please choose author',
            'id_category.required' => 'Please choose category',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $book = new Book;
        $book->name = $input['name'];
        $book->slug = str_slug($request->name);
        $book->price = $input['price'];
        $book->highlights = $input['highlights'];
        $book->description = $input['description'];
        $book->id_author = $input['id_author'];
        $book->id_category = $input['id_category'];
        $book->image = $request->file('image')->store('/public/images');
        $book->save();
        if ($request->id_tag) {
            $book->tags()->attach($request->id_tag);
        }
        if ($request->tagname) {
            if (Tag::where('name', $request->tagname)->first()) {
                return $this->sendMessage('This tag already exits, please enter another name !');
            }
            $tag = new Tag;
            $tag->name = $request->tagname;
            $tag->slug = str_slug($tag->name);
            $tag->save();
            $tag->books()->attach($book->id);
        }
        if (count(Book::where('slug', $book->slug)->get()) > 1) {
            $oldSlug = $book->slug;
            $book->slug = $oldSlug . '.' . $book->id;
            $book->save();
        }

        $history = new History;
        $history->status = 'input';
        $history->quantity = $request->quantity;
        $history->id_book = $book->id;
        $history->id_user = $request->user()->id;
        $history->save();

        $storage = new Storage;
        $storage->quantity = $request->quantity;
        $storage->id_book = $book->id;
        $storage->save();
        return $this->sendMessage('Book ' . $book->name . ' created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::whereIn('highlights', [0, 1])->find($id);
        if (is_null($book)) {
            return $this->sendErrorNotFound('Book not found.');
        }
        return $this->sendData($book);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $book = Book::whereIn('highlights', [0, 1])->find($id);
        if (is_null($book)) {
            return $this->sendErrorNotFound('Book not found.');
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:books,name,' . $book->id,
            'price' => 'required',
            'highlights' => 'required',
            'description' => 'required',
            'id_author' => 'required',
            'id_category' => 'required',
        ], [
            'name.required' => 'Please enter book name',
            'name.unique' => 'Book already exits, please enter another name !',
            'price.required' => 'Please enter price',
            'highlights.required' => 'Please choose type highlights',
            'description.required' => 'Please enter book description',
            'id_author.required' => 'Please choose author',
            'id_category.required' => 'Please choose category',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }

        if ($request->hasFile('image')) {
            $book->image = $request->file('image')->store('/public/images');
        }
        $book->name = $input['name'];
        $book->slug = str_slug($input['name']);
        $book->price = $input['price'];
        $book->highlights = $input['highlights'];
        $book->description = $input['description'];
        $book->id_author = $input['id_author'];
        $book->id_category = $input['id_category'];
        $book->save();
        if ($request->id_tag) {
            $book->tags()->attach($request->id_tag);
        }
        if ($request->tagname) {
            if (Tag::where('name', $request->tagname)->first()) {
                return $this->sendMessage('This tag already exits, please enter another name !');
            }
            $tag = new Tag;
            $tag->name = $request->tagname;
            $tag->slug = str_slug($tag->name);
            $tag->save();
            $tag->books()->attach($book->id);
        }
        return $this->sendMessage('Book ' . $book->name . ' updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::whereIn('highlights', [0, 1])->find($id);
        if (is_null($book)) {
            return $this->sendErrorNotFound('Book not found.');
        }
        if ($book->highlights == 2) {
            return $this->sendErrorNotFound('Book not found.');
        }
        $book->highlights = 2;
        $book->save();
        return $this->sendMessage('Book ' . $id . ' hidden successfully');
    }
}
