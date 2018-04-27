<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Category;
use App\History;
use App\Order;
use Carbon\Carbon;
use App\OrderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\API\APIBaseController as APIBaseController;

class PageController extends APIBaseController
{
    public function GetNewBook(Request $request)
    {
        $books = Book::whereBetween('created_at', [$request->startday, $request->finishday])->paginate(15);
        if (count($books) < 1) {
            return $this->sendMessage('Found 0 new book.');
        }
        return $this->sendData($books->toArray());
    }

    public function index()
    {
        $sachnoibac = Book::where('highlights', 1)->take(6)->get();
        if (count($sachnoibac) < 1) {
            return $this->sendMessage('Found 0 highlights book.');
        }
        $sachgiamgia = Book::take(4)->orderBy('promotion_price', 'DESC')->get();
        if (count($sachgiamgia) < 1) {
            return $this->sendMessage('Found 0 highlights book.');
        }
        $nowymd = Carbon::tomorrow();
        $ago7days = Carbon::tomorrow()->subDays(7);
        $sachcach7ngay = Book::whereBetween('created_at', [$ago7days, $nowymd])->take(6)->get();
        if (count($sachcach7ngay) < 1) {
            return $this->sendMessage('Found 0 highlights book.');
        }
        return $this->sendData(['sachnoibac'=>$sachnoibac, 'sachgiamgia'=>$sachgiamgia, 'sachcach7ngay'=>$sachcach7ngay]);
    }
    
    public function getAuthors()
    {
        $authors = Author::paginate(15);
        if(is_null($authors)){
            return $this->sendError('Found 0 authors !');
        }
        return $this->sendData($authors);
    }

    public function getInfoAuthor($slug)
    {
        $author = Author::where('slug', $slug)->first();
        if(is_null($author)){
            return $this->sendError('Author not found !');
        }
        $books = Book::where('id_author', $author->id)->paginate(10);
        return $this->sendData(['author'=>$author, 'books'=>$books]);
    }

    public function getCategoies()
    {
        $categories = Category::paginate(20);
        if(is_null($categories)){
            return $this->sendError('Found 0 categories');
        }
        return $this->sendData($categories);
    }
    
    public function getInfoCategory($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if(is_null($category)){
            return $this->sendError('Category not found !');
        }
        $books = Book::where('id_category', $category->id)->paginate(10);
        return $this->sendData(['category'=>$category, 'books'=>$books]);
    }
    public function checkout(Request $request)
    {
        $order = new Order;
        $order->total = $request->total;
        $order->status = null;
        $order->id_user = Auth::guard('api')->id();
        $order->save();

        foreach ($items as $key => $value) {
            $history = new History;
            $history->status = 'out';
            $history->quantity = $item->quantity;
            $history->id_book = $key;
            $history->save();
        }

        foreach ($items as $key => $value) {
            $order_detail = new OrderDetail;
            $order_detail->id_order = $order->id;
            $order_detail->id_book = $key;
            $order_detail->quantity = $value['qty'];
            $order_detail->price = ($value['price'] / $value['qty']);

            $order_detail->save();
        }

        return $this->sendMessage('Order successfully !');
    }
}
