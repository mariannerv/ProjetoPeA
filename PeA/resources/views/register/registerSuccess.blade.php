<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Sucesso!</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
  </head>
  <body>
    @include('components.navbar-guest')
    <main class="my-5">
      <div class="container">
        <div class="container mt-auto">
          <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header text-left">Registo concluído!</div>
                    <div class="card-body text-center">
                        <h1>Obrigado por se juntar a nós!</h1>
                        <p>Foi enviado uma notificação via e-mail a confirmar o sucesso na criação de conta.</p>
                        <div class="d-grid gap-2">
                            <a href="http://localhost:8000/" role="button" class="btn btn-primary btn-lg" aria-disabled="true">Continuar</a>
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
  </body>
</html>