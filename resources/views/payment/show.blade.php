@extends('layouts.app')

@section('title', 'Payment')

@section('content')
<div class="container">
    <h2>Payment</h2>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Payment') }}</div>

                <div class="card-body">
                    <form id="payment-form" method="POST" action="{{ route('payment.process') }}">
                        @csrf

                        <!-- Amount -->
                        <div class="mb-3">
                            <label for="amount" class="form-label">Amount</label>
                            <input type="number" class="form-control" id="amount" name="amount" required>
                        </div>

                        <!-- Payment Method -->
                        <div class="mb-3">
                            <label for="payment_method" class="form-label">Payment Method</label>
                            <select class="form-select" id="payment_method" name="payment_method" required>
                                <option value="credit_card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                            </select>
                        </div>

                        <!-- Other form fields as needed -->

                        <button type="submit" class="btn btn-primary">Submit Payment</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Add your JavaScript code here, if needed
</script>
@endsection