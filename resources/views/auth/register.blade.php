@extends('layouts.app')

@section('content')
<link rel="stylesheet" href="{{ asset('css/register.css') }}">
<div class="container">
  <div class="card-header">{{ __('Registro') }}</div>
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
        <label for="name" class="datails">{{ __('Name') }}</label>
        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
          value="{{ old('name') }}" required autocomplete="name" autofocus>
        @error('name')
        <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
        </span>
        @enderror
      </div>

      <div class="input_pox">
        <label for="email" class="datails">{{ __('Email Address') }}</label>
        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
          value="{{ old('email') }}" required autocomplete="email">
        @error('email')
        <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
        </span>
        @enderror
      </div>

      <div class="input_pox">
        <label for="password" class="datails">{{ __('Password') }}</label>
        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
          name="password" required autocomplete="new-password">
        @error('password')
        <span class="invalid-feedback" role="alert">
          <strong>{{ $message }}</strong>
        </span>
        @enderror
      </div>

      <div class="input_pox">
        <label for="password-confirm" class="datails">{{ __('Confirm Password') }}</label>
        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required
          autocomplete="new-password">
      </div>
    </div>

    <div class="button">
      <input type="submit" value="Register">
    </div>
  </form>
</div>

@endsection