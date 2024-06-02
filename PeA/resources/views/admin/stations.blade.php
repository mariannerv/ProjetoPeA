<!-- resources/views/users.blade.php -->
@if (auth()->check())

@if(auth()->user()->admin == "true")

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
    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.css" rel="stylesheet">
 
<script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>


  </head>
    <title>Tabela de Estaçoes</title>
   
</head>
<body>
    <header>
  
        @include('components.navbar')
      
    </header>

    <h2>Tabela de Estacoes</h2>

    <table id="usertabel">
        <thead>
            <tr>
                <th>Morada</th>
                <th>localidade</th>
                <th>unidade</th>
                <th>Email</th>
                <th>Eliminar</th>

            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->morada }}</td>
                <td>{{ $user->localidade }}</td>
                <td>{{ $user->unidade }}</td>
                <td>{{ $user->email }}</td>
                
                <td class="action-buttons">
                    <form method="post" action="{{ route('policestation.destroy', $user->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                    <form method="get" action="{{ route('station.edit', ['user' => $user->_id]) }}" style="display: inline;">
                      @csrf
                      <button type="submit">Editar</button>
                  </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

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

        function confirmActivation(userId) {
            const swalWithBootstrapButtons = Swal.mixin({
                customClass: {
                    confirmButton: "btn btn-success",
                    cancelButton: "btn btn-danger"
                },
                buttonsStyling: false
            });

            swalWithBootstrapButtons.fire({
                title: "Tem a certeza?",
                text: "Voce quer ativar esta conta?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Sim, ativar",
                cancelButtonText: "Não, cancelar!",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    swalWithBootstrapButtons.fire({
                        title: "Conta ativada!",
                        text: "Conta ativada com suceso.",
                        icon: "success"
                    });
                    setTimeout(() => {
                        document.getElementById('form-active-' + userId).submit();
                    }, 3000); // Delay of 5 seconds
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    swalWithBootstrapButtons.fire({
                        title: "Cancelado!",
                        text: "Operação cancelada!",
                        icon: "error"
                    });
                }
            });
        }
    </script>


@include('components.footer')
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
@else
<h1>Area Administrativa</h1>
@endif

@else
<h1>Area Administrativa</h1>
@endif
