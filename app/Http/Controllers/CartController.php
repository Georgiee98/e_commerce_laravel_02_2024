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
    public function addToCart(Request $request, $productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            abort(404);
        }

        $cart = session()->get('cart', []);

        // If the product is already in the cart, increment the quantity
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity']++;
        } else {
            // If not, add it to the cart with quantity = 1
            $cart[$productId] = [
                "name" => $product->name,
                "quantity" => 1,
                "price" => $product->price,
                "photo" => $product->photo // Assuming your Product model has a photo field
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', 'Product added to cart successfully!');
    }

    // Display the cart
    public function showCart()
    {
        $cart = session()->get('cart');
        return view('cart', compact('cart'));
    }

    // Update the cart
    public function updateCart(Request $request)
    {
        if ($request->id and $request->quantity) {
            $cart = session()->get('cart');
            $cart[$request->id]["quantity"] = $request->quantity;
            session()->put('cart', $cart);
            session()->flash('success', 'Cart updated successfully');
        }
    }

    // Remove item from the cart
    public function removeFromCart(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            session()->flash('success', 'Product removed successfully');
        }
    }
}