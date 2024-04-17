<!-- login.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <div>
        <h2>Login</h2>
        <!-- Form for user login -->
        <form method="POST" action="{{ route('login') }}">
            @csrf <!-- CSRF protection -->

            <!-- Email input -->
            <div>
                <label for="email">Email</label>
                <input id="email" type="email" name="email" required autofocus>
            </div>

            <!-- Password input -->
            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required autocomplete="current-password">
            </div>

            <!-- Remember Me checkbox -->
            <div>
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Remember Me</label>
            </div>

            <!-- Submit button -->
            <div>
                <button type="submit">Login</button>
            </div>

            <!-- Error messages (if any) -->
            @error('email')
                <div>{{ $message }}</div>
            @enderror

            @error('password')
                <div>{{ $message }}</div>
            @enderror
        </form>
    </div>
</body>
</html>
