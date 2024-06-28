<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Perdidos&Achados</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
  </head>
  <body>
    <header>
      @if (auth()->check())
        @include('components.navbar')
      @else
        @if(Auth::guard('police')->check())
        @include('components.navbar-police')
      
        @else
        @include('components.navbar-guest')
      @endif 
      @endif
    </header>
    @if (auth()->check() || Auth::guard('police')->check() )
    <div class="container">
      <div class="row align-items-end">
        <div class="col-md-auto">
            @if (Auth::guard('police')->check() )
            <h2>Bem vindo, {{Auth::guard('police')->user()->name}}!</h2>
            @endif
            @if(auth()->check()) 
            <h2>Bem vindo, {{auth()->user()->name}}!</h2>
            @endif
        </div>
        <div class="row">
          <div class="col">
            <h4>Por favor escolhe uma opção:</h4>
          </div>
      @if (auth()->check())
        <div class="col-md-auto">
          <div class="text-center">
            <a role="button" href="{{ route('lost-objects.register-form') }}" class="btn btn-primary btn-lg btn-block mb-3">Registar um objeto</a>
          </div>
        </div>
        <div class="col-md-auto">
          <div class="text-center">
            <a role="button" href="{{ route('user.profile', auth()->user()->id) }}" class="btn btn-secondary btn-lg btn-block mb-3">Ver os seus objetos</a>
          </div>
        </div>
      </div>
      @endif
    </div>
    @else
    <div class="container">
      <div class="row align-items-end">
        <div class="col-md-auto">
            <h2>Bem vindo!</h2>
        </div>
      </div>
        <div class="row">
          <div class="col">
            <h4>Por favor escolha uma opção:</h4>
          </div> 
        <div class="col-md-auto">
          <div class="text-center">
            <a role="button" href="http://localhost:8000/chooseaccounttype" class="btn btn-primary btn-lg btn-block mb-3">Criar nova conta de utilizador</a>
          </div>
        </div>
        <div class="col-md-auto">
          <div class="text-center">
            <a role="button" href="http://localhost:8000/login" class="btn btn-secondary btn-lg btn-block mb-3">Iniciar sessão como utilizador</a>
          </div>
        </div>
        <div class="col-md-auto">
          <div class="text-center">
            <a role="button" href="http://localhost:8000/loginpolice" class="btn btn-secondary btn-lg btn-block mb-3">Iniciar sessão como policia</a>
          </div>
        </div>
      </div>
    </div>
    
    @endif
    @include('content-home')
    @include('components.footer')
    {{-- JQuery --}}
    <script 
      src="https://code.jquery.com/jquery-3.6.0.min.js" 
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
      crossorigin="anonymous"
    ></script>
    {{-- Popper --}}
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
      integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
      crossorigin="anonymous"
    ></script>
    {{-- Bootstrap --}}
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
      integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
      crossorigin="anonymous"
    ></script>
    {{-- <script>
    function redirect(url)
    {
    window.location(url);
    }
    </script> --}}
  </body>
</html>

