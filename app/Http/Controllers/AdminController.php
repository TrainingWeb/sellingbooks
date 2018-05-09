<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Category;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\Order;
use App\Storage;
use App\Tag;
use App\User;
use Illuminate\Http\Request;
use App\History;

class AdminController extends APIBaseController
{
    public function index()
    {
        $storage = Storage::sum('quantity');
        $countbook = Category::withCount('books')->paginate(10);
        $bestsellers = Book::whereIn('highlights', [0, 1])->withCount('orderdetails')->orderBy('orderdetails_count', 'DECS')->take(5)->get();
        $countorders = count(Order::get());
        $countwaitingorder = count(Order::where('status', 'waiting')->get());
        $countusers = count(User::get());
        $countcategories = count(Category::get());
        $counttags = count(Tag::get());
        $countauthors = count(Author::get());
        return $this->sendData([
            'totalbooks' => $storage,
            'countbooksincategory' => $countbook,
            'bestsellers' => $bestsellers,
            'countorders' => $countorders,
            'countwaitingorder' => $countwaitingorder,
            'countusers' => $countusers,
            'countcategories' => $countcategories,
            'counttags' => $counttags,
            'countauthors' => $countauthors,
        ]);

    }

    public function bandUser(Request $request, $id)
    {
        $user = User::find($id);
        if ($user->id == $request->user()->id) {
            return $this->sendMessage('You are can not band yourself !');
        } elseif ($user->role == 1) {
            return $this->sendMessage('You are can not band another administrator !');
        }
        if (is_null($user)) {
            return $this->sendErrorNotFound('User not found !');
        }
        if ($user->role == 2) {
            return $this->sendMessage('User ' . $user->name . ' got banned before !');
        }
        $user->role = 2;
        $user->save();
        return $this->sendMessage('User ' . $user->name . ' just banned successfully !');
    }

    public function changUserRole(Request $request, $id)
    {
        $user = User::find($id);
        if (is_null($user)) {
            return $this->sendErrorNotFound('User not found !');
        }
        if($user->role == 1){
            return $this->sendMessage('Cannot change role this user !');
        }
        $user->role = $request->role;
        $user->save();
        return $this->sendMessage('Changed role of user ' . $user->name . ' successfully !');
    }

    public function addBookQuantity(Request $request, $id)
    {
        $storage = Storage::where('id_book', $id)->first();
        if (is_null($storage)) {
            return $this->sendErrorNotFound('Book not found !');
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
        return $this->sendMessage('Just updated ' . $request->quantity . ' quantity of the book have id ' . $id . '');
    }

    public function getHiddenBook()
    {
        $books = Book::where('highlights', 2)->paginate(18);
        if (count($books) < 1) {
            return $this->sendMessage('Found 0 books !');
        }
        return $this->sendData($books);
    }

    public function Total(Request $request)
    {
        if ($request->startday && $request->finishday && $request->month && $request->year) {
            return $this->sendMessage('Please check it with day to day, month/year or just year !');
        }
        if ($request->startday && $request->finishday) {
            $total = Order::whereBetween('created_at', [$request->startday, $request->finishday])->sum('total');
            return $this->sendResponse($total, 'Total in ' . $request->startday . ' to ' . $request->finishday);
        } elseif ($request->month && $request->year) {
            $total = Order::whereMonth('created_at', $request->month)->whereYear('created_at', $request->year)->sum('total');
            return $this->sendResponse($total, 'Total in ' . $request->month . '/' . $request->year);
        } elseif ($request->year) {
            $total = Order::whereYear('created_at', $request->year)->sum('total');
            return $this->sendResponse($total, 'Total in ' . $request->year);
        }
        $total = Order::sum('total');
        return $this->sendResponse($total, 'Total revenue');
    }

    public function editStorage(Request $request, $id)
    {
        $storage = Storage::find($id);
        if(!$storage){
            return $this->sendErrorNotFound('Storage not found !');
        }
        $oldQuantity = $storage->quantity;
        $storage->quantity = $oldQuantity - $request->quantity;
        $storage->save();

        $history = new History;
        $history->status = 'errorquantityinput';
        $history->quantity = $request->quantity;
        $history->id_book = $id;
        $history->id_user = $request->user()->id;
        $history->save();
        return $this->sendMessage('Updated storage successfully !');
    }

}
