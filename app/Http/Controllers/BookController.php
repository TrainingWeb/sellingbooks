<?php

namespace App\Http\Controllers;

use App\Book;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use Illuminate\Http\Request;
use Validator;
use App\Tag;
use App\History;
use App\Storage;

class BookController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $books = Book::paginate(15);
        if (count($books) < 1) {
            return $this->sendMessage('Found 0 book');
        }
        return $this->sendData($books->toArray());
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
            'name' => 'required|unique:books',
            'image' => 'required',
            'price' => 'required',
            'highlights' => 'required',
            'description' => 'required',
            'id_author' => 'required',
            'id_category' => 'required',
        ], [
            'name.required' => 'Please enter book name',
            'name.unique' => 'Book already exits, please enter another name !',
            'image.required' => 'Please enter book image',
            'price.required' => 'Please enter price',
            'highlights.required' => 'Please choose type highlights',
            'description.required' => 'Please enter book description',
            'id_author.required' => 'Please choose author',
            'id_category.required' => 'Please choose category',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $book = new Book;
        $book->name = $input['name'];
        $book->slug = str_slug($request->name);
        $book->price = $input['price'];
        $book->highlights = $input['highlights'];
        $book->description = $input['description'];
        $book->id_author = $input['id_author'];
        $book->id_category = $input['id_category'];
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file->move('./images', $file->getClientOriginalName('image'));
            $image = $file->getClientOriginalName('image');
            $book->image = $image;
        }
        $book->save();

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
        return $this->sendResponse(['book'=>$book, 'history'=>$history, 'storage'=>$storage], 'Book created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $book = Book::find($id);
        if (is_null($book)) {
            return $this->sendError('Book not found.');
        }
        return $this->sendData($book->toArray());
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
        $book = Book::find($id);
        if (is_null($book)) {
            return $this->sendError('Book not found.');
        }
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required|unique:books,name,'. $book->id,
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
            return $this->sendError('Validation Error.', $validator->errors());
        }

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $file->move('./images', $file->getClientOriginalName('image'));
            $image = $file->getClientOriginalName('image');
            $book->image = $image;
        }
        $book->name = $input['name'];
        $book->slug = str_slug($input['name']);
        $book->price = $input['price'];
        $book->highlights = $input['highlights'];
        $book->description = $input['description'];
        $book->id_author = $input['id_author'];
        $book->id_category = $input['id_category'];
        $book->save();
        return $this->sendResponse($book->toArray(), 'Book updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Book  $book
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $book = Book::find($id);
        if (is_null($book)) {
            return $this->sendError('Book not found.');
        }
        $book->delete();
        return $this->sendResponse($id, 'Book deleted successfully');
    }

    public function addBookQuantity(Request $request, $id)
    {
        $storage = Storage::where('id_book', $id)->first();
        if(is_null($storage)){
            return $this->sendError('Book not found !');
        }
        $oldquantity = $storage->quantity;
        $storage->quantity = $oldquantity + $request->quantity;
        $storage->id_book = $id;
        $storage->save();

        $history = new History;
        $history->status = 'input';
        $history->quantity = $request->quantity;
        $history->id_book = $id;
        $history->id_user = $request->user()->id;
        $history->save();
        return $this->sendResponse(['storage'=>$storage, 'history'=>$history], 'Just updated quantity of the book have id '.$id);
    }

}
