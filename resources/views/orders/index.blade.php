@extends('layouts.app')

@section('content')
<div class="container">
    <h2>All Orders</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Total Amount</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <!-- Iterate through orders and display them in rows -->
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->user->name }}</td>
                <td>${{ $order->total_amount }}</td>
                <td>{{ $order->status }}</td>
                <td>
                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-primary btn-sm">View</a>
                    <!-- Add more actions as needed -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection