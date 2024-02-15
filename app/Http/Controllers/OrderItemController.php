<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class OrderItemController extends Controller
{

    // Display a listing of the order items
    public function index()
    {
        $orderItems = OrderItem::all();
        return view('order_items.index', compact('orderItems'));
    }
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



    public function show($id)
    {
        $orderItem = OrderItem::findOrFail($id);
        return view('order_items.show', compact('orderItem'));
    }

}