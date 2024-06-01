<<<<<<< HEAD
=======
@if (auth()->check())

@if(auth()->user()->admin == "true")
>>>>>>> fc56948-gabriel
<!-- resources/views/users.blade.php -->

<!DOCTYPE html>
<html>
<<<<<<< HEAD
<head>
    <title>Tabela de Utilizadores</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-buttons {
            display: flex;
            gap: 5px;
        }
    </style>
</head>
<body>
    <h2>Tabela de Policias</h2>

    <table>
        <thead>
            <tr>
                <th>Nome</th>
                <th>ID Interno</th>
                <th>Estação</th>
                <th>Eliminar</th>

=======

<link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.css" rel="stylesheet">
 
<script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<head>
    <title>Tabela de Utilizadores</title>
</head>

<body>
    <h2>Numero de Utilizadores: {{$numberusers}} </h2>
    <h2>Numero de Utilizadores ativos {{$numberactive}} </h2>
    <h2>Numero de Utilizadores Desativos: {{$deactivated}} </h2>
    <a href="{{route('policesactive.store')}}"><button>Mostrar policias Ativos</button></a>
    <a href="{{route('policesdeactivated.store')}}"><button>Mostrar policias Destivos</button></a>
    <h2>Tabela de policias</h2>

    <table id="usertabel">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Id internoi</th>
                <th>Estação</th>
                <th>Ação</th>   
>>>>>>> fc56948-gabriel
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->internalId }}</td>
                <td>{{ $user->policeStationId }}</td>
<<<<<<< HEAD
                
                <td class="action-buttons">
                    <form method="post" action="{{ route('police.destroy', $user->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                    <form method="get" action="{{ route('police.edit', ['user' => $user->_id]) }}" style="display: inline;">
                      @csrf
                      
                      <button type="submit">Editar</button>
                  </form>
                </td>

                
=======
                <td class="action-buttons">
                    @if($user->account_status == 'active')
                    <form method="post" action="{{ route('police.desactive', $user->id) }}" id="form-desactive-{{ $user->id }}" style="display: inline;">
                        @csrf
                        <button type="button" onclick="confirmDeactivation('{{ $user->id }}')">Desativar</button>               
                    </form>
                    @else
                    <form method="post" action="{{ route('police.useractive', $user->id) }}" id="form-active-{{ $user->id }}" style="display: inline;">
                        @csrf
                        <button type="button" onclick="confirmActivation('{{ $user->id }}')">Ativar</button>               
                    </form>
                    @endif
                </td>
>>>>>>> fc56948-gabriel
            </tr>
            @endforeach
        </tbody>
    </table>
<<<<<<< HEAD
</body>
</html>
=======

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
                    }, 2000); // Delay of 5 seconds
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
                    }, 2000); // Delay of 5 seconds
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
>>>>>>> fc56948-gabriel
