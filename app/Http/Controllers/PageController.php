<?php

namespace App\Http\Controllers;

use App\Author;
use App\Book;
use App\Category;
use App\Comment;
use App\Group;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\Mail\ResetPassword;
use App\Order;
use App\OrderDetail;
use App\Storage;
use App\Tag;
use App\User;
use Carbon\Carbon;
use Exception;
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
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'required|unique:users',
            'password' => 'required',
        ], [
            'name.required' => 'Please enter name',
            'email.required' => 'Please enter email.',
            'email.unique' => 'Email alreay exits, please enter another email !',
            'password.required' => 'Password can not null.',
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        $user = new User;
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->role = 0;
        $user->password = bcrypt($input['password']);
        $user->save();
        Auth::attempt(['email' => $request->email, 'password' => $request->password]);
        $api_token = Auth::user()->createToken('test')->accessToken;
        return response()->json(['api_token' => $api_token, 'user' => $user, 'role' => $user->role, 'message' => 'User created successfully !']);
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
            'discountbooks' => $discountbooks,
            'newbooks' => $newbooks,
            'menucategories' => $menucategories,
            'menuauthors' => $menuauthors,
        ]);
    }

    public function tagInfo($slug)
    {
        $tag = Tag::with('books')->where('slug', $slug)->first();
        if (!$tag) {
            return $this->sendErrorNotFound('Tag not found !');
        }
        switch (request()->sort) {
            case 'atoz':
                $books = $tag->books()->with('author')->with('tags')->with('storage')->orderBy('name')->paginate(18);
                break;
            case 'atozdesc':
                $books = $tag->books()->with('author')->with('tags')->with('storage')->orderBy('name', 'DESC')->paginate(18);
                break;
            case 'price';
                $books = $tag->books()->with('author')->with('tags')->with('storage')->orderBy('price')->paginate(18);
                break;
            case 'pricedesc';
                $books = $tag->books()->with('author')->with('tags')->with('storage')->orderBy('price', 'DESC')->paginate(18);
                break;
            default:
                $books = $tag->books()->with('author')->with('tags')->with('storage')->paginate(18);
        }
        return response()->json($books);
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
        if (count($books) < 1) {
            return $this->sendMessage('Found 0 books for this keywork !');
        }
        switch (request()->sort) {
            case 'atoz':
                $books = Book::whereIn('id', $done)->with('author')->with('storage')->orderBy('name')->paginate(18);
                break;
            case 'atozdesc':
                $books = Book::whereIn('id', $done)->with('author')->with('storage')->orderBy('name', 'DESC')->paginate(18);
                break;
            case 'price';
                $books = Book::whereIn('id', $done)->with('author')->with('storage')->orderBy('price')->paginate(18);
                break;
            case 'pricedesc';
                $books = Book::whereIn('id', $done)->with('author')->with('storage')->orderBy('price', 'DESC')->paginate(18);
                break;
            default:
                $books = Book::whereIn('id', $done)->with('author')->with('storage')->paginate(18);
        }
        return response()->json($books);
    }

    public function typeBooks($type)
    {
        try {
            switch ($type) {
                case 'discountbooks':
                    switch (request()->sort) {
                        case 'atoz':
                            $books = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->with('tags')->orderBy('promotion_price', 'DESC')->orderBy('name')->paginate(18);
                            break;
                        case 'atozdesc':
                            $books = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->with('tags')->orderBy('promotion_price', 'DESC')->orderBy('name', 'DESC')->paginate(18);
                            break;
                        case 'price';
                            $books = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->with('tags')->orderBy('promotion_price', 'DESC')->orderBy('price')->paginate(18);
                            break;
                        case 'pricedesc';
                            $books = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->with('tags')->orderBy('promotion_price', 'DESC')->orderBy('price', 'DESC')->paginate(18);
                            break;
                        default:
                            $books = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->with('tags')->orderBy('promotion_price', 'DESC')->paginate(18);
                    }
                    if (count($books) < 1) {
                        return $this->sendMessage('Found 0 feature books.');
                    }
                    break;
                case 'featuredbooks':
                    switch (request()->sort) {
                        case 'atoz':
                            $books = Book::where('highlights', 1)->with('storage')->with('author')->with('tags')->orderBy('name')->paginate(18);
                            break;
                        case 'atozdesc':
                            $books = Book::where('highlights', 1)->with('storage')->with('author')->with('tags')->orderBy('name', 'DESC')->paginate(18);
                            break;
                        case 'price';
                            $books = Book::where('highlights', 1)->with('storage')->with('author')->with('tags')->orderBy('price')->paginate(18);
                            break;
                        case 'pricedesc';
                            $books = Book::where('highlights', 1)->with('storage')->with('author')->with('tags')->orderBy('price', 'DESC')->paginate(18);
                            break;
                        default:
                            $books = Book::where('highlights', 1)->with('storage')->with('author')->with('tags')->paginate(18);
                    }
                    if (count($books) < 1) {
                        return $this->sendMessage('Found 0 discount books.');
                    }
                    break;
                case 'newbooks':
                    $nowymd = Carbon::tomorrow();
                    $ago7days = Carbon::tomorrow()->subDays(7);

                    switch (request()->sort) {
                        case 'atoz':
                            $books = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->with('tags')->whereBetween('created_at', [$ago7days, $nowymd])->orderBy('name')->paginate(18);
                            break;
                        case 'atozdesc':
                            $books = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->with('tags')->whereBetween('created_at', [$ago7days, $nowymd])->orderBy('name', 'DESC')->paginate(18);
                            break;
                        case 'price';
                            $books = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->with('tags')->whereBetween('created_at', [$ago7days, $nowymd])->orderBy('price')->paginate(18);
                            break;
                        case 'pricedesc';
                            $books = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->with('tags')->whereBetween('created_at', [$ago7days, $nowymd])->orderBy('price', 'DESC')->paginate(18);
                            break;
                        default:
                            $books = Book::whereIn('highlights', [0, 1])->with('storage')->with('author')->with('tags')->whereBetween('created_at', [$ago7days, $nowymd])->paginate(18);
                    }
                    if (count($books) < 1) {
                        return $this->sendMessage('Found 0 new books.');
                    }
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
        $comments = Comment::with('user')->with('book')->where('id_book', $book->id)->get();
        $samebooks = Book::where('id_category', $book->id_category)->whereNotIn('id', [$book->id])->with('author')->orderBy('created_at', 'DESC')->take(3)->get();
        return $this->sendData(['book' => $book, 'comments' => $comments, 'samebooks' => $samebooks]);
    }

    public function getAuthors()
    {
        $authors = Author::withCount('books')->paginate(15);
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
        switch (request()->sort) {
            case 'atoz':
                $books = Book::whereIn('highlights', [0, 1])->where('id_author', $author->id)->with('author')->with('tags')->with('storage')->orderBy('name')->paginate(9);
                break;
            case 'atozdesc':
                $books = Book::whereIn('highlights', [0, 1])->where('id_author', $author->id)->with('author')->with('tags')->with('storage')->orderBy('name', 'DESC')->paginate(9);
                break;
            case 'price';
                $books = Book::whereIn('highlights', [0, 1])->where('id_author', $author->id)->with('author')->with('tags')->with('storage')->orderBy('price')->paginate(9);
                break;
            case 'pricedesc';
                $books = Book::whereIn('highlights', [0, 1])->where('id_author', $author->id)->with('author')->with('tags')->with('storage')->orderBy('price', 'DESC')->paginate(9);
                break;
            default:
                $books = Book::whereIn('highlights', [0, 1])->where('id_author', $author->id)->with('author')->with('tags')->with('storage')->paginate(9);
        }
        return $this->sendData($books);
    }

    public function getCategoies()
    {
        $categories = Category::withCount('books')->paginate(20);
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
        $books = Book::whereIn('highlights', [0, 1])->where('id_category', $category->id)->with('author')->with('tags')->paginate(9);
        return $this->sendData($books);
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
        if (is_null($user->orders)) {
            return $this->sendMessage('You are have 0 order !');
        }
        return $this->sendData($user);
    }

    public function deleteOrderUser(Request $request, $id)
    {
        $order = Order::find($id);
        if (is_null($order)) {
            return $this->sendErrorNotFound('Order not found !');
        }
        if ($order->status == 'waiting' || $order->status == 'cancel') {
            if ($request->user()->id == $order->id_user) {
                $order->status = 'cancel';
                $order->save();
                return $this->sendMessage('Just deleted order ' . $id . ' !');
            } else {
                return $this->sendErrorPermisstion('Cannot delete order of another user !');
            }
        } elseif ($order->status == 'accept') {
            return $this->sendMessage('Your order has been approved, you cannot cancel this order ! Give us a call if you really want to cancel this order.');
        } elseif ($order->status == 'sold') {
            return $this->sendMessage('Your order has been done ! Let us keep that.');
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
                    return $this->sendMessage('This book has been favorite, can not add this book one more time !');
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
                return $this->sendMessage('Unlike successfully this book !');
            }
        }
        return $this->sendErrorNotFound('Cannot found this book in your favorite list !');
    }

    public function getFavoriteBook(Request $request)
    {
        $user = User::find($request->user()->id);
        $books = $user->books()->with('tags')->with('author')->paginate(18);
        switch (request()->sort) {
            case 'atoz':
                $books = $user->books()->with('tags')->with('author')->with('storage')->sortBy('name')->paginate(18);
                break;
            case 'atozdesc':
                $books = $user->books()->with('tags')->with('author')->with('storage')->sortBy('name', 'DESC')->paginate(18);
                break;
            case 'price';
                $books = $user->books()->with('tags')->with('author')->with('storage')->sortBy('price')->paginate(18);
                break;
            case 'pricedesc';
                $books = $user->books()->with('tags')->with('author')->with('storage')->sortBy('price', 'DESC')->paginate(18);
                break;
            default:
                $books = $user->books()->with('tags')->with('author')->paginate(18);}
        if (count($books) < 1) {
            return $this->sendResponse($user, 'Found 0 book');
        }
        return $this->sendData($books);
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

    public function showInfoUser(Request $request)
    {
        $user = User::find($request->user()->id);
        if (is_null($user)) {
            return $this->sendErrorNotFound('User not found !');
        }
        if ($user->role == 1) {
            $role = 1;
        } elseif ($user->role == 0) {
            $role = 0;
        } else {
            $role = null;
        }
        return $this->sendData(['user' => $user, 'role' => $role]);

    }

    public function updateUser(Request $request)
    {
        $user = User::find($request->user()->id);
        $input = $request->all();
        $validator = Validator::make($input, [
            'name' => 'required',
            'email' => 'unique:users,email,' . $user->id,
            'password' => 'required',
            'phone' => 'unique:users,phone,' . $user->id,
        ], [
            'name.required' => 'Please enter name',
            'email.required' => 'Please enter email',
            'password.required' => 'Please enter password',
            'phone.unique' => 'This phone number already exits ! Please enter another phone number,' . $user->id,
        ]);
        if ($validator->fails()) {
            return $this->sendErrorValidation('Validation Error.', $validator->errors());
        }
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $file->store('/public/images');
            $name = $file->getClientOriginalName('avatar');
            $user->avatar = $name;
        }
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->birthday = $request->birthday;
        $user->address = $request->address;
        $user->phone = $request->phone;
        $user->save();
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
        Mail::to($request->email)->send(new ResetPassword());
    }

    // public function resetPassword(Request $request)
    // {
    //     $passwordreset = PasswordReset::where('token', $request->token);
    //     if ($passwordreset->email !== $request->email) {
    //         return $this->sendMessage('This token is not correct !');
    //     } else {
    //         $passwordreset->token = bcrypt(str_radom(60));
    //         $passwordreset->save();
    //     }
    //     $user = User::where('email', $request->email)->first();
    //     if ($request->password !== $request->confirm_password) {
    //         return $this->sendMessage('Password confirm must in the same !');
    //     }
    //     $user->password = $request->password;
    //     $user->save();
    //     return $this->sendMessage('Your account has been reset password !');
    // }
}
