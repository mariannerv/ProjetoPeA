<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Document</title>
</head>
<body>
ola mund

@if(Auth::guard('police')->check())
<form  method="GET" action="{{route('polices.logout')}}">
  @csrf
<button>Logout Policia</button>
</form>
@endif

@if (auth()->check())
Utilizador Normal

@endif
</body>
</html>