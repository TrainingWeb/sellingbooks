<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Category;
use App\Comment;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\Order;
use App\OrderDetail;
use App\Storage;
use App\Tag;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;

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
        $tagtrendings = Tag::withCount('books')->orderBy('books_count', 'DECS')->take(20)->get();
        $featuredbooks = Book::with('storage')->where('highlights', 1)->take(6)->get();
        $discountbooks = Book::with('storage')->take(4)->orderBy('promotion_price', 'DESC')->get();
        $nowymd = Carbon::tomorrow();
        $ago7days = Carbon::tomorrow()->subDays(7);
        $newbooks = Book::with('storage')->whereBetween('created_at', [$ago7days, $nowymd])->take(6)->get();
        return $this->sendData([
            'tagtrendings' => $tagtrendings,
            'featuredbooks' => $featuredbooks,
            'discountbooks' => $discountbooks,
            'newbooks' => $newbooks,
            'tieuthuyet' => $tieuthuyet,
            'kinhdoanh' => $kinhdoanh,
            'lichsu' => $lichsu,
            'menuauthors' => $menuauthors,
        ]);
    }

    public function getBookwithTag($slug)
    {
        $books = null;
        $results = Tag::with('books')->where('slug', $slug)->first();
        if (is_null($results)) {
            return $this->sendErrorNotFound('Tag not found !');
        }
        $tag = Tag::where('slug', $slug)->first();
        if (count($results->books) > 0) {
            foreach ($results->books as $item) {
                $books[] = ($item)->paginate(18);
            }
        }
        return $this->sendData(['tag' => $tag, 'books' => $books]);
    }

    public function search()
    {
        $books = Book::where([
            ['name', 'LIKE', '%' . request()->name . '%'],
        ])->take(12)->get();
        $authors = Author::where([
            ['name', 'LIKE', '%' . request()->name . '%'],
        ])->paginate(6);
        $categories = Category::where([
            ['name', 'LIKE', '%' . request()->name . '%'],
        ])->paginate(6);
        return $this->sendData(['books' => $books, 'authors' => $authors, 'categories' => $categories]);
    }

    public function seemorefromSearch($name)
    {
        $featuredauthors = null;
        $books = Book::where([
            ['name', 'LIKE', '%' . $name . '%'],
        ])->with('tags')->paginate(18);
        foreach ($books as $book) {
            foreach ($book->tags as $item) {
                $tags[] = $item;
            }
        }
        $results = Book::with('author')->where('highlights', 1)->where([
            ['name', 'LIKE', '%' . $name . '%'],
        ])->get();
        foreach ($results as $item) {
            $featuredauthors[] = ($item->author)->take(6)->get();
        }
        return $this->sendData(['books' => $books, 'featuredauthors' => $featuredauthors, 'tags' => $tags]);
    }

    public function featuredBooks()
    {
        $featuredbooks = Book::where('highlights', 1)->paginate(18);
        if (count($featuredbooks) < 1) {
            return $this->sendMessage('Found 0 feature books.');
        }
        return $this->sendData($featuredbooks);
    }

    public function bestSellers()
    {
        $books = Book::withCount('orderdetails')->orderBy('orderdetails_count', 'DECS')->take(5)->get();
        if (count($books) < 1) {
            return $this->sendMessage('Found 0 books.');
        }
        return $this->sendData($books);
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
        $book = Book::with('comments')->where('slug', $slug)->first();
        if (is_null($book)) {
            return $this->sendErrorNotFound('Book not found !');
        }
        $samebooks = Book::where('id_category', $book->id_category)->orderBy('created_at', 'DESC')->take(4)->get();
        return $this->sendData(['book' => $book, 'samebooks' => $samebooks]);
    }

    public function getAuthors()
    {
        $authors = Author::paginate(15);
        if (is_null($authors)) {
            return $this->sendMessage('Found 0 authors !');
        }
        return $this->sendData($authors);
    }

    public function getInfoAuthor($slug)
    {
        $author = Author::where('slug', $slug)->first();
        if (is_null($author)) {
            return $this->sendErrorNotFound('Author not found !');
        }
        $books = Book::where('id_author', $author->id)->with('tags')->paginate(9);
        foreach ($books as $book) {
            foreach ($book->tags as $item) {
                $tags[] = $item;
            }
        }
        return $this->sendData(['author' => $author, 'books' => $books, 'tags' => $tags]);
    }

    public function getCategoies()
    {
        $categories = Category::paginate(20);
        if (is_null($categories)) {
            return $this->sendMessage('Found 0 categories');
        }
        return $this->sendData($categories);
    }

    public function getInfoCategory($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (is_null($category)) {
            return $this->sendErrorNotFound('Category not found !');
        }
        $books = Book::where('id_category', $category->id)->with('tags')->paginate(9);
        foreach ($books as $book) {
            foreach ($book->tags as $item) {
                $tags[] = $item;
            }
        }
        return $this->sendData(['category' => $category, 'books' => $books, 'tags' => $tags]);
    }

    public function postComment(Request $request, $id)
    {
        $comment = new Comment;
        $comment->id_user = $request->user()->id;
        $comment->id_book = $id;
        $comment->content = $request->content;
        $comment->save();
        return $this->sendMessage('Add comment successfully !');
    }

    public function deleteComment(Request $request, $id)
    {
        if (!$request->user()) {
            return $this->sendErrorAuth('You are must login before to do this !');
        }
        $comment = Comment::find($id);
        if (is_null($comment)) {
            return $this->sendErrorNotFound('Comment not found !');
        }
        $user = User::find($request->user()->id);
        if ($comment->id_user == $user->id) {
            $comment->delete();
            return $this->sendMessage('Deleted comment successfully !');
        } elseif ($comment->id_user !== $user->id) {
            return $this->sendErrorPermission('You are have no permisstion to do this !');
        }
    }

    public function postFavorite(Request $request, $id)
    {
        if (!Book::find($id)) {
            return $this->sendErrorNotFound('Book not found !');
        }
        $user = User::find($request->user()->id);
        $results = $user->books()->get();
        foreach ($results as $result) {
            $items[] = $result->id;
            foreach ($items as $item) {
                if ($item == $id) {
                    return $this->sendMessage('This book has been favorite, can not add this book one more time !');
                }
            }
        }
        $user->books()->attach($id);
        return $this->sendMessage('Add favorite successfully !');
    }

    public function getFavoriteBook(Request $request)
    {
        $user = User::find($request->user()->id);
        $books = $user->books()->with('tags')->paginate(18);
        if (count($books) < 1) {
            return $this->sendMessage('Found 0 books !');
        }
        foreach ($books as $book) {
            foreach ($book->tags as $item) {
                $tags[] = $item;
            }
        }
        return $this->sendData(['user' => $user, 'books' => $books, 'tags' => $tags]);
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
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $feedback = new Feedback;
        $feedback->name = $request->user()->name;
        $feedback->content1 = $input['content1'];
        $feedback->content2 = $input['content2'];
        $feedback->save();
        return $this->sendMessage('Feedback successfully !');
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
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $user = User::find($request->user()->id);
        $user->phone = $input['phone'];
        $user->address = $input['address'];
        $user->save();
        return $this->sendMessage('Update user successfully !');
    }

    public function checkout(Request $request)
    {
        $quantity = $request->quantity;
        $books = Book::whereIn('id', $request->id)->get();
        $idbook = array();
        foreach ($books as $key => $book) {
            $storage = Storage::where('id_book', $book->id)->first();
            if ($quantity[$key] > $storage->quantity) {
                $idbook[] = $storage->id_book;
            }
        }
        if ($idbook) {
            return $this->sendResponse($idbook, 'Have a book not enought to sells, please check quantity before sells !');
        }

        foreach ($books as $key => $book) {
            if ($book->promotion_price) {
                $price = $book->promotion_price;
            } elseif (is_null($book->promotion_price)) {
                $price = $book->price;
            }
            $subtotal[] = $price * $quantity[$key];
        }
        $order = new Order;
        $order->total = array_sum($subtotal);
        $order->status = 'waiting';
        $order->id_user = $request->user()->id;
        $order->save();

        foreach ($books as $key => $book) {
            $order_detail = new OrderDetail;
            $order_detail->id_order = $order->id;
            $order_detail->id_book = $book->id;
            $order_detail->quantity = $quantity[$key];
            $item = Book::find($book->id);
            if ($item->promotion_price) {
                $order_detail->price = $item->promotion_price;
            } elseif (is_null($item->promotion_price)) {
                $order_detail->price = $item->price;
            }
            $order_detail->save();
        }

        return $this->sendMessage('Order successfully !');
    }
}
