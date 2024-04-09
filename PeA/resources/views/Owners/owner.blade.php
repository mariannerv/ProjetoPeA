<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner</title>
</head>
<body>
    <h1>{{$owner->name}}</h1>
</body>
</html><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Owner</title>
</head>
<body>
    <h1>{{$owner->name}}</h1>

    @if($user = App\User::where('name', $owner->name)->first())
        <p>User Found:</p>
        <ul>
            <li>Nome: {{$user->name}}</li>
            <li>Género: {{$user->gender}}</li>
            <li>Contacto: {{$user->contactNumber}}</li>
            <li>Email: {{$user->email}}</li>
            
        </ul>
    @else
        <p>No matching user found.</p>
    @endif
</body>
</html>
