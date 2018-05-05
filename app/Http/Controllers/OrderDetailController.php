<?php

namespace App\Http\Controllers;

use App\Order_derail;
use Illuminate\Http\Request;

class OrderDerailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orderdetails = OrderDetail::paginate(16);
        if(count($orderdetails)<1){
            return $this->sendMessage('Found 0 order details !');
        }
        return $this->sendData($orderdetails);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Order_derail  $order_derail
     * @return \Illuminate\Http\Response
     */
    public function show(Order_derail $order_derail)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Order_derail  $order_derail
     * @return \Illuminate\Http\Response
     */
    public function edit(Order_derail $order_derail)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order_derail  $order_derail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Order_derail $order_derail)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Order_derail  $order_derail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $orderdetail = OrderDetail::find($id);
        if(is_null($orderdetail)){
            return $this->sendErrorNotFound('Order Detail not found !');
        }
        return $this->sendMessage('Deleted order detail '.$id.' successfully !');
    }
}
