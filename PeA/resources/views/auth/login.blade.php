<?php
if (Auth::guard('police')->check() || Auth::check()) {
    header('Location: ' . route('home'));
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Login</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
  </head>
  <body>
    <header>
      @include('components.navbar-guest')
    </header>
    <main class="my-5">
      <div class="container">
        <div class="container mt-5">
          <div class="row justify-content-center">
              <div class="col-8">
                  <div class="card">
                      <div class="card-header">Iniciar sessão como utilizador</div>
                      <div class="card-body">

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                               
                            </ul>
                        </div>
                    @endif
                    <form class="g-3 needs-validation" action="{{route('user.login')}}" method="POST" novalidate>
                      @csrf
                      <div class="row">
                        <label for="validationCustom09" class="form-label">Email</label>
                        <input type="email" class="form-control" id="validationCustom09" name="email" value="{{ old('email') }}" required>
                        <div class="invalid-feedback">
                            Please provide a valid email address.
                        </div>
                      </div>
                      <div class="row">
                        <label for="validationCustom10" class="form-label">Password</label>
                        <input type="password" class="form-control" id="validationCustom10" name="password" required>
                        <div class="invalid-feedback">
                            Please provide a valid password.
                        </div>
                      </div>
                      <div class="col-12">
                        <button class="btn btn-primary" type="submit">Login</button>
                      </div>
                    </form>
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
      integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
      crossorigin="anonymous"
    ></script>
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
      integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
      crossorigin="anonymous"
    ></script>
  <script>
    // Example starter JavaScript for disabling form submissions if there are invalid fields
    (() => {
      'use strict'

      const forms = document.querySelectorAll('.needs-validation');
      // Fetch all the forms we want to apply custom Bootstrap validation styles to
      Array.from(forms).forEach(form => {
          form.addEventListener('submit', event => {
              let isValid = true;
              // Check each input field
              form.querySelectorAll('input, select, textarea').forEach(input => {
                  if (!input.checkValidity()) {
                    event.preventDefault();
                    event.stopPropagation();
                    input.classList.add('is-invalid');
                  } 
                  else {
                      input.classList.remove('is-invalid');
                  }
              });

              // Check date input validity
              const dateInput = form.querySelector('input[type="date"]');
              if (dateInput) {
                  const selectedDate = new Date(dateInput.value);
                  const today = new Date();
                  const maxDate = new Date(today.getFullYear() - 18, today.getMonth(), today.getDate());
                  if (selectedDate > maxDate) {
                      event.preventDefault();
                      event.stopPropagation();
                      dateInput.classList.add('is-invalid');
                  } 
                  else {
                      dateInput.classList.remove('is-invalid');
                  }
              }

              if (!isValid) {
                  event.preventDefault();
                  event.stopPropagation();
              }

              form.classList.add('was-validated');
          }, false);
        }) 
    })()
  </script>
</body>
</html>