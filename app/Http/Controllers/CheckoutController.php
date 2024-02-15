<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use App\Services\SquareService;


class CheckoutController extends Controller
{
    function calculateTotal($cart)
    {
        $subtotal = 0;

        // Calculate subtotal
        foreach ($cart as $id => $details) {
            $product = Product::find($id);
            if ($product) {
                $subtotal += $details['quantity'] * $details['price'];
            }
        }

        // Example: Calculate tax and shipping fees
        $taxRate = 0.05; // 5% tax rate
        $shippingFee = 10; // $10 shipping fee

        $taxAmount = $subtotal * $taxRate;
        $totalAmount = $subtotal + $taxAmount + $shippingFee;

        return $totalAmount;
    }

    public function checkout(Request $request, SquareService $squareService)
    {
        $cart = session()->get('cart');
        if (!$cart) {
            return redirect()->route('cart.show')->with('error', 'Your cart is empty.');
        }

        $nonce = $request->input('nonce'); // The payment nonce from Square's Web Payments SDK
        $totalAmount = $this->calculateTotal($cart);

        // Attempt to process the payment with Square
        $paymentResult = $squareService->createPayment($totalAmount, $nonce, uniqid());

        if (!$paymentResult) {
            return back()->with('error', 'Payment failed. Please try again.');
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_amount' => $totalAmount,
                'status' => 'paid', // Or 'pending' until you verify the payment success
            ]);

            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price'],
                ]);
            }

            session()->forget('cart'); // Clear the cart after successful payment and order creation
            DB::commit(); // Commit the transaction

            return redirect()->route('home')->with('success', 'Order placed and payment successful.');
        } catch (\Exception $e) {
            DB::rollback(); // Rollback the transaction on error
            return redirect()->route('cart.show')->with('error', 'Error placing order. Please try again.');
        }
    }

    public function showCheckout()
    {
        return view('checkout.index'); // Make sure this view exists in your resources/views/checkout directory
    }
}