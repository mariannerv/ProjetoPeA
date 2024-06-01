@if (auth()->check())

<<<<<<< HEAD
@if(auth()->user()->email == "admin@teste.pt")
=======
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
    <h2>Tabela de Utilizadores</h2>

    <table>
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
    <button id="test">teste</button>
    <a href="{{route('usersactive.store')}}"><button>Mostrar Utilizadores Ativos</button></a>
    <a href="{{route('usersdeactivated.store')}}"><button>Mostrar Utilizadores Destivos</button></a>
    <h2>Tabela de Utilizadores</h2>

    <table id="usertabel">
>>>>>>> fc56948-gabriel
        <thead>
            <tr>
                <th>Nome</th>
                <th>Género</th>
                <th>Nº Telefone</th>
                <th>Email</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->gender }}</td>
                <td>{{ $user->contactNumber }}</td>
                <td>{{ $user->email }}</td>
                <td class="action-buttons">
<<<<<<< HEAD
                    <form method="post" action="{{ route('user.confirm-delete', $user->id) }}" style="display: inline;">
                        @csrf
                        <button type="submit">Eliminar</button>
                    </form>
                    
                    <form method="get" action="{{ route('user.edit', $user->id) }}" style="display: inline;">
                        @csrf
    
                        <button type="submit">Editar</button>
                    </form>
=======
                    @if($user->account_status == 'active')
                    <form method="post" action="{{ route('user.desactive', $user->id) }}" id="form-desactive-{{ $user->id }}" style="display: inline;">
                        @csrf
                        <button type="button" onclick="confirmDeactivation('{{ $user->id }}')">Desativar</button>               
                    </form>
                    @else
                    <form method="post" action="{{ route('user.useractive', $user->id) }}" id="form-active-{{ $user->id }}" style="display: inline;">
                        @csrf
                        <button type="button" onclick="confirmActivation('{{ $user->id }}')">Ativar</button>               
                    </form>
                    @endif
>>>>>>> fc56948-gabriel
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
<<<<<<< HEAD
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
>>>>>>> fc56948-gabriel
</body>
</html>
@else
<h1>Area Administrativa</h1>
@endif

@else
<h1>Area Administrativa</h1>
@endif
<<<<<<< HEAD


=======
>>>>>>> fc56948-gabriel
