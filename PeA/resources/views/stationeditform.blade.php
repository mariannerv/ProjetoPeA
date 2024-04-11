<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
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
      <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
          <a class="navbar-brand" href="#">Navbar</a>
          <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent"
            aria-expanded="false"
            aria-label="Toggle navigation"
          >
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
              <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="#">Home</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="#">Link</a>
              </li>
              <li class="nav-item dropdown">
                <a
                  class="nav-link dropdown-toggle"
                  href="#"
                  id="navbarDropdown"
                  role="button"
                  data-bs-toggle="dropdown"
                  aria-expanded="false"
                >
                  Dropdown
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                  <li><a class="dropdown-item" href="#">Action</a></li>
                  <li><a class="dropdown-item" href="#">Another action</a></li>
                  <li><hr class="dropdown-divider" /></li>
                  <li>
                    <a class="dropdown-item" href="#">Something else here</a>
                  </li>
                </ul>
              </li>
            </ul>
            <form class="d-flex">
              <input
                class="form-control me-2"
                type="search"
                placeholder="Search"
                aria-label="Search"
              />
              <button class="btn btn-outline-success" type="submit">
                Search
              </button>
            </form>
          </div>
        </div>
      </nav>
    </header>
    <main class="my-5">
      <div class="container">
        <div class="container mt-5">
          <div class="row justify-content-center">
              <div class="col-md-8">
                  <div class="card">
                      <div class="card-header">Register</div>
                      <div class="card-body">

                    
                      <form class="row g-3 needs-validation" action="{{route('station.update' , ['station' => $user->_id])}}" method="post" novalidate>
                        
                        @csrf
                        @method('PUT')
                        <div class="col-md-6">
                            <label for="validationCustom03" class="form-label">Address</label>
                            <input type="text" class="form-control" id="validationCustom03" name="morada" value="{{$user->morada}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid address.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="validationCustom04" class="form-label">Postal Code</label>
                            <input type="text" class="form-control" id="validationCustom04" name="codigo_postal" value="{{$user->codigo_postal}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid postal code.
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label for="validationCustom05" class="form-label">Zone</label>
                            <input type="text" class="form-control" id="validationCustom05" name="localidade" value="{{$user->localidade}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid zone.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom03" class="form-label">Unit</label>
                            <input type="text" class="form-control" id="validationCustom03" name="unidade" value="{{$user->unidade}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid unit.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom03" class="form-label">Acronym</label>
                            <input type="text" class="form-control" id="validationCustom03" name="sigla" value="{{$user->sigla}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid acronym.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom08" class="form-label">Contact Number</label>
                            <input type="tel" class="form-control" id="validationCustom08" name="telefone" value="{{$user->telefone}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid contact number.
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="validationCustom08" class="form-label">Fax Number</label>
                            <input type="tel" class="form-control" id="validationCustom08" name="fax" value="{{$user->fax}}" required>
                            <div class="invalid-feedback">
                                Please provide a valid fax number.
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="validationCustom09" class="form-label">Email</label>
                            <input type="email" class="form-control" id="validationCustom09" name="email"  value="{{$user->email}}"required>
                            <div class="invalid-feedback">
                                Please provide a valid email address.
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <label for="validationCustom10" class="form-label">Password</label>
                            <input type="password" class="form-control" id="validationCustom10" name="password" required>
                            <div class="invalid-feedback">
                                Please provide a valid password.
                            </div>
                        </div> --}}
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
    <footer class="footer mt-auto py-3 bg-dark">
      <div class="container">
        <span class="text-muted"
          >Copyrights
          <a href="https://mdbootstrap.com">MDBootstrap.com</a></span
        >
      </div>
    </footer>
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