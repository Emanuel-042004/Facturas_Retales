<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Gestión de Facturas</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,600&display=swap" rel="stylesheet" />
    <style>
        /* Agregado para personalizar el estilo */
        body {
            font-family: 'Figtree', sans-serif;
            background-color: #f3f4f6;
            color: #1c1c1e;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            text-align: center;
        }
        .welcome-message {
            font-size: 2rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .description {
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .btn-container {
            display: flex;
            justify-content: center;
        }
        .btn {
            margin: 0.5rem;
            padding: 1rem 2rem;
            font-size: 1rem;
            text-decoration: none;
            border-radius: 0.5rem;
            background: -webkit-linear-gradient(left,#660000,#800000,#b30000
, #e60000);
            color: #ffffff;
            transition: background-color 0.3s;
        }
        .btn:hover {
            background: #e0190a;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="welcome-message">Bienvenidos a la Gestión de Facturas</div>
        <div class="description">Una plataforma para gestionar tus facturas de manera eficiente.</div>
        <div class="btn-container">
            @if (Route::has('login'))
                @auth
                    <a href="{{ url('/pendientes') }}" class="btn">Home</a>
                @else
                    <a href="{{ route('login') }}" class="btn">Iniciar Sesión</a>

                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="btn">Registrarse</a>
                    @endif
                @endauth
            @endif
        </div>
    </div>
</body>
</html>
