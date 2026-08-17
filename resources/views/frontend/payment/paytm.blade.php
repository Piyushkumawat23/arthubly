<!DOCTYPE html><html><head><meta charset="utf-8"><title>Redirecting to Paytm...</title></head>
<body onload="document.paytmForm.submit();">
    <p style="text-align:center;margin-top:60px;font-family:sans-serif;">Redirecting to Paytm...</p>
    <form name="paytmForm" method="POST" action="{{ $url }}">
        <input type="hidden" name="mid"      value="{{ $mid }}">
        <input type="hidden" name="orderId"  value="{{ $orderId }}">
        <input type="hidden" name="txnToken" value="{{ $token }}">
    </form>
</body></html>