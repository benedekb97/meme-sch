<!DOCTYPE html>
<html lang="hu">
    <head>
        <title>Bejelentkezés</title>
        <meta charset="UTF-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
        <link rel="stylesheet" href="{{ asset('css/login.css') }}"/>
    </head>
    <body class="text-center">
        <main class="form-login">
            <form>
                <h1 class="h3 mb-6 fw-normal">Kérlek jelentkezz be!</h1>
                <p class="p-12 mb-6 mt-3 text-opacity-50">A tartalom megtekintéséhez szükséges az AuthSCH-s bejelentkezés.</p>
                <div class="checkbox mb-3 mt-3">
                    <a href="{{ route('auth.redirect') }}" class="w-100 btn btn-lg btn-primary">
                        <img style="width:25px; margin-right:3px; bottom:3px; position:relative;" src="{{ asset('img/logo_vektor.png') }}" alt="SCH"> Bejelentkezés
                    </a>
                </div>
            </form>
        </main>
    </body>
</html>
