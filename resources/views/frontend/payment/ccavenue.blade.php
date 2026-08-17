<!DOCTYPE html><html><head><meta charset="utf-8"><title>Redirecting to CCAvenue...</title></head>
<body onload="document.ccaForm.submit();">
    <p style="text-align:center;margin-top:60px;font-family:sans-serif;">Redirecting to CCAvenue...</p>
    <form name="ccaForm" method="POST" action="{{ $action }}">
        <input type="hidden" name="encRequest" value="{{ $encRequest }}">
        <input type="hidden" name="access_code" value="{{ $accessCode }}">
    </form>
</body></html>