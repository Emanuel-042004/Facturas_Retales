<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	

	<link rel="stylesheet" href="{{ asset('css/auth.css') }}">
	<title>Login Page</title>
</head>
<body>
<div class="wrapper">
      <div class="title-text">
        <div class="title login">Iniciar Sesion</div>
        
      </div>
      <div class="form-container">
       
        <div class="form-inner">
          
        <form method="POST" action="{{ route('login') }}">
             @csrf
            <div class="field">
            
            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" placeholder="Correo Electronico" required autocomplete="email" autofocus>

            @error('email')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
            
            </div>
            
            <div class="field">
            
            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Contraseña" required autocomplete="current-password">

              @error('password')
                  <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                  </span>
              @enderror
            </div>
            <div class="field btn">
              <div class="btn-layer"></div>
              <input type="submit" value="Login">
            </div>
          </form>
        
            
        </div>
      </div>
    </div>

</body>



</html>