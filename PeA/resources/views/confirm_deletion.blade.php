<!-- resources/views/confirm_deletion.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Confirm Delete</title>
</head>
<body>
    <h2>Confirm Delete</h2>
    <p>Please confirm deletion of user {{ $user->name }} by entering your password:</p>
    <form method="post" action="{{ route('user.destroy', $user->id) }}">
        @csrf
        @method('DELETE')
        <input type="password" name="password" placeholder="Your Password" required>
        <button type="submit">Confirm Delete</button>
    </form>
    
</body>
</html>
