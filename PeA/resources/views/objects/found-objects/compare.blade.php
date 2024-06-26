<?php
if (!Auth::guard('police')->check() && !auth()->check()) {
    header('Location: ' . route('home'));
    exit;
}
?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  </head>
  <body>
    <header>
        @if (auth()->check())
        @include('components.navbar-guest')
      @else
        @include('components.navbar-police')
      @endif 
  </header>
    <br><br>
    @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
  @endif

  @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    <h1>Estes objetos têm {{$compare}}% de semelhança</h1>
  <br>
    
    <div class="container border">
        <h4>Ojeto encontado</h4>
        <div class="row">
            <div class="col align-self-center">
                <img src="{{ asset('images/found-objects-img/' . $foundObjects->image) }}" alt="image representing there is no image" class="img-fluid">
            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <h2>{{ $foundObjects->category }}</h2>
                        <p> 
                            {{$foundObjects->brand}}; 
                            {{$foundObjects->color}};
                          
                        </p>
                    </div>
                </div>
                    <div class="col">
                        <p>Objeto perdido: {{ $foundObjects->description }}</p>

                        
                    </div>  
                <div class="row">
                    <div class="col">
                        <p>Data de aparecimento:: {{ $foundObjects->date_found }}</p>
                    </div>
                </div>
                
            
            </div>
        </div>
        <br>
    </div>

<br>

    <div class="container border">
        <h4>Ojeto perdido</h4>
        <div class="row">
          <div class="col align-self-center">
              <img src="{{ asset('images/lost-objects-img/' . $lostObjects->image) }}" alt="image representing there is no image" class="img-fluid">
          </div>
          <div class="col">
              <div class="row">
                  <div class="col">
                      <h2>{{ $lostObjects->category }}</h2>
                      <p> 
                          {{$lostObjects->brand}}; 
                          {{$lostObjects  ->color}};
                        
                      </p>
                  </div>
              </div>
                  <div class="col">
                      <p>Objeto perdido: {{ $lostObjects->description }}</p>

                      
                  </div>  
              <div class="row">
                  <div class="col">
                      <p>Data da perda:: {{ $lostObjects->date_lost }}</p>
                  </div>
              </div>
              
          
          </div>
      </div>
      <br>
     
  </div>
  <br>
  <?php

  $id = False;
  
  // Verifique se a propriedade 'possible_owner' existe e se é um array ou objeto
  if (isset($foundObjects->possible_owner) && (is_array($foundObjects->possible_owner) || is_object($foundObjects->possible_owner))) {
      foreach ($foundObjects->possible_owner as $owner) {
          if ($owner['lostObjectid'] == $lostObjects->_id) {
              $id = True;
          }
      }
  }
  ?>
  
  <?php
  if ($id == False) {
  ?>
 <?php
      if (Auth::guard('police')->check()) {

      
 ?>
      <form method="POST" action="{{ route('addowner.objects', [$foundObjects, $lostObjects]) }}">
          @csrf
          <button class="btn btn-primary">adicionar possivel owner</button>
      </form>

     <?php
   
    }
    else {

    
?>
       <form method="POST" action="{{ route('adduser.objects', [$foundObjects, $lostObjects]) }}">
        @csrf
        <button class="btn btn-primary">Notificar policia</button>
    </form>
<?php
}
  }
  else {
    echo "<h3>Possivel dono $lostObjects->ownerEmail  já registrado</h3>";
  }
  ?>
   <button class="btn btn-secondary" onclick="goBack()">Voltar</button>
  
    @include('components.footer')
    <script 
      src="https://code.jquery.com/jquery-3.6.0.min.js" 
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
      crossorigin="anonymous"
    ></script>

    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
      integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
      integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
      crossorigin="anonymous"
    ></script>

    <script>
      
        function goBack() {
            window.history.back();
        }
      

    </script>