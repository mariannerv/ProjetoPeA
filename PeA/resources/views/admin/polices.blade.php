@if (auth()->check())

@if(auth()->user()->admin == "true")
<!-- resources/views/users.blade.php -->

<!DOCTYPE html>
<html>

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
            </tr>
        </thead>
        <tbody>
            @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->internalId }}</td>
                <td>{{ $user->policeStationId }}</td>
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
