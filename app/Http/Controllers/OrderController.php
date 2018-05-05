<?php

namespace App\Http\Controllers;

use App\History;
use App\Http\Controllers\API\APIBaseController as APIBaseController;
use App\Order;
use App\OrderDetail;
use App\Storage;
use App\User;
use Illuminate\Http\Request;

class OrderController extends APIBaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = Order::paginate(16);
        if (is_null($orders)) {
            return $this->sendMessage('Found 0 orders !');
        }
        return $this->sendData($orders);
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

    /**
     * Display the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = Order::find($id);
        if (is_null($order)) {
            return $this->sendErrorNotFound('Order not found !');
        } else {
            return $this->sendData($order);
        }
    }

    public function showOrderUser(Request $request)
    {
        $orders = Order::where('id_user', $request->user()->id)->get();
        if (is_null($orders)) {
            return $this->sendMessage('You are have 0 order !');
        }
        return $this->sendData($orders);
    }

    public function deleteOrderUser(Request $request, $id)
    {
        $order = Order::find($id);
        if (is_null($order)) {
            return $this->sendErrorNotFound('Order not found !');
        }
        if ($order->status == 'waiting' || $order->status == 'cancel') {
            if ($request->user()->id == $order->id_user) {
                $order->delete();
                return $this->sendMessage('Just deleted order ' . $id . ' !');
            } else {
                return $this->sendErrorPermisstion('Cannot delete order of another user !');
            }
        } elseif ($order->status == 'accept') {
            return $this->sendMessage('Your order has been approved, you cannot cancel this order ! Give us a call if you really want to cancel this order.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $order = Order::find($id);
        if (is_null($order)) {
            return $this->sendErrorNotFound('Order not found.');
        }
        if ($order->status == 'sold') {
            return $this->sendMessage('This order has been sold !');
        } elseif ($order->status == 'accept') {
            if ($request->status == 'accept') {
                return $this->sendMessage('This order has been accepted !');
            } elseif ($request->status == 'cancel') {
                return $this->sendMessage('Order ' . $id . ' has been accepted ! Just delete to stop');
            } elseif ($order->status == 'sold') {
                return $this->sendMessage('Added to total revenue');
            }
        }
        $order->status = $request->status;
        $order->id_user = $request->user()->id;
        $order->save();
        if ($order->status == 'cancel') {
            return $this->sendMessage('Order has been cancel successfully');
        }
        $orderdetails = OrderDetail::where('id_order', $order->id)->get();
        $idbook = array();
        foreach ($orderdetails as $orderdetail) {
            $storage = Storage::where('id_book', $orderdetail->id_book)->first();
            if ($orderdetail->quantity > $storage->quantity) {
                $idbook[] = $orderdetail->id_book;
            }
        }
        if ($idbook) {
            return $this->sendResponse($idbook, 'Have a book not enought to sells, please check quantity before sells !');
        }
        foreach ($orderdetails as $odd) {
            $storage = Storage::where('id_book', $odd['id_book'])->first();
            $old = $storage['quantity'];
            $storage['quantity'] = ($old - $odd['quantity']);
            $storage->save();

            $history = new History;
            $history->status = 'selling';
            $history->quantity = $odd['quantity'];
            $history->id_book = $odd['id_book'];
            $history->id_user = $request->user()->id;
            $history->save();
        }
        return $this->sendMessage('Order ' . $id . ' accept successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $order = Order::find($id);
        if (is_null($order)) {
            return $this->sendErrorNotFound('Order not found !');
        }
        if ($order->status == 'sold') {
            return $this->sendMessage('Can not delete this order ! Because this order has been sold !');
        } elseif ($order->status == 'accept') {
            $orderdetails = OrderDetail::where('id_order', $order->id)->get();
            foreach ($orderdetails as $orderdetail) {
                $storage = Storage::where('id_book', $orderdetail->id_book)->first();
                $oldQuantity = $storage->quantity;
                $storage->quantity = $oldQuantity + $orderdetail->quantity;
                $storage->save();

                $history = new History;
                $history->status = 'cancel';
                $history->quantity = $orderdetail->quantity;
                $history->id_book = $orderdetail->id_book;
                $history->id_user = $request->user()->id;
                $history->save();
            }
        }
        $order->status = 'cancel';
        $order->save();
        return $this->sendMessage('Order cancel successfully !');
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
}
