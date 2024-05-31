<!-- resources/views/users.blade.php -->
@if (auth()->check())

@if(auth()->user()->admin == "true")

<!DOCTYPE html>
<html>
    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.css" rel="stylesheet">
 
    <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.js"></script>
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<head>


    <title>Tabela de Estaçoes</title>
   
</head>
<body>
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

</body>
</html>
@else
<h1>Area Administrativa</h1>
@endif

@else
<h1>Area Administrativa</h1>
@endif
