@if(session('cart'))
<div class="cart">
    @foreach(session('cart') as $id => $details)
    <div class="item">
        <img src="{{ $details['photo'] }}" width="100" height="100" />
        <p>{{ $details['name'] }}</p>
        <p>Price: {{ $details['price'] }}$</p>
        <p>Quantity: {{ $details['quantity'] }}</p>
        <!-- Update form -->
        <!-- Remove form -->
    </div>
    @endforeach
</div>
@endif