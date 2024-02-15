@extends('layouts.app')

@section('title', 'Checkout')

@section('content')
<div class="container">
    <h2>Checkout</h2>
    @if(session('cart') && count(session('cart')) > 0)
    <div>
        <!-- Display cart items for review -->
    </div>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Checkout') }}</div>

                <div class="card-body">
                    <form id="payment-form" method="POST" action="{{ route('payment.process') }}">
                        @csrf

                        <!-- Billing Address -->
                        <div class="mb-3">
                            <label for="billing_address" class="form-label">Billing Address</label>
                            <input type="text" class="form-control" id="billing_address" name="billing_address"
                                required>
                        </div>

                        <!-- Shipping Address -->
                        <div class="mb-3">
                            <label for="shipping_address" class="form-label">Shipping Address</label>
                            <input type="text" class="form-control" id="shipping_address" name="shipping_address"
                                required>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="visa_card">Visa Card</option>
                                <option value="master_card">Master Card</option>
                            </select>
                        </div>

                        <!-- Credit Card Information -->
                        <div class="mb-3">
                            <label class="form-label">Card Information</label>
                            <div id="card-container"></div>
                            <input type="hidden" id="nonce" name="nonce">
                            @if(isset($cart['totalAmount']))
                            <input type="hidden" id="amount" name="amount" value="{{ $cart['totalAmount'] }}">
                            @endif
                        </div>

                        <!-- Additional Checkout Information -->
                        <div class="mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="credit_card_number" name="credit_card_number"
                                required>
                        </div>

                        <!-- Additional Checkout Information -->
                        <div class="mb-3">
                            <label for="credit_card_number" class="form-label">Credit Card Number</label>
                            <input type="text" class="form-control" id="credit_card_number" name="credit_card_number"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="credit_card_expiration" class="form-label">Credit Card Expiration</label>
                            <input type="text" class="form-control" id="credit_card_expiration"
                                name="credit_card_expiration" required>
                        </div>
                        <div class="mb-3">
                            <label for="credit_card_cvv" class="form-label">Credit Card CVV</label>
                            <input type="text" class="form-control" id="credit_card_cvv" name="credit_card_cvv"
                                required>
                        </div>
                        <!-- Include any other checkout-related form fields here -->

                        <button type="submit" id="submit-payment" class="btn btn-primary">Submit Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @else
    <p>Your cart is empty.</p>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://js.squareupsandbox.com/v2/paymentform"></script>
<script>
    var paymentForm = new SqPaymentForm({
        applicationId: "{{ env('SQUARE_SANDBOX_APPLICATION_ID') }}",
        inputClass: 'sq-input',
        autoBuild: false,
        inputStyles: [{
            fontSize: '16px',
            lineHeight: '24px',
            padding: '16px',
            placeholderColor: '#a0a0a0',
        }],
        cardNumber: {
            elementId: 'sq-card-number',
            placeholder: 'Card Number'
        },
        cvv: {
            elementId: 'sq-cvv',
            placeholder: 'CVV'
        },
        expirationDate: {
            elementId: 'sq-expiration-date',
            placeholder: 'MM/YY'
        },
        postalCode: {
            elementId: 'sq-postal-code',
            placeholder: 'Postal Code'
        },
        callbacks: {
            cardNonceResponseReceived: function (errors, nonce, cardData) {
                if (errors) {
                    console.error("Encountered errors:");
                    errors.forEach(function (error) {
                        console.error('  ' + error.message);
                    });
                    alert('Card data is invalid. Please check your input and try again.');
                    return;
                }
                document.getElementById('nonce').value = nonce;
                document.getElementById('payment-form').submit();
            }
        }
    });

    paymentForm.build();

    $('#submit-payment').on('click', function (e) {
        e.preventDefault();
        paymentForm.requestCardNonce();
    });
</script>
@endsection