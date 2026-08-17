<!DOCTYPE html><html><head><meta charset="utf-8"><title>Redirecting to PayU...</title></head>
<body onload="document.payuForm.submit();">
    <p style="text-align:center;margin-top:60px;font-family:sans-serif;">Redirecting to PayU, please wait...</p>
    <form name="payuForm" action="{{ $action }}" method="POST">
        <input type="hidden" name="key"         value="{{ $key }}">
        <input type="hidden" name="txnid"       value="{{ $txnid }}">
        <input type="hidden" name="amount"      value="{{ $amount }}">
        <input type="hidden" name="productinfo" value="{{ $productinfo }}">
        <input type="hidden" name="firstname"   value="{{ $firstname }}">
        <input type="hidden" name="email"       value="{{ $email }}">
        <input type="hidden" name="phone"       value="{{ $phone }}">
        <input type="hidden" name="surl"        value="{{ route('payment.callback', $order->id) }}">
        <input type="hidden" name="furl"        value="{{ route('payment.callback', $order->id) }}">
        <input type="hidden" name="hash"        value="{{ $hash }}">
    </form>
</body></html>