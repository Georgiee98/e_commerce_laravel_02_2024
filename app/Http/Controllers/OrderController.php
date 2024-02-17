<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use DB;


class OrderController extends Controller
{
    // Display a listing of the orders
    public function index()
    {
        $orders = Order::all();
        return view('orders.index', compact('orders'));
    }

    // Show the form for creating a new order
    public function create()
    {
        // You may not need a separate create method if orders are created during checkout
    }

    // Store a newly created order in storage
    public function store(Request $request)
    {
        // This method may be handled during the checkout process
        DB::beginTransaction();
        try {
            // Assume $user_id is retrieved from session or auth for logged-in users
            // For guests, you might generate a unique session ID or handle it differently
            $user_id = auth()->id() ?? null; // Example for authenticated users

            // Create the order
            $order = Order::create([
                'user_id' => $user_id,
                'total_amount' => 0, // Initially 0, calculate based on order items
                'status' => 'pending',
            ]);

            $totalAmount = 0;

            // Loop through each product and create order items
            foreach ($request->products as $product) {
                $orderItem = OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product['id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'], // Assume price is passed; ideally, fetch from DB
                ]);

                $totalAmount += $product['price'] * $product['quantity'];
            }

            // Update order with total amount
            $order->update(['total_amount' => $totalAmount]);

            DB::commit();

            return response()->json(['message' => 'Order created successfully', 'order_id' => $order->id], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Order creation failed', 'error' => $e->getMessage()], 500);
        }
    }

    // Display the specified order
    public function show($id)
    {
        $order = Order::findOrFail($id);
        return view('orders.show', compact('order'));
    }

    // Show the form for editing the specified order
    public function edit($id)
    {
        // You may not need an edit method for orders
    }

    // Update the specified order in storage
    public function update(Request $request, $id)
    {
        // You may not need an update method for orders
    }

    // Remove the specified order from storage
    public function destroy($id)
    {
        // You may not need a destroy method for orders
    }
}