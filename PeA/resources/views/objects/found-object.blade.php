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
    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.css" rel="stylesheet">
 
    <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.js"></script>
    
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>



  </head>
  <body>
    <header>
      @include('components.navbar-police')
  </header>
    <br>
    @if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif
    <br>

    <div class="container border">
        <div class="row">
            <div class="col align-self-center">
                <img src="{{ asset('images/found-objects-img/' . $object->image) }}" alt="image representing there is no image" class="img-fluid">


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
                
                <div class="row">
                    <div class="col">
                        @if (Auth::guard('police')->user()->policeStationId === $object->estacao_policia )
                        <form class="row g-3 needs-validation" novalidate action="{{ route('found-object.edit', $object->_id) }}" method="get">
                        <button  type="submit" class="btn btn-primary">Editar objeto</button>
                        </form>
                        <form method="post" action="{{ route('found-object2.delete', $object->_id) }}" id="form-desactive-{{ $object->_id }}">
                          @csrf
                          <button class="btn btn-danger"  type="button" onclick="confirmDeactivation('{{ $object->_id }}')">Eliminar objeto</button>               
                      </form>
                        
                        @else
                        <button class="btn btn-primary">Encontrei</button> 
                        @endif
                        {{-- Este botão vai servir como notificação de possivel dono --}}
                    </div>
                </div>
            </div>
            @if (is_array($object->possible_owner))
            <table id="usertabel" class="table table-striped" style="width:100%">
              <thead>
                  <tr>
                      <th>Possível dono</th>
                      <th>Match</th>
                      <th>Ver detalhes</th>
                      <th>Notificar</th>
                      <th>Remover</th>
                      
                  </tr>
              </thead>
              <tbody>
                   @foreach ($object->possible_owner as $owner)
                  <tr>
                      <!-- Acesse os atributos do objeto diretamente -->
                      <td>{{ $owner['owner'] ?? '' }}</td>
                      <td>{{ $owner['match'] ?? '' }}%</td>
                      <td><a href="{{route('compare.objects' , [$object->_id , $owner['lostObjectid']  ?? '' ])}}"><button class="btn btn-primary" >Ver detalhes</button></a></td>
                      <td><a href="{{route('notify.owner' , [$object ,$owner['lostObjectid'] ?? '' , $owner['owner']])}}"><button class="btn btn-primary" >Notificar</button></a></td>
                    <td>  <form method="get" action="{{ route('remove.owner' , [$object ,$owner['lostObjectid'] ?? ''])}}" id="form-desactive-{{ $owner['lostObjectid']  }}" style="display: inline;">
                        @csrf
                        <button class="btn btn-danger"  type="button" onclick="confirmDeactivationuser('{{$owner['lostObjectid'] }}')">remover</button>               
                    </form></td>
                  </tr>
                  @endforeach
              </tbody>
            </table>
          @endif


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

    <script>
          let table = new DataTable('#usertabel');
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
                text: "Voce quer remover este objeto?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sim, remover",
                cancelButtonText: "Não, cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire({
                        title: "Destivado!",
                        text: "objeto removido.",
                        icon: "success"
                    });
                    setTimeout(() => {
                        document.getElementById('form-desactive-' + userId).submit();
                    }, 1000); // Delay of 5 seconds
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado!",
                        text: "Operação destivada.",
                        icon: "error"
                    });
                }
            });
        }

        function confirmDeactivationuser(userId) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "Tem a certeza?",
                text: "Voce quer remver este utilizador?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sim, remover",
                cancelButtonText: "Não, cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire({
                        title: "Destivado!",
                        text: "Utilizador removido.",
                        icon: "success"
                    });
                    setTimeout(() => {
                        document.getElementById('form-desactive-' + userId).submit();
                    }, 1000); // Delay of 5 seconds
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