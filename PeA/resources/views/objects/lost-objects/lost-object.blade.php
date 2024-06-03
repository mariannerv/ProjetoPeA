<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    @if (auth()->check())
    <title>{{auth()->user()->name}}</title>
    @else
    <title>Sem acesso</title>
    @endif
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  </head>
  <body>
    <header>
      @if (auth()->check())
        @include('components.navbar')
      @else
        @include('components.navbar-guest')
      @endif 
      
    </header>
    <br><br>
    <div class="container border">
        <div class="row">
            <div class="col align-self-center">
                <img src="../images/Missing-image.png" alt="image representing there is no image" class="img-fluid">
            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <h2>{{ $object->category }}</h2>
                        <p> 
                            {{$object->brand}}; 
                            {{$object->color}};
                            @if ($object->size)
                                Tamanho {{$object->size}}
                            @endif
                        </p>
                    </div>
                </div>
                    <div class="col">
                        <p>Objeto perdido: {{ $object->description }}</p>

                        
                    </div>
                <div class="row">
                    <div class="col">
                        <p>Data de perda: {{ $object->date_lost }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p>Status: {{$object->status}} </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        @if (auth()->user()->email === $object->ownerEmail )
                        <form class="row g-3 needs-validation" novalidate action="{{ route('lost-object.edit', ['object' => $object->_id, 'lostObject' => $object]) }}" method="get">
                        <button  type="submit" class="btn btn-primary">Editar objeto</button>
                        </form>
                        <button class="btn btn-danger">Eliminar objeto</button>
                        @else
                        <button class="btn btn-primary">Encontrei</button> 
                        @endif
                        {{-- Este botão vai servir como notificação de possivel dono --}}
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>
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