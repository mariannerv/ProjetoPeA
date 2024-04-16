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
        @include('navbar')
    </header>
    <div class="container d-flex justify-content-center align-items-center vh-100">
      <div class="row justify-content-center">
        <div class="col-md-auto">
          <div class="text-center">
            <h2>Bem vindo!</h2>
            <p>Por favor escolhe uma opção:</p>
          </div>
        </div>
      </div>
      <div class="row justify-content-center">
        <div class="col-md-auto">
          <div class="text-center">
            <a role="button" href="http://localhost:8000/chooseaccounttype" class="btn btn-primary btn-lg btn-block mb-3">Criar nova conta de utilizador</a>
          </div>
        </div>
        <div class="col-md-auto">
          <div class="text-center">
            <a role="button" href="#" class="btn btn-secondary btn-lg btn-block mb-3">Iniciar sessão como utilizador</a>
          </div>
        </div>
      </div>
    </div>
    <script>
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
      integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
      crossorigin="anonymous"
    </script>
    <script>
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
      integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
      crossorigin="anonymous"
    </script>
    <script type="text/javascript">
    function redirect(url)
    {
    window.location(url);
    }
    </script>
  </body>
</html>