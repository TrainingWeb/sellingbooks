<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Category;
use App\Comment;
use App\Group;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\Mail\SendMailResetPassword;
use App\Order;
use App\OrderDetail;
use App\ResetPassword;
use App\Storage;
use App\Tag;
use App\User;
use Carbon\Carbon;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Mail;
use Validator;

class PageController extends APIBaseController
{
    public function login(Request $request)
    {
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $api_token = Auth::user()->createToken('test')->accessToken;
            $user = Auth::user();
            return response()->json(['api_token' => $api_token, 'user' => $user]);
        } else {
            return $this->sendMessage('Email or password is not correct !');
        }
    }

    public function createUser(Request $request)
    {
        $validator = $this->ValidationCreateUserFromIndex($request->all());
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $user = $this->CreateUserFromIndex($request->all());
        Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        $api_token = Auth::user()->createToken('test')->accessToken;
        return response()->json([
            'api_token' => $api_token,
            'user' => $user,
            'role' => $user->role,
            'message' => 'User created successfully !',
        ]);
    }

    public function index(Request $request)
    {
        // for menu
        $menucategories = Group::with('categories')->get();
        $menuauthors = Author::take(15)->get();
        // for menu
        $tagtrendings = Tag::withCount('books')->orderBy('books_count', 'DECS')->take(20)->get();
        $featuredbooks = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->where('highlights', 1)->take(6)->get();
        $discountbooks = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->take(4)->orderBy('promotion_price', 'DESC')->get();
        $nowymd = Carbon::tomorrow();
        $ago7days = Carbon::tomorrow()->subDays(7);
        $newbooks = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->whereBetween('created_at', [$ago7days, $nowymd])->take(6)->get();
        return $this->sendData([
            'tagtrendings' => $tagtrendings,
            'featuredbooks' => $featuredbooks,
            // 'countbooksfavorite' => $countbooksfavorite,
            'discountbooks' => $discountbooks,
            'newbooks' => $newbooks,
            'menucategories' => $menucategories,
            'menuauthors' => $menuauthors,
        ]);
    }

    public function tagInfo($slug)
    {
        $tag = Tag::where('slug', $slug)->first();
        if (!$tag) {
            return $this->sendErrorNotFound('Tag not found !');
        }
        return $this->sortt($tag->books(), $tag);
    }

    public function search()
    {
        $items01 = array();
        $items02 = array();
        $items03 = array();
        $authorbooks = Book::whereIn('highlights', [0, 1])->whereHas('author', function ($query) {
            $query->where('name', 'LIKE', '%' . request()->name . '%');
        })->get();
        foreach ($authorbooks as $items1) {
            $items01[] = $items1->id;
        }
        $catebooks = Book::whereIn('highlights', [0, 1])->whereHas('category', function ($query) {
            $query->where('name', 'LIKE', '%' . request()->name . '%');
        })->get();
        foreach ($catebooks as $items2) {
            $items02[] = $items2->id;
        }
        $books = Book::whereIn('highlights', [0, 1])->where([
            ['name', 'LIKE', '%' . request()->name . '%'],
        ])->get();
        foreach ($books as $items3) {
            $items03[] = $items3->id;
        }
        $done = $items01 + $items02 + $items03;
        if (count($done) < 1) {
            return $this->sendMessage('Found 0 books for this keywork !');
        }
        return $this->sort(Book::whereIn('id', $done));
    }

    public function typeBooks($type)
    {
        try {
            switch ($type) {
                case 'discountbooks':
                    return $this->sort(Book::where('promotion_price', '>', '0'));
                    break;
                case 'featuredbooks':
                    return $this->sort(Book::where('highlights', 1));
                    break;
                case 'newbooks':
                    $nowymd = Carbon::tomorrow();
                    $ago7days = Carbon::tomorrow()->subDays(7);
                    return $this->sort(Book::whereBetween('created_at', [$ago7days, $nowymd]));
                    break;
            }
            return response()->json($books);
        } catch (Exception $e) {
            return $this->sendErrorNotFound('Không tìm thấy kiểu sách');
        }
    }

    public function getBookInfo($slug)
    {
        $book = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->with('tags')->with('comments')->where('slug', $slug)->first();
        if (!$book) {
            return $this->sendErrorNotFound('Book not found !');
        }
        $comments = Comment::with('user')->where('id_book', $book->id)->orderBy('created_at', 'DESC')->paginate(5);
        $samebooks = Book::where('id_category', $book->id_category)->whereIn('highlights', [0, 1])->whereNotIn('id', [$book->id])->with('storage')->with('author')->orderBy('created_at', 'DESC')->take(3)->get();
        return response()->json(['book' => $book, 'comments' => $comments, 'samebooks' => $samebooks], 200);
    }

    public function getMoreComments($slug)
    {
        $book = Book::whereIn('highlights', [0, 1])->where('slug', $slug)->first();
        if (!$book) {
            return $this->sendErrorNotFound('book not found !');
        }
        foreach ($book->comments as $items) {
            $id[] = $items->id;
        }
        $comments = Comment::whereIn('id', $id)->with('user')->orderBy('created_at', 'DESC')->paginate(5);
        if (count($comments) < 1) {
            return $this->sendMessage('Found 0 comments !');
        }
        return response()->json($comments, 200);

    }

    public function seeMoreSameBooks($slug)
    {
        $book = Book::where('slug', $slug)->first();
        return $this->sort(Book::where('id_category', $book->id_category));
    }

    public function getAuthors()
    {
        $authors = Author::withCount('books')->paginate(18);
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
        return $this->sortt(Book::where('id_author', $author->id), $author);
    }

    public function getCategoies()
    {
        $categories = Category::withCount('books')->paginate(18);
        if (is_null($categories)) {
            return $this->sendMessage('Found 0 categories');
        }
        return $this->sendData($categories);
    }

    public function getInfoCategory($slug)
    {
        $category = Category::where('slug', $slug)->first();
        if (!$category) {
            return $this->sendErrorNotFound('Category not found !');
        }
        return $this->sortt(Book::where('id_category', $category->id), $category);
    }

    public function postComment(Request $request, $slug)
    {
        if (!$request->user()) {
            return $this->sendErrorAuth('You are must login before to do this !');
        }
        $book = Book::where('slug', $slug)->first();
        if (!$book) {
            return $this->sendErrorNotFound('Book not found !');
        }
        $comment = new Comment;
        $comment->id_user = $request->user()->id;
        $comment->id_book = $book->id;
        $comment->content = $request->content;
        $comment->save();
        return $this->sendResponse($comment, 'Add comment successfully !');
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

    public function editComment(Request $request, $id)
    {
        $comment = Comment::find($id);
        if (!$comment) {
            return $this->sendErrorNotFound('Comment not found !');
        }
        if ($request->user()->id == $comment->id_user) {
            $comment->content = $request->content;
            $comment->save();
            return $this->sendMessage('Edited successfully !');
        } else {
            return $this->sendErrorPermission('You have no permission to do this !');
        }
    }

    public function showOrderUser(Request $request)
    {
        $user = User::with('orders')->find($request->user()->id);
        if (count($user->orders) < 1) {
            return $this->sendMessage('Have no orders !');
        } else {
            foreach ($user->orders as $items) {
                $item[] = $items->id;
            }
            $orders = Order::whereIn('id', $item)->paginate(10);
            return response()->json($orders);
        }
    }

    public function deleteOrderUser(Request $request, $id)
    {
        $order = Order::find($id);
        if (is_null($order)) {
            return $this->sendErrorNotFound('Order not found !');
        }
        switch ($order->status) {
            case 'waiting':
                if ($request->user()->id == $order->id_user) {
                    $order->status = 'cancel';
                    $order->save();
                    return $this->sendMessage('Just cancel your order !');
                } else {
                    return $this->sendErrorPermisstion('Cannot delete order of another user !');
                }
            case 'cancel':
                return $this->sendMessage('This order has been cancel before !');
                break;
            case 'accept':
                return $this->sendMessage('Your order has been approved, you cannot cancel this order ! Give us a call if you really want to cancel this order.');
                break;
            case 'sold':
                return $this->sendMessage('Your order has been done ! Let us keep that.');
                break;
        }
    }

    public function postFavorite(Request $request, $id)
    {
        if (!Book::whereIn('highlights', [0, 1])->find($id)) {
            return $this->sendErrorNotFound('Book not found !');
        }
        $user = User::whereIn('role', [0, 1])->find($request->user()->id);
        $results = $user->books()->get();
        foreach ($results as $result) {
            $items[] = $result->id;
            foreach ($items as $item) {
                if ($item == $id) {
                    return $this->sendMessage('This book has been your favorite, can not add this book one more time !');
                }
            }
        }
        $user->books()->attach($id);
        return $this->sendMessage('Add favorite successfully !');
    }

    public function removeFavorite(Request $request, $id)
    {
        $user = User::with('books')->find($request->user()->id);
        foreach ($user->books as $results) {
            if ($id == $results->id) {
                $user->books()->detach($id);
                return $this->sendMessage('Unlike this book successfully!');
            }
        }
        return $this->sendErrorNotFound('Cannot found this book in your favorite list !');
    }

    public function getFavoriteBook(Request $request)
    {
        $user = User::find($request->user()->id);
        return $this->sort($user->books());
    }

    public function checkInfo(Request $request)
    {
        $validator = $this->ValidationCheckInfo($request->all());
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $user = $this->UpdateDataUserCheckInfo($request->all(), $request->user()->id);
        return $this->sendMessage('Update user successfully !');
    }

    public function showInfoUser(Request $request)
    {
        $user = User::find($request->user()->id);
        if (is_null($user)) {
            return $this->sendErrorNotFound('User not found !');
        }
        switch ($user->role) {
            case 1:
                $role = 1;
                break;
            case 0:
                $role = 0;
                break;
            default:
                $role = null;
                break;
        }
        return response()->json(['user' => $user, 'role' => $role]);

    }

    public function updateUser(Request $request)
    {
        $validator = $this->ValidationUpdateDataInfoUser($request->all(), $request->user()->id);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar')->store('/public/images');
        } else {
            $avatar = User::find($request->user()->id)->avatar;
        }
        $user = $this->UpdateDataInfoUser($request->all(), $request->user()->id, $avatar);
        return $this->sendMessage('Successfully !');
    }

    public function checkout(Request $request)
    {
        $quantity = $request->quantity;
        $books = Book::whereIn('highlights', [0, 1])->whereIn('id', $request->id)->get();
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
            $item = Book::whereIn('highlights', [0, 1])->find($book->id);
            if ($item->promotion_price) {
                $order_detail->price = $item->promotion_price;
            } elseif (is_null($item->promotion_price)) {
                $order_detail->price = $item->price;
            }
            $order_detail->save();
        }

        return $this->sendMessage('Order successfully !');
    }

    public function sendMailResetPassword(Request $request)
    {
        if (!User::where('email', $request->email)->first()) {
            return $this->sendMessage('Have no account have this email, please check it again !');
        }
        Mail::to($request->email)->send(new SendMailResetPassword());
        return $this->sendMessage('Send reset password successfully !');
    }

    public function resetPassword(Request $request, $token)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
            'confirm_password' => 'required',
        ], [
            'email.required' => 'Email can not be null',
            'password.required' => 'Password can not be null',
            'confirm_password.required' => 'Please confirm password !',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        if ($request->password !== $request->confirm_password) {
            return $this->sendMessage('Password confirm must in the same !');
        }
        if (!User::where('email', $request->email)->first()) {
            return $this->sendMessage('Have no account have this email, please check it again !');
        }
        $nowtime = Carbon::now();
        $sub5m = Carbon::now()->subMinutes(5);
        $resetpassword = ResetPassword::where('token', $token)->whereBetween('created_at', [$sub5m, $nowtime])->first();
        if (!$resetpassword) {
            return $this->sendMessage('Your token has expired, please send another request reset mail !');
        }
        if ($request->email !== $resetpassword->email) {
            return $this->sendMessage('This email have no request reset password !');
        } else {
            $resetpassword->delete();
        }
        $user = User::where('email', $request->email)->first();
        $user->update([
            'password' => Hash::make($request->password),
        ]);
        return $this->sendMessage('Your account has been reset password !');
    }

    public function testmail()
    {
        $token = request()->token;
        $email = request()->email;
        return view('testmail', compact('token', 'email'));
    }
}
