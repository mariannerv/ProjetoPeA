<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Additional custom styles can be added here */
    </style>
</head>
<header>
      <?php     @include('components.navbar') 
?>
    </header>

<body>


<div class="container">
    @yield('content')
</div>
    <div class="container">
        <h2>Login</h2>
        <!-- Form for user login -->
        <form method="POST" action="{{ route('login') }}" class="needs-validation" novalidate>
            @csrf <!-- CSRF protection -->

            <!-- Email input -->
            <div class="form-group">
                <label for="email">Email</label>
                <input id="email" type="email" name="email" class="form-control" required autofocus>
                <div class="invalid-feedback">
                    Please provide a valid email.
                </div>
            </div>

            <!-- Password input -->
            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" class="form-control" required>
                <div class="invalid-feedback">
                    Please provide your password.
                </div>
            </div>

            <!-- Remember Me checkbox -->
            <div class="form-group form-check">
                <input type="checkbox" name="remember" id="remember" class="form-check-input">
                <label for="remember" class="form-check-label">Remember Me</label>
            </div>

            <!-- Submit button -->
            <button type="submit" class="btn btn-primary">Login</button>

            <!-- Error messages (if any) -->
            @error('email')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror

            @error('password')
            <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </form>
    </div>

    <!-- Bootstrap JS and dependencies (optional, only if you need JavaScript features) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Bootstrap validation styles
        (function () {
            'use strict';
            window.addEventListener('load', function () {
                var forms = document.getElementsByClassName('needs-validation');
                var validation = Array.prototype.filter.call(forms, function (form) {
                    form.addEventListener('submit', function (event) {
                        if (form.checkValidity() === false) {
                            event.preventDefault();
                            event.stopPropagation();
                        }
                        form.classList.add('was-validated');
                    }, false);
                });
            }, false);
        })();
    </script>
</body>

</html>
