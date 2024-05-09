<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    @if (auth()->check())
    <title>{{auth()->user()->name}}</title>
    @else
    <title>Sem acesso</title>
    @endif
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous"
    />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  </head>
  <body>
    <header>
      @if (auth()->check())
        @include('components.navbar')
      @else
        @include('components.navbar-guest')
      @endif 
      
    </header>
    @if (auth()->check())
    <br><br>
    <div class="container">
        <div class="row border">
            <div class="col-2">
                  <img src="../images/Sample_User_Icon.png" class="img-fluid" id="prof-pic" alt="User icon">
            </div>
            <div class="col">
                <h2>{{auth()->user()->name}}</h2>
                <br>
                <h4 >{{auth()->user()->email}}</h4>
            </div>
            <div class="col-auto">
                <button class="btn btn-primary">Editar perfil</button>
                <button class="btn btn-danger">Eliminar perfil</button>
            </div>
        </div>
    </div>
    <br><br>
    <div class="container vh-100">
        <div class="row border">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home-tab-pane" type="button" role="tab" aria-controls="home-tab-pane" aria-selected="true">Objetos perdidos</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile-tab-pane" type="button" role="tab" aria-controls="profile-tab-pane" aria-selected="false">Objetos encontrados</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact-tab-pane" type="button" role="tab" aria-controls="contact-tab-pane" aria-selected="false">Leilões</button>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home-tab-pane" role="tabpanel" aria-labelledby="home-tab" tabindex="0">
                    <div class="container">
                      <div class="row">
                        <h2>Os meus objetos perdidos</h2>
                    </div> 
                    <div class="row">
                      <p>Aqui serão inseridos os objetos que foram perdidos pelo utilizador e pode-se filtrar + pesquisar</p>  
                      <input class="form-control" id="lostObj" type="text" placeholder="Procurar..">
                    </div>
                    </div>
                    
                </div>
                <div class="tab-pane fade" id="profile-tab-pane" role="tabpanel" aria-labelledby="profile-tab" tabindex="0">
                    <h2>Os meus objetos encontrados</h2>
                    <p>Aqui serão inseridos os objetos que foram encontrados pelo utilizador e pode-se filtrar + pesquisar</p>  
                    <input class="form-control" id="foundObj" type="text" placeholder="Search..">
                </div>
                <div class="tab-pane fade" id="contact-tab-pane" role="tabpanel" aria-labelledby="contact-tab" tabindex="0">
                  <div class="container">
                    <div class="row">
                    <div class="col">
                      <h2>Os meus leilões</h2>
                    </div>
                    <div class="col-auto">
                      <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Filtrar
                        </button>
                        <ul class="dropdown-menu">
                          <li><button class="dropdown-item" type="button">Todos</button></li>
                          <li><button class="dropdown-item" type="button">Presente</button></li>
                          <li><button class="dropdown-item" type="button">Passado</button></li>
                          <li><button class="dropdown-item" type="button">Futuro</button></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <p>Aqui serão inseridos os leilões em que o utilizador está envolvido e pode-se filtrar + pesquisar</p>  
                    <input class="form-control" id="lostObj" type="text" placeholder="Procurar..">
                  </div>
                  </div>
                </div>
              </div>
        </div>
    </div>
    @endif
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
        $(document).ready(function(){
          $("#myInput").on("keyup", function() {
            var value = $(this).val().toLowerCase();
            $("#myTable tr").filter(function() {
              $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
            });
          });
        });
    </script>
  </body>
</html>