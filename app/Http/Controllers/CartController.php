<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartController extends Controller
{

    // Add a product to the cart
    public function add(Request $request, $productId)
    {
        // Retrieve the product
        $product = Product::findOrFail($productId);

        // Check if the product has an image set
        $image = $product->image ?? null;

        // Retrieve the cart from the session
        $cart = session()->get('cart', []);

        // Check if the product is already in the cart
        if (isset($cart[$productId])) {
            // Increment the quantity
            $cart[$productId]['quantity']++;
        } else {
            // Add the product to the cart
            $cart[$productId] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "image" => $image // Set the image from the product
            ];
        }

        // Update the cart in the session
        session()->put('cart', $cart);

        // Return the updated cart total
        return response()->json(['cartTotal' => count(session()->get('cart'))]);
    }

    // Display the cart
    public function show()
    {
        $cart = session()->get('cart');
        $total = 0; // Initialize total sum

        // Calculate total sum of all items in the cart
        foreach ($cart as $details) {
            $total += $details['quantity'] * $details['price'];
        }

        return view('cart.show', compact('cart', 'total'));
    }
    // Update the cart
    public function updateCart(Request $request)
    {
        if ($request->id and $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]['quantity'] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
        return redirect()->back();
    }

    // Remove item from the cart
    public function removeFromCart(Request $request, $productId)
    {
        $cart = session()->get('cart');
        if (isset($cart[$productId])) {
            unset($cart[$productId]);
            session()->put('cart', $cart);
            session()->flash('success', 'Product removed successfully');
        }
        return redirect()->back();
    }


}