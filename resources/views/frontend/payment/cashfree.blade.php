<!DOCTYPE html><html><head><meta charset="utf-8"><title>Redirecting to Cashfree...</title>
<script src="https://sdk.cashfree.com/js/v3/cashfree.js"></script></head>
<body>
    <p style="text-align:center;margin-top:60px;font-family:sans-serif;">Opening Cashfree checkout...</p>
    <script>
        const cashfree = Cashfree({ mode: "{{ $mode === 'live' ? 'production' : 'sandbox' }}" });
        cashfree.checkout({
            paymentSessionId: "{{ $sessionId }}",
            redirectTarget: "_self"
        });
    </script>
</body></html>