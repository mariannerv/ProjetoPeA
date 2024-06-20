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
      <div class="container text-center">
        <div class="container mt-5">
          <div class="row justify-content-md-center">
      <div class="card">
        <div class="card-header">
          Escolher tipo de conta
        </div>
        <div class="card-body">
          <div class="container text-center">
            <div class="container mt-5">
              <div class="row justify-content-center d-flex align-content-stretch flex-wrap">
                <div class="col-md-4 col-sm-4 col-4 ">
                  <a href="http://localhost:8000/usersform" class="btn btn-secondary" role="button">
                    <img src="images/Sample_User_Icon.png" height="100%" class="img-fluid"  alt="User icon">
                    Utilizador / Licitador
                  </a>
                </div>
                <div class="col-md-4 col-sm-4 col-4 ">
                  <a href="http://localhost:8000/policesform" class="btn btn-secondary" role="button">
                    <img src="images/pngtree-police-silhouette-character-icon-design-png-image_6224403.png" height="100%" class="img-fluid"  alt="Police officer icon">
                    Polícia 

                  </a>
                </div>
                <div class="col-md-4 col-sm-4 col-4 ">
                  <a href="http://localhost:8000/stationsform" class="btn btn-secondary" role="button">
                    <img src="/images/89046.png" height="100%" class="img-fluid"  alt="Police station icon">
                    Estação de Polícia 
                  </a>
                </div>
              </div>
            </div>
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