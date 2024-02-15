@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Order Item Details</h2>
    <table class="table">
        <tr>
            <th>ID</th>
            <td>{{ $orderItem->id }}</td>
        </tr>
        <tr>
            <th>Order ID</th>
            <td>{{ $orderItem->order->id }}</td>
        </tr>
        <tr>
            <th>Product</th>
            <td>{{ $orderItem->product->name }}</td>
        </tr>
        <tr>
            <th>Quantity</th>
            <td>{{ $orderItem->quantity }}</td>
        </tr>
        <tr>
            <th>Price</th>
            <td>${{ $orderItem->price }}</td>
        </tr>
        <!-- Add more order item details as needed -->
    </table>
</div>
@endsection