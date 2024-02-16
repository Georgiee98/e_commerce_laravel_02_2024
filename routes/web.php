<?php
use App\Http\Controllers\SquareController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\PaymentController;

Route::get('/', function () {
    return view('welcome');
});

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
// Route to show a single product detail page
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
// Product Categories
Route::get('/category/{categoryName}', [ProductController::class, 'showProductsByCategory'])->name('category.products');


// Orders
Route::get('/orders', [OrderController::class, 'index']);
Route::get('/orders/{id}', [OrderController::class, 'show']);
// Add more routes as needed for other CRUD operations


// Cart
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::post('/cart/add/{productId}', [CartController::class, 'add'])->name('cart.add');

Route::post('/cart/update', [CartController::class, 'updateCart'])->name('cart.update');
Route::post('/cart/remove/{productId}', [CartController::class, 'removeFromCart'])->name('cart.remove');


// Checkout

Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('checkout');

// Payment
Route::get('/checkout', [PaymentController::class, 'showCheckout'])->name('checkout.show');
// Process payment
Route::post('/process-payment', [PaymentController::class, 'processPayment'])->name('payment.process');
// Route::post('/payment/process', [PaymentController::class, 'processPayment'])->name('payment.process');


Route::get('/square', [SquareController::class, 'index'])->name('square');


Route::post('/test', function () {
    return response()->json(['message' => 'Route is working']);
});