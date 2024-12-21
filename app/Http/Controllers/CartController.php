<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Order;

class CartController extends Controller
{

    //////user*******************************
    
     // Create or retrieve cart for the user
     public function getCart(Request $request)
     {
         $user = $request->user();
         $cart = Cart::firstOrCreate(['user_id' => $user->id]);
         return response()->json($cart->load('items.product'));
     }


     public function unBuy(Request $request,Order $order)
     {
        if($order->status=='completed')
        {
            return response()->json([
                'message'=>'order deliverd ,you can not cancelled the order'
                ,'order'=>$order
                ]);
        }elseif($order->status== 'cancelled')
        {
            return response()->json([
                'message'=> 'order already canelled'
            ,'order'=>$order]);
        }
        else
        {
            $order->status= 'cancelled';
            $order->save();
            $user = $request->user();
            $cart = Cart::where('user_id', $user->id)->firstOrFail();
            $cartItems=$cart->items;
            foreach ($cartItems as $cartItem)
            {   $product=$cartItem->product;
                $product->quantity += $cartItem->quantity;
                $product->sales -= $cartItem->quantity;
                $product->save();
            }

            return response()->json([
                'message'=>'order cancelled ',
                'cart'=>$cart->load('items.product')
                ,'order'=>$order]);
        }
        
     }
     public function buy(Request $request)
     {
   
        $user = $request->user();
        $cart = Cart::where('user_id', $user->id)->firstOrFail();
        $cartItems=$cart->items;
        $payments=null;
        foreach ($cartItems as $cartItem)
        {
            if($cartItem->product->quantity >= $cartItem->quantity)
            {
                $cartItem->product->quantity -= $cartItem->quantity;
                $cartItem->product->sales+=$cartItem->quantity;
                $payments +=$cartItem->product->price * $cartItem->quantity;
            }else{
                return response()->json([
                    "message"=>"the quantity of this product id ".$cartItem->product_id
                    ." is ".$cartItem->product->quantity." and you order ".$cartItem->quantity,
                    "quantity"=>$cartItem->product->quantity]);
            }
           
        }
        foreach ($cartItems as $cartItem)
        {
            $cartItem->product->save();
        }
        $order = Order::
        Create([
            'user_id' => $user->id,
            'cart_id' => $cart->id,
            'delivery_address' => $user->location,
            'bill'=>$payments,
            'status' => 'pending',
        ]);
        return response()->json([
            "order"=>$order,
            "message"=>"your oder has been bought successfully",
        "payments"=>$payments
        ]);
     }
     // Add product to cart
     public function addToCart(Request $request)
     {
         $user = $request->user();
         $cart = Cart::firstOrCreate(['user_id' => $user->id]);
         
        
         $cartItem = $cart->items()->firstOrCreate(
             ['product_id' => $request->product_id],
             ['quantity_up' => 0],
             ['quantity_down'=> 0],
             ['quantity'=> 0]
         );
         $cartItem->increment('quantity', $request->quantity_up ?? 1);
         
         return response()->json($cart->load('items.product'));
     }


     public function decreaseItems(Request $request)
     {
         $user = $request->user();
         $cart = Cart::firstOrCreate(['user_id' => $user->id]);
 
         $cartItem = $cart->items()->firstOrCreate(
             ['product_id' => $request->product_id],
             ['quantity_up' => 0],
             ['quantity_down'=> 0],
             ['quantity'=> 0]
         );
         
         $cartItem->decrement('quantity', $request->quantity_down ?? 1);
         return response()->json($cart->load('items.product'));
     }
 
     // Remove product from cart
     public function removeFromCart(Request $request)
     {
         $user = $request->user();
         $cart = Cart::where('user_id', $user->id)->firstOrFail();
         $cart->items()->where('product_id', $request->product_id)->delete();
 
         return response()->json($cart->load('items.product'));
     }
     public function removeCart(Request $request)
     {
         $user = $request->user();
         $cart = Cart::where('user_id', $user->id)->firstOrFail();
         $cart->items()->delete();
         DB::table('cart_items')->truncate();
         return response()->json([$cart->load('items.product'),"all Products deleted"]);
     }
}
