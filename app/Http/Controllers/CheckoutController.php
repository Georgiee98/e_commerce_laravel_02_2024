<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function checkout(Request $request)
    {
        $order = new Order();
        $order->total_amount = calculateTotal($request->cart); // Implement this to calculate the total amount
        $order->status = 'pending';
        // For guest checkout, don't set user_id or set it to null
        if (Auth::check()) {
            $order->user_id = Auth::id();
        }
        $order->save();

        foreach (session('cart') as $id => $details) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->product_id = $id;
            $orderItem->quantity = $details['quantity'];
            $orderItem->price = $details['price'];
            $orderItem->save();
        }

        session()->forget('cart'); // Clear the cart after order is placed

        // Implement order confirmation notification (e.g., email) to the guest
        // sendOrderConfirmation($order, $request->email); // This is a hypothetical function

        return redirect()->route('order.confirmation')->with('success', 'Order placed successfully!');
    }
}