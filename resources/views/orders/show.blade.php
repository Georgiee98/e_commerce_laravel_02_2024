@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Order Details</h2>
    <table class="table">
        <tr>
            <th>ID</th>
            <td>{{ $order->id }}</td>
        </tr>
        <tr>
            <th>User</th>
            <td>{{ $order->user->name }}</td>
        </tr>
        <tr>
            <th>Total Amount</th>
            <td>${{ $order->total_amount }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ $order->status }}</td>
        </tr>
        <!-- Add more order details as needed -->
    </table>
</div>
@endsection