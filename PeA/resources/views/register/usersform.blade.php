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
              <div class="col-md-8">
                  <div class="card">
                      <div class="card-header">Registar utilizador</div>
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
                           <!--
                         'name' => 'required|string',
            'gender' => 'required|string',
            'birthdate' => 'required|date',
            'address' => 'required|string',
            'codigo_postal' => 'required|string',
            'localidade' => 'required|string',
            'civilId' => 'required|string',
            'taxId' => 'required|string|unique:users',
            'contactNumber' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
                        -->    
                      <form class="row g-3 needs-validation" novalidate action="{{route('user.register')}}" method="post">
                        @csrf
                        <div class="col-md-4">  
                            <label for="validationCustom01" class="form-label">Full name</label>
                            <input type="text" class="form-control" id="validationCustom01" name="name" value="{{old('name')}}" required>
                            <div class="valid-feedback">
                                Looks good!
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="validationCustom02" class="form-label">Gender</label>
                            <select class="form-select" id="validationCustom02" name="gender" value="{{old('gender')}}" required>
                                <option selected disabled value="">Choose...</option>
                                <option>Male</option>
                                <option>Female</option>
                                <option>Other</option>
                            </select>
                            <div class="invalid-feedback">
                                Please select a valid gender.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustomDate" class="form-label">Birthdate</label>
                            <div class="input-group has-validation">
                                <input type="date" class="form-control" id="validationCustomDate" name="birthdate" value="{{old('birthdate')}}" required>
                                <div class="invalid-feedback">
                                    You need to be over 18 years old.
                                </div>
                            </div>
                        </div>
                    
                        <div class="col-md-6">
                            <label for="validationCustom03" class="form-label">Address</label>
                            <input type="text" class="form-control" id="validationCustom03" name="address"  value="{{old('address')}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid address.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="validationCustom04" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="validationCustom04" name="codigo_postal" value="{{old('codigo_postal')}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid postal code.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="validationCustom05" class="form-label">City</label>
                            <input type="text" class="form-control" id="validationCustom05" name="localidade" value="{{old('localidade')}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid city.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom06" class="form-label">Civil ID</label>
                            <input type="text" class="form-control" id="validationCustom06" name="civilId" value="{{old('civilId')}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid Civil ID.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom07" class="form-label">Tax ID</label>
                            <input type="text" class="form-control" id="validationCustom07" name="taxId" value="{{old('taxId')}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid Tax ID.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom08" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="validationCustom08" name="contactNumber" value="{{old('contactNumber')}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid contact number.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom09" class="form-label">Email</label>
                            <input type="email" class="form-control" id="validationCustom09" name="email" value="{{old('email')}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid email address.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom10" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="validationCustom10" name="password" required autocomplete="current-password">
                            <div class="invalid-feedback">
                                Please provide a valid password.
                            </div>
                            @error('validationCustom10')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                             <label for="validationCustom11" class="form-label">Confirm Password</label>
                             <input id="validationCustom11" type="password" class="form-control @error('password') is-invalid @enderror" name="c_password" required autocomplete="current-password">
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
                      // input.classList.add('is-invalid');
                    } 
                    // else {
                    //     input.classList.remove('is-invalid');
                    // }
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
                        // dateInput.classList.add('is-invalid');
                    } 
                    // else {
                    //     dateInput.classList.remove('is-invalid');
                    // }
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