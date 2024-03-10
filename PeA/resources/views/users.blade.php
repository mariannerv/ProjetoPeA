<!DOCTYPE html>
<html>
<style>
table, th, td {
  border:1px solid black;
}
</style>
<body>

<h2>Tabela de Utilizadores</h2>

<table style="width:100%">
  <tr>
    <th>Nome</th>
    <th>Genero</th>
    <th>Nº Telefone</th>
    <th>email</th>
    <th>acao</th>
  </tr>
  @foreach ($users as $user)
  <tr>
  <td>{{$user->name}}</td>
  <td>{{$user->gender}}</td>
  <td>{{$user->contactNumber}}</td>
  <td>{{$user->email}}</td>
  <td ><button>Eliminar</button> <button>Editar</button></td>
</tr>
  @endforeach
</table>


</body>
</html>
