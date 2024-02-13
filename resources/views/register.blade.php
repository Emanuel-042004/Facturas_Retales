@extends('layouts.app')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ asset('css/register.css') }}">
  <title>Registro</title>
</head>

<body>
  <div class="container">
    <div class="title">Registro</div>
    <form method="POST" action="{{ route('register') }}">
      @csrf
      <div class="user_details">
        <div class="input_pox">
          <span class="datails">Area</span>
          <select id="area" name="area" required>
            <option value="" disabled selected>Selecciona</option>
            <option value="Compras">Compras</option>
            <option value="Financiera">Financiera</option>
            <option value="Logistica">Logistica</option>
            <option value="Mantenimiento">Mantenimiento</option>
            <option value="Tecnologia">Tecnologia</option>
          </select>
        </div>
        <div class="input_pox">
          <span class="datails">Nombre</span>
          <input type="text" placeholder="Nombre de Usario" class=" @error('name') is-invalid @enderror" name="name"
            placeholder="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
          @error('name')
          <span class="invalid-feedback" role="alert">
            <strong>{{ $message }}</strong>
          </span>
          @enderror
        </div>

        <div class="input_pox">
          <span class="datails">Email</span>
          <input type="text" placeholder="example@losretales.co" class="  @error('email') is-invalid @enderror"
            name="email" value="{{ old('email') }}" placeholder="Email" required autocomplete="email">
          @error('email')
          <span class="invalid-feedback" role="alert">
            <strong>Este email ya ha sido registrado</strong>
          </span>
          @enderror
        </div>
        <div class="input_pox">
          <span class="datails">Contraseña</span>
          <input type="password" placeholder="Contraseña" class="@error('password') is-invalid @enderror"
            name="password" required autocomplete="new-password">
          @error('password')
          <span class="invalid-feedback" role="alert">
            <strong>Las contraseñas no coinciden</strong>
          </span>
          @enderror
        </div>

        <div class="input_pox">
          <span class="datails">Confirmar Contraseña</span>
          <input id="password-confirm" type="password" name="password_confirmation" placeholder="Confirmar Contraseña"
            required autocomplete="new-password">
          @error('password_confirmation')
          <span class="invalid-feedback" role="alert">
            <strong>Las contraseñas no coinciden</strong>
          </span>
          @enderror
        </div>

      </div>

      <div class="button">
        <input type="submit" value="Register">
      </div>
    </form>
  </div>
</body>

</html>
@endsection