@extends('layouts.app')

@section('title', 'Checkout')
@section('content')
<div class="container">
    <h1>Checkout</h1>
    <div id="payment-form">
        <div id="payment-status-container"></div>
        <div id="card-container" class="card p-3 mb-3"></div>
        <button id="card-button" type="button" class="btn btn-primary">Pay</button>
    </div>
</div>
@endsection

@section('scripts') {{-- corrected section name --}}
<script type="module">
    const payments = Square.payments('sandbox-sq0idb-RT3u-HhCpNdbMiGg5aXuVg', 'TC4Z3ZEBKRXRH');
    const card = await payments.card();
    await card.attach('#card-container');

    const cardButton = document.getElementById('card-button');
    cardButton.addEventListener('click', async () => {
        const statusContainer = document.getElementById('payment-status-container');

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
        }
    });
</script>
@endsection