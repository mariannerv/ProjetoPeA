<!-- resources/views/users.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Tabela de Utilizadores</title>
    <style>
        table, th, td {
            border: 1px solid black;
        }
    </style>
</head>
<body>
    <h2>Tabela de Utilizadores</h2>

    <table style="width:100%">
        <tr>
            <th>Nome</th>
            <th>Género</th>
            <th>Nº Telefone</th>
            <th>Email</th>
            <th>Ação</th>
        </tr>
        @foreach ($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->gender }}</td>
                <td>{{ $user->contactNumber }}</td>
                <td>{{ $user->email }}</td>
                <td>
                    <form method="post" action="{{ route('users.delete', $user->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                    <a href="{{ route('users.edit', $user->id) }}"><button>Editar</button></a>
                </td>
            </tr>
        @endforeach
    </table>
</body>
</html>
