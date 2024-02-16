<p>Dear {{ $user->name }},</p>

<p>Your order (Order Number: {{ $order->id }}) has been successfully processed.</p>
<p>Here are your order details:</p>

<ul>
    <li>Total Amount: ${{ $order->total_amount }}</li>
    <!-- Add more order details as needed -->
</ul>

<p>Your account details:</p>

<ul>
    <li>Email: {{ $user->email }}</li>
    <li>Password: {{ $user->password }}</li>
    <!-- Add more user details as needed -->
</ul>

<p>Thank you for shopping with us!</p>