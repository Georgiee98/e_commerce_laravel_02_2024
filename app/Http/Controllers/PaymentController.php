<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SquareService;
use Illuminate\Support\Facades\Log; // Import the Log facade for logging
use Square\SquareClient;
use Square\Models\Money;
use Square\Models\CreatePaymentRequest;
use Square\Exceptions\ApiException;
use App\Models\Payment;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    protected $squareService;

    // Dependency injection of the SquareService
    public function __construct(SquareService $squareService)
    {
        $this->squareService = $squareService;
    }
    public function showCheckout(Request $request)
    {
        $total = $request->query('total');
        // Retrieve the cart data from the session
        $cart = session()->get('cart', []);

        // Check if $cart is null or if totalAmount key is not set
        // if (is_null($cart) || !isset($cart['totalAmount'])) {
        //     // Set a default value for totalAmount or handle the missing cart data as needed
        //     // For example, you can redirect back with an error message
        //     return redirect()->back()->withErrors('Cart data is missing or invalid.');
        // }

        // If the cart data is valid, continue with displaying the checkout page
        return view('checkout.show', compact('cart', 'total'));
    }

    public function processPayment(Request $request)
    {

        // Retrieve the payment nonce and total amount from the request
        $nonce = $request->input('nonce');
        $totalAmount = $request->input('totalAmount'); // Ensure this is in the smallest currency unit if necessary

        if (is_null($nonce)) {
            return response()->json(['error' => 'Payment information is missing.']);
        }

        // Set up the Square client with appropriate environment and access token
        $client = new SquareClient([
            'accessToken' => config('services.square.access_token'),
            'environment' => \Square\Environment::SANDBOX,
        ]);

        // Define the payment amount and currency
        $amountMoney = new Money();
        $amountMoney->setAmount($totalAmount); // Assuming totalAmount is correctly formatted
        $amountMoney->setCurrency('MKD'); // Set to your desired currency

        // Create the payment request
        $paymentBody = new CreatePaymentRequest($nonce, uniqid(), $amountMoney);
        DB::beginTransaction();
        try {
            $result = $client->getPaymentsApi()->createPayment($paymentBody);

            if ($result->isSuccess()) {
                // Extract the payment result
                $paymentResult = $result->getResult()->getPayment();

                // Determine if the user is authenticated or a guest
                if (Auth::check()) {
                    $user = Auth::user();
                    // Send email to registered user
                    // Mail::to($user->email)->send(new RegisteredUserOrderConfirmation($order, $user));
                } else {
                    // For a guest user, create a new user account or handle as desired
                    $name = $request->input('nameOnCard', 'Guest User');
                    $email = $request->input('email');

                    $user = User::create([
                        'nameOnCard' => $name,
                        'email' => $email,
                        'password' => Hash::make(Str::random(24)), // Consider sending a password reset link instead
                    ]);

                    // Send email to guest user
                    // Mail::to($user->email)->send(new GuestUserOrderConfirmation($order, $user));
                    // Trigger password reset/link email to the user
                    // Password::sendResetLink(['email' => $email]);
                }

                // Retrieve cart items from session or request
                $cartItems = session()->get('cart', []);

                // Create the order
                $order = Order::create([
                    'user_id' => $user->id,
                    'total_amount' => 100,//$totalAmount / 100, // Adjust if your amount is in the smallest currency unit
                    'status' => 'Paid',
                ]);

                // Iterate over cart items to create order items
                foreach ($cartItems as $item) {
                    $order->items()->create([
                        'product_id' => $item['id'],
                        'quantity' => $item['quantity'],
                        'price' => $item['price'],
                    ]);
                }

                // Clear the cart session after order creation
                session()->forget('cart');

                // Send order confirmation email
                Mail::to($user->email)->send(new OrderConfirmation($order, $user));

                return response()->json(['status' => 'success', 'message' => 'Payment processed and order created successfully.']);
            } else {
                return response()->json(['status' => 'error', 'message' => $result->getErrors()]);
            }
            // } catch (ApiException $e) {
            //     return response()->json(['status' => 'exception', 'message' => $e->getMessage()]);
            // }
        } catch (\Exception $e) {
            Log::error("An error occurred: " . $e->getMessage());
            DB::rollBack();
            return response()->json(['status' => 'exception', 'message' => 'An unexpected error occurred.']);
        } catch (\Throwable $e) {
            Log::error("A throwable error occurred: " . $e->getMessage());
            DB::rollBack();
            return response()->json(['status' => 'exception', 'message' => 'A critical error occurred.']);
        }

    }
}