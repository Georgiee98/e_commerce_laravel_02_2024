<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderItemController extends Controller
{
    public function createOrder(Request $request)
    {
        $order = new Order();
        if (Auth::check()) {
            $order->user_id = Auth::id();
        }
        $order->total_amount = calculateTotal($request->cart); // Implement this function based on your needs
        $order->status = 'pending';
        $order->save();

        foreach (session('cart') as $id => $details) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $id;
            $orderItem->quantity = $details['quantity'];
            $orderItem->price = $details['price'];
            $orderItem->save();
        }

        session()->forget('cart'); // Clear the cart
        return redirect()->route('home')->with('success', 'Order placed successfully!');
    }
}