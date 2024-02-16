@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="container">
    <h1>Checkout</h1>
    <div id="payment-form">
        <form action="">
            @CSRF

            <div id="payment-status-container"></div>
            <div id="card-container" class="card p-3 mb-3" style="border: none; max-width: auto;">
                <input type="text" name="order_id" value="order_id" hidden />
                <!-- Email Address Input -->
                <input type="email" id="email" name="email" placeholder="Email Address" required>
                <small class="error-message text-danger d-none" role="alert">This field is required.</small>
                <input type="text" id="nameOnCard" name="nameOnCard" placeholder="Name of holder">
                <div class="spinner center">
                    <div class="spinner-blade"></div>
                    <div class="spinner-blade"></div>
                    <div class="spinner-blade"></div>
                    <div class="spinner-blade"></div>
                    <div class="spinner-blade"></div>
                    <div class="spinner-blade"></div>
                    <div class="spinner-blade"></div>
                    <div class="spinner-blade"></div>
                    <div class="spinner-blade"></div>
                    <div class="spinner-blade"></div>
                    <div class="spinner-blade"></div>
                    <div class="spinner-blade"></div>
                </div>
            </div>
            <!-- Spinner/loader -->
            <div id="loader" class="spinner-border text-primary mx-auto mb-3" role="status" style="display: none;">
                <span class="sr-only">Loading...</span>
            </div>
            <!-- Adjust the max-width as needed -->
            <button id="card-button" type="button" class="btn btn-primary mx-auto d-block">Pay ${{ $total }}</button>

            <!-- Add a hidden input to store the total amount -->
            <input type="hidden" id="totalAmount" value="{{ $total }}">
        </form>

    </div>
</div>

@endsection

@section('styles')
<link href="{{ asset('css/card/card.css') }}" rel="stylesheet">
@endsection

@section('scripts')
<script type="module">
const applicationId = "{{ env('SQUARE_SANDBOX_APPLICATION_ID') }}";
const accessToken = "{{ env('SQUARE_SANDBOX_ACCESS_TOKEN') }}";

const payments = Square.payments(applicationId, accessToken);
const card = await payments.card();
await card.attach('#card-container');

const cardButton = document.getElementById('card-button');
const loader = document.getElementById('loader');
const totalAmountInput = document.getElementById('totalAmount');
const totalAmount = parseFloat(totalAmountInput.value); // Retrieve the total amount

cardButton.addEventListener('click', async () => {
    const statusContainer = document.getElementById('payment-status-container');

    // Show the loader
    loader.style.display = 'block';

    const email = document.getElementById('email').value;
    const nameOnCard = document.getElementById('nameOnCard').value;

    try {
        const result = await card.tokenize();
        if (result.status === 'OK') {
            console.log(`Payment token is ${result.token}`);
            statusContainer.innerHTML = "Payment Successful";
            console.log(`Payment token is ${result.token}`);
            statusContainer.innerHTML = "Payment Successful";
            // Disable the button and update its text
            cardButton.disabled = true;
            cardButton.innerText = `Payment Completed $${totalAmount}`;


            // Add logic to send payment token and total amount to the backend
            // You can use fetch or Axios to send a POST request to your backend
            // Example using fetch:
            const response = await fetch('/process-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token if you're using Laravel
                },
                body: JSON.stringify({
                    nonce: result.token,
                    totalAmount: totalAmount,
                    email: email,
                    nameOnCard: nameOnCard
                })
            });
            const data = await response.json();
            console.log(data); // Log the response from the backend
        } else {
            let errorMessage = `Tokenization failed with status: ${result.status}`;
            if (result.errors) {
                errorMessage += ` and errors: ${JSON.stringify(
                        result.errors
                    )}`;
            }

            throw new Error(errorMessage);
        }
    } catch (e) {
        console.error(e);
        statusContainer.innerHTML = "Payment Failed";
    } finally {
        // Hide the loader when the process completes
        loader.style.display = 'none';
    }
});
</script>
@endsection