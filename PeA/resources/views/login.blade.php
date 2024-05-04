<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
  <form action="{{route('user.login')}}" method="POST">
    @csrf
  Email <input type="text" name="email">
 
  password <input type="password" name="password">
  <button>Login:</button>
  </form>
</body>
</html>
