@extends('layouts.app')
@section('title', 'Checkout')
@section('content')
<div class="container">
    <h1>Checkout</h1>
    <div id="payment-form">
        <div id="payment-status-container"></div>
        <div id="card-container" class="card p-3 mb-3" style="border: none; max-width: auto;">
            <input type="text" name="order_id" value="order_id" hidden />
            <input type="text" id="nameOnCard" name="nameOnCard" placeholder="Name of holder">
            <small class="error-message text-danger d-none" role="alert">This field is required.</small>
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
        <button id="card-button" type="button" class="btn btn-primary mx-auto d-block">Pay (SUM)</button>
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
    cardButton.addEventListener('click', async () => {
        const statusContainer = document.getElementById('payment-status-container');

        // Show the loader
        loader.style.display = 'block';

        try {
            const result = await card.tokenize();
            if (result.status === 'OK') {
                console.log(`Payment token is ${result.token}`);
                statusContainer.innerHTML = "Payment Successful";
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