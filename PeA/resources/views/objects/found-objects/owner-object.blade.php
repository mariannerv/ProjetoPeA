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
    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.css" rel="stylesheet">
 
    <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.js"></script>
    

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  </head>
  <body>
    @if (!Auth::guard('police')->check())
        <?php
            header('Location: ' . route('home'));
            exit;
        ?>
    @endif

    <header>
      @include('components.navbar-police')
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
                        <p>Data de aparecimento:: {{ $object->date_found }}</p>
                    </div>
                </div>
            </div>

           
        </div>

          <!-- Verifique se o possible_owner é um array -->
     @if (is_array($object->possible_owner))
     <table id="usertabel" class="table table-striped" style="width:100%">
       <thead>
           <tr>
               <th>Possível dono</th>
               <th>Match</th>
               <th>Notificar</th>
           </tr>
       </thead>
       <tbody>
            @foreach ($object->possible_owner as $owner)
           <tr>
               <!-- Acesse os atributos do objeto diretamente -->
               <td>{{ $owner['owner'] ?? '' }}</td>
               <td>{{ $owner['match'] ?? '' }}</td>
               <td>Notificar</td>
           </tr>
           @endforeach
       </tbody>
     </table>
   @endif
    </div>

   
 
        @include('components.footer')

        <!-- Scripts -->

        <!-- Função de Desativação -->
        <script>
          let table = new DataTable('#usertabel');
          
        </script>


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



  </body>
</html>
