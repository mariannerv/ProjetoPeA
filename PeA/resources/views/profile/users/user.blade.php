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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
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
            <div class="col-auto align-self-end">
                <button class="btn btn-primary">Editar perfil</button>
                <button class="btn btn-danger">Eliminar perfil</button>
            </div>
        </div>
    </div>
    <br><br>
    <div class="container">
        <div class="row border">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="lost-objects-tab" data-bs-toggle="tab" data-bs-target="#lost-objects-tab-pane" type="button" role="tab" aria-controls="lost-objects-tab-pane" aria-selected="true">Objetos perdidos</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="found-objects-tab" data-bs-toggle="tab" data-bs-target="#found-objects-tab-pane" type="button" role="tab" aria-controls="found-objects-tab-pane" aria-selected="false">Objetos encontrados</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="auctions-tab" data-bs-toggle="tab" data-bs-target="#auctions-tab-pane" type="button" role="tab" aria-controls="auctions-tab-pane" aria-selected="false">Leilões</button>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="lost-objects-tab-pane" role="tabpanel" aria-labelledby="lost-objects-tab" tabindex="0">
                    <div class="container">
                      <div class="row">
                        <h2>Os meus objetos perdidos</h2>
                    </div> 
                    <div class="row">
                      <p>Aqui serão inseridos os objetos que foram perdidos pelo utilizador e pode-se filtrar + pesquisar</p>  
                      <input class="form-control" id="lostObj" type="text" placeholder="Procurar..">
                    </div>
                    <hr>
                    <div class="row justify-content-end">
                      <div class="col-4 justify-content-end">
                        <a class="btn btn-outline-secondary" href="{{ route('lost-objects.register-form') }}">Adicionar um objeto</a>
                      </div>
                    </div>
                    <br>
                    <div class="row">

                      <div class="col-*" id="lost_objects">

                      </div>
                    </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="found-objects-tab-pane" role="tabpanel" aria-labelledby="found-objects-tab" tabindex="0">
                  <div class="container">
                    <div class="row">
                      <h2>Os meus objetos encontrados</h2>
                  </div> 
                  <div class="row">
                    <p>Aqui serão inseridos os objetos que foram encontrados pelo utilizador e pode-se filtrar + pesquisar</p>  
                    <input class="form-control" id="foundObj" type="text" placeholder="Procurar..">
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-*" id="found_objects">

                    </div>
                  </div>
                </div>
                </div>
                <div class="tab-pane fade" id="auctions-tab-pane" role="tabpanel" aria-labelledby="auctions-tab" tabindex="0">
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
                    <input class="form-control" id="auction" type="text" placeholder="Procurar..">
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-*" id="auctions">

                    </div>
                  </div>
                  </div>
                </div>
              </div>
        </div>
    </div>
    @include('components.modal-lost-object-delete')
    @else
    @include('auth.noaccess')
    @endif
    {{-- @include('components.footer') --}}

    {{-- JQuery --}}
    <script 
      src="https://code.jquery.com/jquery-3.6.0.min.js" 
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
      crossorigin="anonymous"
    ></script>
    {{-- Popper --}}
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
      integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
      crossorigin="anonymous"
    ></script>
    {{-- Bootstrap --}}
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
      integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
      crossorigin="anonymous"
    ></script>
    {{-- Filtro --}}
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
    {{-- Objetos perdidos --}}
    <script >
      $.ajax({
        url: '{{ route("lost-objects.get") }}',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let html = '';
            let counter = 0;
            for (let i = 0; i < response.data.length; i++) {
            const item = response.data[i];
            
            if ( item.ownerEmail == '{{auth()->user()->email}}' && item.status === "Lost"){
                if (counter % 3 === 0 || counter === 0){
                html += "<div class = 'row'>";
                }
                html += "<div class = 'col-4 border'>";
                html += "<div class = 'row'>"; 
                html += "<div class= 'col'><br>";
                html += "<p>Categoria: " + item.category + "</p>";
                html += "</div>";
                html += "<div class= 'col-auto'>";
                html += "<button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#lostObject' onclick='setID(\"" + item._id + "\")'> \
                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash2' viewBox='0 0 16 16'>\
                            <path d='M14 3a.7.7 0 0 1-.037.225l-1.684 10.104A2 2 0 0 1 10.305 15H5.694a2 2 0 0 1-1.973-1.671L2.037 3.225A.7.7 0 0 1 2 3c0-1.105 2.686-2 6-2s6 .895 6 2M3.215 4.207l1.493 8.957a1 1 0 0 0 .986.836h4.612a1 1 0 0 0 .986-.836l1.493-8.957C11.69 4.689 9.954 5 8 5s-3.69-.311-4.785-.793'/>\
                        </svg>\
                        </button>";
                html += "</div>";  
                html += "</div>";  
                html += "<p>Marca: " + item.brand + "</p>";
                html += "<p>Cor: " + item.color + "</p>";
                html += "<p>Data do desaparecimento: " + item.date_lost + "</p>";
                html += "<div class = 'row justify-content-end'>";
                html += "<div class = 'col-auto'>";
                html += "<a class='btn btn-secondary' href={{ route('lost-object.get', '') }}/" + item._id + ">Ver Objeto </a><br><br>";
                html += "</div>";
                html += "<div class = 'col-auto'>";
                html += "<a class='btn btn-secondary' href={{ route('lost-object.edit', '') }}/" + item._id + ">Editar Objeto </a><br><br>";
                html += "</div>";
                html += "</div>";
                // Add a horizontal line between each object
                counter+=1
                html += "</div>";
                if (counter % 3 === 0 && counter !== 0){
                html += "</div><br>";
                }
                
            }
            }
            $('#lost_objects').html(html); // Insert the generated HTML into the DOM
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
      });
    </script>
    {{-- Objetos encontrados --}}
    <script>
      $.ajax({
        url: '{{ route("lost-objects.get") }}',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let html = '';
            let counter = 0;
            for (let i = 0; i < response.data.length; i++) {
              const item = response.data[i];
              if ( item.ownerEmail == '{{auth()->user()->email}}' && item.status === "Found"){
                if (counter % 3 === 0 || counter === 0){
                  html += "<div class = 'row'>";
                }
                html += "<div class = 'col-4 border'>";
                html += "<div class = 'row'>"; 
                html += "<div class= 'col'><br>";
                html += "<p>Categoria: " + item.category + "</p>";
                html += "</div>";
                html += "<div class= 'col-auto'>";
                html += "<button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#exampleModalCenter'> \
                          <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash2' viewBox='0 0 16 16'>\
                            <path d='M14 3a.7.7 0 0 1-.037.225l-1.684 10.104A2 2 0 0 1 10.305 15H5.694a2 2 0 0 1-1.973-1.671L2.037 3.225A.7.7 0 0 1 2 3c0-1.105 2.686-2 6-2s6 .895 6 2M3.215 4.207l1.493 8.957a1 1 0 0 0 .986.836h4.612a1 1 0 0 0 .986-.836l1.493-8.957C11.69 4.689 9.954 5 8 5s-3.69-.311-4.785-.793'/>\
                          </svg>\
                         </button>";
                html += "</div>";  
                html += "</div>";  
                html += "<p>Marca: " + item.brand + "</p>";
                html += "<p>Cor: " + item.color + "</p>";
                html += "<p>Data de devolução: " +/* item.date_found + */"</p>"; // Adicionar BD a data devolução
                html += "<div class = 'row justify-content-end'>";
                html += "<div class = 'col-auto'>";
                html += "<a class='btn btn-secondary' href={{ route('lost-object.get', '') }}/" + item._id + ">Ver Objeto </a><br><br>";
                html += "</div>";
                html += "<div class = 'col-auto'>";
                html += "<a class='btn btn-secondary' href={{ route('lost-object.edit', '') }}/" + item._id + ">Editar Objeto </a><br><br>";
                html += "</div>";
                html += "</div>";
                counter+=1
                html += "</div>";
                if (counter % 3 === 0 && counter !== 0){
                  html += "</div><br>";
                }
                
              } // Add a horizontal line between each object
          }
            $('#found_objects').html(html); // Insert the generated HTML into the DOM
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
     });
    </script>
    {{-- Leilões --}}
    <script>
      $.ajax({
        url: '{{ route("auctions.get") }}',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let html = '';
            for (let i = 0; i < response.data.length; i++) {
              const item = response.data[i];
              // if ( item.ownerEmail === {{auth()->user()->email}}){
              if (i+1 % 3 === 0 || i === 0){
              html += "<div class = 'row'>";
              }
              html += "<div class = 'col-4 border'>";
              html += "<p>Objeto: " + item.objectId + "</p>";
              html += "<p>Licitação mais alta: " + item.highestBid + "</p>";
              html += "<p>Acaba em: " + item.end_date + "</p>";
              html += "<p>Status: " + item.status + "</p>";
              html += "<a class='btn btn-secondary' href={{ route('auction.get', '') }}/" + item._id + ">Ver Leilao </a> "
              if (i+1 % 3 === 0){
              html += "</div>";
              }
              html += "</div>"; // Add a horizontal line between each object
          }
            $('#auctions').html(html); // Insert the generated HTML into the DOM
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
     });
    </script>
    {{-- Inserir ID --}}
    <script>
      function setID(id){
        $("#lostObjectIdInput").val(id);
        var formAction = "{{ route('lost-object.delete', '') }}/" + $('#lostObjectIdInput').val();
        $('#deleteObject').attr('action', formAction);
      } 
    </script>
    {{-- Apagar objeto --}}
    <script>  
      $(document).ready(function() {
        $('form').submit(function(event) {
            // Prevent the default form submission
            event.preventDefault();

            // Serialize form data
            var formData = $(this).serialize();

            // Submit form data via AJAX
            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                dataType: 'json',
                success: function(response) {
                    $('#lostObject').modal('hide')
                    toastr.success(response.message, 'Success', { closeButton: true });
                    $.ajax({
                      url: '{{ route("lost-objects.get") }}',
                      method: 'GET',
                      dataType: 'json',
                      success: function(response) {
                          let html = '';
                          let counter = 0;
                          for (let i = 0; i < response.data.length; i++) {
                            const item = response.data[i];
                            
                            if ( item.ownerEmail == '{{auth()->user()->email}}' && item.status === "Lost"){
                              if (counter % 3 === 0 || counter === 0){
                                html += "<div class = 'row'>";
                              }
                              html += "<div class = 'col-4 border'>";
                              html += "<div class = 'row'>"; 
                              html += "<div class= 'col'><br>";
                              html += "<p>Categoria: " + item.category + "</p>";
                              html += "</div>";
                              html += "<div class= 'col-auto'>";
                              html += "<button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#lostObject' onclick='setID(\"" + item._id + "\")'> \
                                        <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash2' viewBox='0 0 16 16'>\
                                          <path d='M14 3a.7.7 0 0 1-.037.225l-1.684 10.104A2 2 0 0 1 10.305 15H5.694a2 2 0 0 1-1.973-1.671L2.037 3.225A.7.7 0 0 1 2 3c0-1.105 2.686-2 6-2s6 .895 6 2M3.215 4.207l1.493 8.957a1 1 0 0 0 .986.836h4.612a1 1 0 0 0 .986-.836l1.493-8.957C11.69 4.689 9.954 5 8 5s-3.69-.311-4.785-.793'/>\
                                        </svg>\
                                      </button>";
                              html += "</div>";  
                              html += "</div>";  
                              html += "<p>Marca: " + item.brand + "</p>";
                              html += "<p>Cor: " + item.color + "</p>";
                              html += "<p>Data do desaparecimento: " + item.date_lost + "</p>";
                              html += "<a class='btn btn-secondary' href={{ route('lost-object.get', '') }}/" + item._id + ">Ver Objeto </a><br><br>"
                              // Add a horizontal line between each object
                              counter+=1
                              html += "</div>";
                              if (counter % 3 === 0 && counter !== 0){
                                html += "</div><br>";
                              }
                              
                            }
                          }
                          $('#lost_objects').html(html); 
                      },
                      error: function(xhr, status, error) {
                          console.error(xhr.responseText);
                      }
     });
                    // $('#myModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        });
      });
    </script>
</body>
</html>