<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <form method="POST" action="{{route('polices.login')}}" >
  @csrf
  ID interno: <input type="text" name="internalId">
  password <input type="password" name="password">
  <button>Login</button>
  </form>
</body>
</html>