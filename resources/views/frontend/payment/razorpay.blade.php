<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Redirecting to Payment...</title>
    <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
</head>
<body>
    <p style="text-align:center; margin-top:60px; font-family:sans-serif;">
        Please wait, opening secure payment window...
    </p>

    {{-- Razorpay success → ye form callback pe POST hoga --}}
    <form id="rzp-return" action="{{ route('payment.callback', $order->id) }}" method="POST">
        @csrf
        <input type="hidden" name="razorpay_payment_id" id="razorpay_payment_id">
        <input type="hidden" name="razorpay_order_id"   id="razorpay_order_id">
        <input type="hidden" name="razorpay_signature"  id="razorpay_signature">
    </form>

    <script>
        var options = {
            "key": "{{ $keyId }}",
            "amount": "{{ $rzpOrder['amount'] }}",
            "currency": "INR",
            "name": "{{ config('app.name') }}",
            "description": "Order #{{ $order->id }}",
            "order_id": "{{ $rzpOrder['id'] }}",
            "prefill": {
                "name": "{{ $order->name }}",
                "email": "{{ $order->email }}",
                "contact": "{{ $order->phone }}"
            },
            "theme": { "color": "#3399cc" },
            "handler": function (response) {
                document.getElementById('razorpay_payment_id').value = response.razorpay_payment_id;
                document.getElementById('razorpay_order_id').value   = response.razorpay_order_id;
                document.getElementById('razorpay_signature').value  = response.razorpay_signature;
                document.getElementById('rzp-return').submit();
            },
            "modal": {
                "ondismiss": function () {
                    // User ne popup band kar diya → wapas checkout
                    window.location.href = "{{ route('checkout.index') }}";
                }
            }
        };
        var rzp = new Razorpay(options);
        rzp.open();
    </script>
</body>
</html>