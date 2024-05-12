@if (auth()->check())

@if(auth()->user()->email == "admin@teste.pt")
<!-- resources/views/users.blade.php -->

<!DOCTYPE html>
<html>

<link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.css" rel="stylesheet">
 
<script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.js"></script>



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
                    @if($user->account_status == 'active')
                    <form method="post" action="{{ route('user.desactive', $user->id) }}" id="formactive" style="display: inline;">
                        @csrf
                        <button type="submit">Destivar</button>               
                    </form>
                    @else
                    <form method="post" action="{{ route('user.useractive', $user->id) }}" style="display: inline;">
                        @csrf
                        <button type="submit">Ativar</button>               
                    </form>
                    @endif
                    
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <script>
        let table = new DataTable('#usertabel');
    
    
    </script>
</body>
</html>
@else
<h1>Area Administrativa</h1>
@endif

@else
<h1>Area Administrativa</h1>
@endif


