<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Driver;
use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;

class OrderController extends Controller
{
    //Driver*******************************************
    public function index()
    {
        $orders=Order::where('status','pending')->paginate(10);
        return response()->json([
            'orders'=>$orders
        ]);
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
    public function quickBuy(Request $request,Product $product)
    {
  
       $user = $request->user();
      $quantity=$request->quantity??1;
       $payments=0;
       if($product->quantity >= $quantity)
       {
           $product->quantity -= $quantity;
           $product->sales+=$quantity;
           $payments =$product->price * $quantity;
       }else{
            response()->json([
               "message"=>"the quantity of this product id ".$product->id
               ." is ".$product->quantity." and you order ".$quantity,
               "quantity"=>$product->quantity]);
       }
      
       $product->save();

       if($user->pocket < $payments)
       {   
           return response()->json([
          'message' => 'no enough balance your pocket is '.$user->pocket], 400);
       }
       $user->pocket -=$payments;
       $user->save();

       
           
       $order = Order::
       Create([
           'user_id' => $user->id,
           
           'delivery_address' => $user->location,
           'bill'=>$payments,
           'status' => 'pending',
       ]);
      
           OrderItem::create([
               'order_id' => $order->id,
               'product_id' => $product->id,
               'quantity' => $quantity,
               'price' => $product->price, 
           ]);
     


 
       return response()->json([
           "order"=>$order,
           
           "message"=>"your oder has been bought successfully",
       "payments"=>$payments,
       "user"=>$user,
       ]);
    }
    public function acceptOrder(Request $request,Order $order)
    {
        $driver=$request->user('driver');
        if( $order->status=='pending')
        {
            $driver->available=false;
            $driver->save();
            $order->update(['driver_id' => $driver->id, 'status' => 'accepted']);
            
            return response()->json(['message' => 'Order Accepted Successfully', 'order' => $order], 200);
        }elseif( $order->status=='cancelled'){
            return response()->json(['message' =>'order has been Cancelled from Customer'],400);    
        }
        else{
        return response()->json(['message' =>'order has been taken'],400);
    }
    }
    public function changeStatus(Request $request,Order $order)
    {
        $driver=$request->user('driver');
        if( $order->driver_id ==$driver->id)
        {
            if($order->status!='cancelled')
            {
                $status=$request->input('status');
                $order->status=$status;
                $order->save();
                return response()->json([
                    'order'=>$order],200);
            }else{
                return response()->json([
                    'message'=>'Order Cancelled ,We Are Sory',
                    'order'=>$order],400);
            }
        
   
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
    public function getOrderItems(Request $request,Order $order)
    {
        return response()->json(['order'=>$order->load('items.product')]);

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
