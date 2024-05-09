<!-- resources/views/confirm_deletion.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Apagar Conta</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous"
    />
</head>
<body>
    @if (auth()->check())
    @include('components.navbar')
  @else
    @include('components.navbar-guest')
  @endif 
    <main class="my-5">
        <div class="container">
          <div class="container mt-auto">
            <div class="row justify-content-center">
              <div class="col-md-8">
                  <div class="card">
                      <div class="card-header text-left">Tem a certeza que quer eliminar o perfil?</div>
                      <div class="card-body text-center">
                            <p>Por favor confirme a eliminação de '{{ auth()->user()->name }}' ao introduzir a sua password:</p>
                            <form class="row g-3 needs-validation" method="post" action="{{ route('user.destroy', auth()->user()->id) }}">
                                @csrf
                                @method('DELETE')
                                <div class="row">
                                    <div class="col-auto">
                                        <label for="password" class="col-form-label">Password:</label>
                                    </div>
                                    <div class="col">
                                        <input type="password" class="form-control" id="password" name="password" required>
                                    </div>
                                </div>
                                
                                <button class="btn btn-primary" type="submit">Eliminar</button><button class="btn btn-secondary" onclick="goBack()">Cancelar</button>
                            </form>
                          
                      </div>
                  </div>
                  
              </div>
            </div>
        </div>
        </div>
      </main>
    
    @include('components.footer')
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
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
