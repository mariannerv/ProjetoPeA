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
    @include('profile.users.partials.user-content')
    @include('components.modal-lost-object-delete')
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
{{-- Objetos perdidos com filtro --}}
@if(Auth::check())
    <script>
      console.log({{ auth()->check() }})
      if ({{ Auth::check() }}){
        let allObjects = [];

        // Fetch data once when the page loads
        $.ajax({
            url: '{{ route("lost-objects.get") }}',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                allObjects = response.data;
                renderObjects();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

        // Event listener for search input
        document.getElementById('lostObj').addEventListener('input', function() {
            const searchString = this.value.toLowerCase();
            renderObjects(searchString);
        });

        // Function to render objects based on search string
        function renderObjects(searchString = '') {
            let html = '';
            let counter = 0;

            for (let i = 0; i < allObjects.length; i++) {
                const item = allObjects[i];

                // Debugging information
                console.log('Item ownerEmail:', item.ownerEmail);
                console.log('Authenticated user email:', '{{ auth()->user()->email }}');
                console.log('Item status:', item.status);

                if (item.ownerEmail == '{{ auth()->user()->email }}' && item.status === "Lost") {
                    // Check if the item matches the search string
                    if (
                        item.category.toLowerCase().includes(searchString) ||
                        item.brand.toLowerCase().includes(searchString) ||
                        item.color.toLowerCase().includes(searchString) ||
                        item.date_lost.toLowerCase().includes(searchString)
                    ) {
                        if (counter % 3 === 0 || counter === 0) {
                            html += "<div class='row'>";
                        }

                    html += "<div class='col-4 border'>";
                    html += "<div class='row'>";
                    html += "<div class='col'><br>";
                    html += "<p>Categoria: " + item.category + "</p>";
                    html += "</div>";
                    html += "<div class='col-auto'>";
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
                    html += "<div class='row justify-content-end'>";
                    html += "<div class='col-auto'>";
                    html += "<a class='btn btn-secondary' href={{ route('lost-object.get', '') }}/" + item._id + ">Ver Objeto </a><br><br>";
                    html += "</div>";
                    html += "<div class='col-auto'>";
                    html += "<a class='btn btn-secondary' href={{ route('lost-object.edit', '') }}/" + item._id + ">Editar Objeto </a><br><br>";
                    html += "<a class='btn btn-secondary' href={{ route('found-search', '') }}/" + item._id + ">procurar objeto </a><br><br>";
                    html += "</div>";
                    html += "</div>";
                    
                    let now = new Date();
                    let createdDate = new Date(item.created_at);
                    let oneHourAgo = new Date(now.getTime() - (60 * 60 * 1000));
                    if (createdDate >= oneHourAgo) {
                        html += "<div class='row'><div class='col text-center'><p>Criado recentemente!</p></div></div>";
                    }

                        counter++;
                        html += "</div>";
                        if (counter % 3 === 0 && counter !== 0) {
                            html += "</div><br>";
                        }
                    }
                }
            }
            if (counter % 3 !== 0) {
                html += "</div>";
            }
            $('#lost_objects').html(html); // Insert the generated HTML into the DOM
        }
      }
    </script>
@endif
{{-- Objetos encontrados --}}
@if (auth()->check())
    <script>
        let foundObjects = [];

        // Fetch data once when the page loads
        $.ajax({
            url: '{{ route("found-objects.get") }}',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                foundObjects = response.data;
                renderFoundObjects();
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });

        // Function to render found objects
        function renderFoundObjects() {
            let html = '';
            let counter = 0;

            for (let i = 0; i < foundObjects.length; i++) {
                const item = foundObjects[i];

                // Debugging information
                console.log('Item ownerEmail:', item.ownerEmail);
                console.log('Authenticated user email:', '{{ auth()->user()->email }}');
                console.log('Item status:', item.status);

                if (item.ownerEmail == '{{ auth()->user()->email }}' && item.status === "Found") {
                    if (counter % 3 === 0 || counter === 0) {
                        html += "<div class='row'>";
                    }

                    html += "<div class='col-4 border'>";
                    html += "<div class='row'>";
                    html += "<div class='col'><br>";
                    html += "<p>Categoria: " + item.category + "</p>";
                    html += "</div>";
                    html += "<div class='col-auto'>";
                    html += "<button class='btn btn-danger' data-bs-toggle='modal' data-bs-target='#foundObject' onclick='setID(\"" + item._id + "\")'> \
                                <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-trash2' viewBox='0 0 16 16'>\
                                    <path d='M14 3a.7.7 0 0 1-.037.225l-1.684 10.104A2 2 0 0 1 10.305 15H5.694a2 2 0 0 1-1.973-1.671L2.037 3.225A.7.7 0 0 1 2 3c0-1.105 2.686-2 6-2s6 .895 6 2M3.215 4.207l1.493 8.957a1 1 0 0 0 .986.836h4.612a1 1 0 0 0 .986-.836l1.493-8.957C11.69 4.689 9.954 5 8 5s-3.69-.311-4.785-.793'/>\
                                </svg>\
                            </button>";
                    html += "</div>";  
                    html += "</div>";  
                    html += "<p>Marca: " + item.brand + "</p>";
                    html += "<p>Cor: " + item.color + "</p>";
                    html += "<p>Data do desaparecimento: " + item.date_found + "</p>";
                    html += "<div class='row justify-content-end'>";
                    html += "<div class='col-auto'>";
                    html += "<a class='btn btn-secondary' href={{ route('found-object.get', '') }}/" + item._id + ">Ver Objeto </a><br><br>";
                    html += "</div>";
                    html += "<div class='col-auto'>";
                    html += "<a class='btn btn-secondary' href={{ route('found-object.edit', '') }}/" + item._id + ">Editar Objeto </a><br><br>";
                    html += "</div>";
                    html += "</div>";

                    let now = new Date();
                    let createdDate = new Date(item.created_at);
                    let oneHourAgo = new Date(now.getTime() - (60 * 60 * 1000));
                    if (createdDate >= oneHourAgo) {
                        html += "<div class='row'><div class='col text-center'><p>Criado recentemente!</p></div></div>";
                    }

                    counter++;
                    html += "</div>";
                    if (counter % 3 === 0 && counter !== 0) {
                        html += "</div><br>";
                    }
                }
            }
            if (counter % 3 !== 0) {
                html += "</div>";
            }
            $('#found_objects').html(html); // Insert the generated HTML into the DOM
        }
    </script>
@endif

{{-- Leilões --}}
@if (auth()->check())
    <script>
        $.ajax({
            url: '{{ route("auctions.get") }}',
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let auctions = response.data;
                let html = '';
                let counter = 0;

                for (let i = 0; i < auctions.length; i++) {
                    const auction = auctions[i];

                    if (auction.bidder_list.includes( '{{ auth()->user()->email }}' )) {
                        if (counter % 3 === 0 || counter === 0) {
                            html += "<div class='row'>";
                        }

                        html += "<div class='col-4 border'>";
                        html += "<p>Objeto: " + auction.objectId + "</p>";
                        html += "<p>Licitação mais alta: " + auction.highestBid + "</p>";
                        html += "<p>Acaba em: " + auction.end_date + "</p>";
                        html += "<p>Status: " + auction.status + "</p>";
                        html += "<p>Nome: " +  '{{ auth()->user()->name }}' + " Inscrito no Leilao </p> "
                        html += "<div class='row justify-content-end'>";
                        html += "<div class='col-auto'>";
                        html += "<a class='btn btn-secondary' href={{ route('auction.get', '') }}/" + auction._id + ">Ver Leilão </a><br><br>";
                        html += "</div>";
                        html += "</div>";

                        let now = new Date();
                        let createdDate = new Date(auction.created_at);
                        let oneHourAgo = new Date(now.getTime() - (60 * 60 * 1000));
                        if (createdDate >= oneHourAgo) {
                            html += "<div class='row'><div class='col text-center'><p>Criado recentemente!</p></div></div>";
                        }

                        counter++;
                        html += "</div>";
                        if (counter % 3 === 0 && counter !== 0) {
                            html += "</div><br>";
                        }
                    }
                }
                if (counter % 3 !== 0) {
                    html += "</div>";
                }
                $('#auctions').html(html); // Insert the generated HTML into the DOM
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    </script>
@endif


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
    @else
    @include('auth.noaccess')
    @endif
    {{-- @include('components.footer') --}}


</body>
</html>