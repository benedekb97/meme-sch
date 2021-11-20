<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="UTF-8"/>
</head>
<body>
    Főoldalxd
    @if (Auth::check())
        be vagy jelentkezve<br>
        <a href="{{ route('auth.logout') }}">Kijelentkezés</a>
    @else
        nem vagy bejelentkezve<br>
        <a href="{{ route('auth.redirect') }}">Bejelentkezés</a>
    @endif
</body>
</html>
