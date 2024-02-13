<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<!DOCTYPE html>
<html lang="es">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Afacad&family=Roboto+Condensed&display=swap" rel="stylesheet">
  <title>Gestión de Facturas</title>

</head>

<body>
<ul class="nav nav-underline">
<li class="nav-item">
      <a class="nav-link text-dark" href="{{route('pendientes.index')}}">Todas</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-dark" aria-current="page" href="{{ url()->current() . '?area=Compras' }}">Compras</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-dark" href="{{ url()->current() . '?area=Financiera' }}">Financiera</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-dark" href="{{ url()->current() . '?area=Logistica' }}">Logística</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-dark" href="{{ url()->current() . '?area=Mantenimiento' }}">Mantenimiento</a>
    </li>
    <li class="nav-item">
      <a class="nav-link text-dark" href="{{ url()->current() . '?area=Tecnologia' }}">Tecnología</a>
    </li>
  </ul>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
