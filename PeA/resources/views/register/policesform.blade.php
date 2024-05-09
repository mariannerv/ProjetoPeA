<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Registar Conta</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
  </head>
  <body>
    <header>
      @if (auth()->check())
        @include('components.navbar')
      @else
        @include('components.navbar-guest')
      @endif 
    </header>
    <main class="my-5">
      <div class="container">
        <div class="container mt-5">
          <div class="row justify-content-center">
              <div class="col-8">
                  <div class="card">
                      <div class="card-header">Register</div>
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

                         <!---
                            $val = $request->validate([
                'name' => 'required|string',
                'internalId' => 'required|string|unique:police_user',
                'password' => 'required|min:8',
                'policeStationId' =>  'required|string|exists:police_station,sigla',
            ]); 
                         -->

                      <form class="row g-3 needs-validation"   action="{{route('police.register')}}" method="post" novalidate>
                        @csrf
                        <div class="col-4">
                            <label for="validationCustom01" class="form-label">Name</label>
                            <input type="text" class="form-control" id="validationCustom01" name="name" value="{{old('name')}}" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-4">
                            <label for="validationCustom02" class="form-label">Internal ID</label>
                            <input type="text" class="form-control" id="validationCustom02" name="internalId" value="{{old('internalId')}}">
                            <div class="invalid-feedback">
                                Please provide a valid ID
                            </div>
                        </div>
                        <div class="col-4">
                          <label for="validationCustom03" class="form-label">Police Station ID</label>
                          <select class="form-select" id="validationCustom02" name="policeStationId" value="{{old('policeStationId')}}" required>
                            <option selected disabled value="">Choose...</option>
                            @foreach ($users as $user)
                            <option>{{$user->sigla}}</option>
                            @endforeach
                        </select>
                          <div class="invalid-feedback">
                              Please provide a valid station ID
                          </div>
                      </div>
                        {{-- <div class="col-6">
                            <label for="validationCustom09" class="form-label">Email</label>
                            <input type="email" class="form-control" id="validationCustom09" name="email" required>
                            <div class="invalid-feedback">
                                Please provide a valid email address.
                            </div>

                            
                        </div> --}}
                        <div class="col-6">
                            <label for="validationCustom10" class="form-label">Password</label>
                            <input type="password" class="form-control" id="validationCustom10" name="password" required>
                            <div class="invalid-feedback">
                                Please provide a valid password.
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="" id="invalidCheck" required>
                                <label class="form-check-label" for="invalidCheck">
                                    Agree to terms and conditions
                                </label>
                                <div class="invalid-feedback">
                                    You must agree before submitting.
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Submit form</button>
                        </div>
                    </form>
                      </div>
                  </div>
              </div>
          </div>
      </div>
      </div>
    </main>
    @include('components.footer')
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