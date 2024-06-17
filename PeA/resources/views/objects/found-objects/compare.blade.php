<?php
if (!Auth::guard('police')->check()) {
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
      @include('components.navbar-police')
  </header>
    <br><br>

    <h1>Este objeto tem {{$compare}}%</h1>

    <h2>Ojeto encontado</h2>
    <div class="container border">
        <div class="row">
            <div class="col align-self-center">
                <img src="../images/Missing-image.png" alt="image representing there is no image" class="img-fluid">
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
<h2>Ojeto perdido</h2>
    <div class="container border">
      <div class="row">
          <div class="col align-self-center">
              <img src="../images/Missing-image.png" alt="image representing there is no image" class="img-fluid">
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
                      <p>Data de aparecimento:: {{ $lostObjects->date_lost }}</p>
                  </div>
              </div>
              
          
          </div>
      </div>
      <br>
      <form method="POST" action="{{route('addowner.objects' , [$foundObjects ,$lostObjects] )}}">
        @csrf
        <button>adicionar possivel owner</button>
      </form>
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

    <script>
       function confirmDeactivation(userId) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "Tem a certeza?",
                text: "Voce tem a certeza que quer destivar esta conta?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sim, destivar",
                cancelButtonText: "Não, cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire({
                        title: "Destivado!",
                        text: "Utilizador destivado.",
                        icon: "success"
                    });
                    setTimeout(() => {
                        document.getElementById('form-desactive-' + userId).submit();
                    }, 3000); // Delay of 5 seconds
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado!",
                        text: "Operação destivada.",
                        icon: "error"
                    });
                }
            });
        }



    </script>