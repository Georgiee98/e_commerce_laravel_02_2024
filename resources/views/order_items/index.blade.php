@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Order Items</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Order ID</th>
                <th>Product</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
        </thead>
        <tbody>
            <!-- Iterate through order items and display them in rows -->
            @foreach($orderItems as $orderItem)
            <tr>
                <td>{{ $orderItem->id }}</td>
                <td>{{ $orderItem->order->id }}</td>
                <td>{{ $orderItem->product->name }}</td>
                <td>{{ $orderItem->quantity }}</td>
                <td>${{ $orderItem->price }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection