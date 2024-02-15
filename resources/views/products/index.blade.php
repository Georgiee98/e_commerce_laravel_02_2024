@extends('layouts.app')

@section('title', 'All Products')

@section('content')
<div class="container">
    <h2>All Products</h2>
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4">
            <div class="card">
                @if($product->image)
                <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
                @else
                <img src="https://via.placeholder.com/150" class="card-img-top" alt="{{ $product->name }}">
                @endif
                <div class="card-body">
                    <h5 class="card-title">
                        <a href="{{ route('products.show', $product) }}" style="text-decoration: none;">{{
                            $product->name }}</a>
                    </h5>
                    <p class="card-text">{{ $product->description }}</p>
                    <p class="card-text">${{ $product->price }}</p>
                    <!-- Add to Cart Form -->
                    <form action="{{ route('cart.add', $product->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-primary add-to-cart-btn"
                            data-product-id="{{ $product->id }}">Add to Cart</button>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>

<script>
    $(document).ready(function () {
        $('.add-to-cart-btn').click(function () {
            var productId = $(this).data('product-id');
            $.ajax({
                url: "{{ url('/cart/add') }}/" + productId,
                type: "POST",
                data: {
                    _token: "{{ csrf_token() }}",
                    productId: productId
                },
                success: function (response) {
                    // Assuming you have a function to update the cart UI
                    updateCartUI(response);
                },
                error: function (response) {
                    // Handle error
                    alert('Error adding product to cart');
                }
            });
        });
    });

    function updateCartUI(response) {
        // Update your cart UI here
        // For simplicity, this could be updating a cart item count or sliding in a cart panel
        console.log("Product added", response);
        // Example: Update a cart counter
        $('#cart-counter').text(response.cartTotal);
    }
</script>

@endsection