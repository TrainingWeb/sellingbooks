<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Category;
use App\Comment;
use App\History;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\Order;
use App\OrderDetail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageController extends APIBaseController
{
    public function index(Request $request)
    {
        // for menu
        $tieuthuyet = Category::where('id_group', 1)->take(5)->get();
        $kinhdoanh = Category::where('id_group', 2)->take(5)->get();
        $lichsu = Category::where('id_group', 3)->take(5)->get();
        $menuauthors = Author::take(15)->get();
        // end
        $featuredbooks = Book::where('highlights', 1)->take(6)->get();
        if (count($featuredbooks) < 1) {
            return $this->sendMessage('Found 0 highlights book.');
        }
        $discountbooks = Book::take(4)->orderBy('promotion_price', 'DESC')->get();
        if (count($discountbooks) < 1) {
            return $this->sendMessage('Found 0 highlights book.');
        }
        $nowymd = Carbon::tomorrow();
        $ago7days = Carbon::tomorrow()->subDays(7);
        $newbooks = Book::whereBetween('created_at', [$ago7days, $nowymd])->take(6)->get();
        if (count($newbooks) < 1) {
            return $this->sendMessage('Found 0 highlights book.');
        }
        return $this->sendData([
            'featuredbooks' => $featuredbooks,
            'discountbooks' => $discountbooks,
            'newbooks' => $newbooks,
            'tieuthuyet' => $tieuthuyet,
            'kinhdoanh' => $kinhdoanh,
            'lichsu' => $lichsu,
            'menuauthors' => $menuauthors,
        ]);
    }

    public function search()
    {
        $books = null;
        $categorybooks = null;
        $authorbooks = null;
        $books = Book::where([
            ['name', 'LIKE', '%' . request()->name . '%'],
        ])->paginate(18);
        $categories = Category::where([
            ['name', 'LIKE', '%' . request()->name . '%'],
        ])->take(4)->get();
        foreach ($categories as $category) {
            $categorybooks = Book::where('id_category', $category->id)->paginate(18);
        }
        $authors = Author::where([
            ['name', 'LIKE', '%' . request()->name . '%'],
        ])->take(4)->get();
        foreach ($authors as $author) {
            $authorbooks = Book::where('id_author', $author->id)->paginate(18);
        }
        return $this->sendData(['books'=>$books, 'categorybooks'=> $categorybooks, 'authorbooks'=>$authorbooks]);
    }

    public function featuredBooks()
    {
        $featuredbooks = Book::where('highlights', 1)->paginate(18);
        if (count($featuredbooks) < 1) {
            return $this->sendMessage('Found 0 feature books.');
        }
        return $this->sendData($featuredbooks);
    }

    public function discountBooks()
    {
        $discountbooks = Book::orderBy('promotion_price', 'DESC')->paginate(18);
        if (count($discountbooks) < 1) {
            return $this->sendMessage('Found 0 discount books.');
        }
        return $this->sendData($discountbooks);
    }

    public function newBooks()
    {
        $nowymd = Carbon::tomorrow();
        $ago7days = Carbon::tomorrow()->subDays(7);
        $newbooks = Book::whereBetween('created_at', [$ago7days, $nowymd])->take(6)->get();
        if (count($newbooks) < 1) {
            return $this->sendMessage('Found 0 new books.');
        }
        return $this->sendData($newbooks);
    }

    public function getBookInfo($slug)
    {
        $book = Book::where('slug', $slug)->first();
        if (is_null($book)) {
            return $this->sendError('Book not found !');
        }
        $samebooks = Book::where('id_category', $book->id_category)->orderBy('created_at', 'DESC')->take(4)->get();
        return $this->sendData(['book' => $book, 'samebooks' => $samebooks]);
    }

    public function getAuthors()
    {
        $authors = Author::paginate(15);
        if (is_null($authors)) {
            return $this->sendError('Found 0 authors !');
        }
        return $this->sendData($authors);
    }

    public function getInfoAuthor($slug)
    {
        $author = Author::where('slug', $slug)->first();
        if (is_null($author)) {
            return $this->sendError('Author not found !');
        }
        $books = Book::where('id_author', $author->id)->paginate(10);
        return $this->sendData(['author' => $author, 'books' => $books]);
    }

    public function getCategoies()
    {
        $categories = Category::paginate(20);
        if (is_null($categories)) {
            return $this->sendError('Found 0 categories');
        }
        return $this->sendData($categories);
    }

    public function getInfoCategory($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (is_null($category)) {
            return $this->sendError('Category not found !');
        }
        $books = Book::where('id_category', $category->id)->paginate(10);
        return $this->sendData(['category' => $category, 'books' => $books]);
    }

    public function postComment(Request $request, $id)
    {
        $comment = new Comment;
        $comment->id_user = $request->user()->id;
        $comment->id_book = $id;
        $comment->content = $request->content;
        $comment->save();
        return $this->sendResponse($comment, 'Add comment successfully !');
    }

    public function postFavorite(Request $request, $id)
    {
        $user = User::find($request->user()->id);
        $user->books()->attach($id);
        return $this->sendResponse($user, 'Add favorite successfully !');
    }

    public function getFavoriteBook(Request $request)
    {
        $user = User::find($request->user()->id);
        $books = $user->books()->get();
        return $this->sendData($books);
    }

    public function postFeedback(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'content1' => 'required',
            'content2' => 'required',
        ], [
            'name.required' => 'You must login to do this !',
            'content1.required' => 'Content 1 can not blank !',
            'content2.required' => 'Content 2 can not blank !',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $feedback = new Feedback;
        $feedback->name = $request->user()->name;
        $feedback->content1 = $input['content1'];
        $feedback->content2 = $input['content2'];
        $feedback->save();
        return $this->sendReponse($feedback, 'Thanks you for your feedback ! We will check it soon.');
    }

    public function checkInfo(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'phone' => 'required',
            'address' => 'required',
        ], [
            'phone.required' => 'Phone can not blank !',
            'address.required' => 'Address can not blank !',
        ]);
        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }
        $user = User::find($request->user()->id);
        $user->phone = $input['phone'];
        $user->address = $input['address'];
        $user->save();
        return $this->sendResponse($user, 'Update user successfully !');
    }

    public function checkout(Request $request)
    {
        $order = new Order;
        $order->total = $total;
        $order->status = null;
        $order->id_user = $request->user()->id;
        $order->save();

        foreach ($books as $book) {
            $history = new History;
            $history->status = 'out';
            $history->quantity = $book->quantity;
            $history->id_book = $book->id;
            $history->id_user = $request->user()->id;
            $history->save();
        }

        foreach ($books as $book) {
            $storage = Storage::where('id_book', $book->id)->first();
            $oldquantity = $storage->quantity;
            $storage->quantity = $oldquantity - $book->quantity;
            $storage->save();
        }

        foreach ($books as $book) {
            $order_detail = new OrderDetail;
            $order_detail->id_order = $order->id;
            $order_detail->id_book = $book->id;
            $order_detail->quantity = $book->quantity;
            $order_detail->price = $book->price;
            $order_detail->save();
        }

        return $this->sendMessage('Order successfully !');
    }
}
