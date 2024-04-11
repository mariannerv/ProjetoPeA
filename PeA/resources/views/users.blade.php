<!-- resources/views/users.blade.php -->

<!DOCTYPE html>
<html>
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
                    <form method="post" action="{{ route('user.destroy', $user->id) }}" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Eliminar</button>
                    </form>
                    <form method="get" action="{{ route('user.edit', $user->id) }}" style="display: inline;">
                        @csrf
    
                        <button type="submit">Editar</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
