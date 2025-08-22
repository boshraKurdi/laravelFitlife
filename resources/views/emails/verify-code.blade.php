{{-- resources/views/emails/verify-code.blade.php --}}
<!doctype html>
<html lang="ar" dir="rtl">

<body>
    <h2>welcome {{ $user->name }}</h2>
    <p>Your email confirmation code is:</p>
    <div style="font-size:24px;font-weight:bold;letter-spacing:4px">{{ $code }}</div>
    <p>Its validity is 10 minutes.</p>
    <p>If you don't ask, ignore the message.</p>
</body>

</html>
