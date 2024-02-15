@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Your Shopping Cart</h2>

    <!-- Display total sum -->
    <h5>Total: ${{ number_format($total, 2) }}</h5>

    <!-- Display cart items -->
    @if(session('cart') && count(session('cart')) > 0)
    <div class="table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Image</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach(session('cart') as $id => $details)
                <tr>
                    <td>
                        <!-- Check if 'image' key exists and provide a default image if it doesn't -->
                        @php $imageUrl = $details['image'] ?? 'https://via.placeholder.com/150'; @endphp
                        <img src="{{ $imageUrl }}" class="img-fluid" alt="{{ $details['name'] }}">
                    </td>
                    <td>{{ $details['name'] }}</td>
                    <td>{{ $details['quantity'] }}</td>
                    <td>${{ $details['price'] }}</td>
                    <td>
                        <form action="{{ route('cart.remove', $id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <!-- Cart items -->

        <form action="{{ route('checkout') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-success">Checkout</button>
        </form>
        <a href="{{ route('checkout.show') }}" class="btn btn-success">Cart</a>

    </div>
    @else
    <p>Your cart is empty.</p>
    @endif
</div>
@endsection





<!-- @if(session('cart') && count(session('cart')) > 0)
    <div class="row">
        @foreach(session('cart') as $id => $details)
        <div class="col-md-4">
            <div class="card">
                {{-- Check if 'image' key exists and provide a default image if it doesn't --}}
                @php $imageUrl = $details['image'] ?? 'https://via.placeholder.com/150'; @endphp
                <img src="{{ $imageUrl }}" class="card-img-top" alt="{{ $details['name'] }}">

                <div class="card-body">
                    <h5 class="card-title">{{ $details['name'] }}</h5>
                    <p class="card-text">Quantity: {{ $details['quantity'] }}</p>
                    <p class="card-text">Price: ${{ $details['price'] }}</p>
                    {{-- Add more details as needed --}}
                </div>
                <div class="card-footer">
                    <form action="{{ route('cart.remove', $id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <p>Your cart is empty.</p>
    @endif -->