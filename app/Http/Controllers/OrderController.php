<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Driver;
use App\Models\Order;
class OrderController extends Controller
{
    //Driver*******************************************
    public function index()
    {
        return Order::where('status','pending')->paginate(10);
    }
    public function currentOrders(Request $request)
    {
       $driver= $request->user('driver');
       $cutrrentOrders=$driver->orders()->where('status','accepted')->
       orWhere('status','in_progress')
       ->get();
       if($cutrrentOrders)
       {
        return response()->json([
            'currentOrders'=>$cutrrentOrders,
        ]);
       }else{
        return response()->json([
            'message'=>'there is no order taken for you '
        ]);
       }

    }
    public function acceptOrder(Request $request,Order $order)
    {
        $driver=$request->user('driver');
        if( $order->status=='pending')
        {
            $driver->available=false;
            $driver->save();
            $order->update(['driver_id' => $driver->id, 'status' => 'accepted']);
    
            return response()->json(['message' => 'Order accepted', 'order' => $order], 200);
        }else{
        return response()->json(['message' =>'order has been taken']);
    }
    }
    public function changeStatus(Request $request,Order $order)
    {
        $driver=$request->user('driver');
        if( $order->driver_id ==$driver->id)
        {
        $status=$request->input('status');
        $order->status=$status;
        $order->save();
        return response()->json([
            'order'=>$order],200);
        }else
        {
            return response()->json(['message'=> 'this order not for you'], 401);
        }
        
    }


    ////user************************************************
    public function getOrders(Request $request)
    {
        $user=$request->user();
        $orders=$user->orders;
        return response()->json(['orders'=>$orders]);
    }
    public function getAcceptedOrders(Request $request)
    {
        $user=$request->user();
        $orders=$user->orders()->where('status','accepted')->paginate(10);
        return response()->json(['accepted orders'=>$orders]);
    }
    public function getPindingOrders(Request $request)
    {
        $user=$request->user();
        $orders=$user->orders()->where('status','!=','accepted')->paginate(10);
        return response()->json(['unaccepted orders'=>$orders]);
    }
   
}
