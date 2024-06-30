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
                      <input class="form-control" id="lostObj" type="text" placeholder="Procurar..">
                    </div>
                    <hr>
                    <div class="row ">
                      <div class="col d-flex justify-content-start">
                        <a class="btn btn-outline-secondary" href="{{ route('lost-objects.register-form') }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                          <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5"/>
                          <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                        </svg> Adicionar um objeto</a>
                      </div>
                      <div class="col-3 d-flex justify-content-end">
                          <select class="form-select" id="cat-select" aria-label="Default select example">
                            <option selected>Escolher categoria</option>
                            <option value="1">Todas</option>
                            <option value="2">Eletrodomésticos</option>
                            <option value="3">Roupa</option>
                            <option value="4">Dispositivos</option>
                            <option value="5">Acessórios</option>
                            <option value="6">Material escritório</option>
                            <option value="7">Outro</option>
                          </select>
                      </div>
                      <div class="col-auto d-flex justify-content-start">
                        <button class="btn btn-secondary">Filtrar</button>
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
        <!-- Auctions will be loaded here -->
    </div>
</div>
<div id="purchasedItemsContainer" class="mt-3"></div>
</div>
</div>
</div>
</div>

<!-- Modal for Bid History -->
<div class="modal fade" id="bidHistoryModal" tabindex="-1" aria-labelledby="bidHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bidHistoryModalLabel">Histórico de Licitações</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bidHistoryContent">
                <!-- Bid history content will be loaded here -->
            </div>
        </div>
    </div>
</div>

      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
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
  
    {{-- Objetos perdidos com filtro --}}
  <script>
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

            if (item.ownerEmail == '{{auth()->user()->email}}' && item.status === "Lost") {
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
    
                if (item.ownerEmail == '{{auth()->user()->email}}' && item.status === "Lost") {
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
            $('#found_objects').html(html); // Insert the generated HTML into the DOM
        }
      </script>

    
    {{-- Leilões --}}
    <script>
      $(document).ready(function() {
    // Fetch auctions and display them
    $.ajax({
        url: '{{ route("auctions.get") }}',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let html = '';
            for (let i = 0; i < response.data.length; i++) {
                const item = response.data[i];
                if (i % 3 === 0) {
                    html += "<div class='row'>";
                }
                html += "<div class='col-4 border'>";
                html += "<p>Objeto: " + item.objectId + "</p>";
                html += "<p>Licitação mais alta: " + item.highestBid + "</p>";
                html += "<p>Acaba em: " + item.end_date + "</p>";
                html += "<p>Status: " + item.status + "</p>";
                html += "<a class='btn btn-secondary' href='{{ route('auction.get', '') }}/" + item._id + "'>Ver Leilao</a>";
                html += "</div>";
                if (i % 3 === 2 || i === response.data.length - 1) {
                    html += "</div>";
                }
            }
            $('#auctions').html(html);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

    // Fetch purchased items and display them in a dropdown
    $.ajax({
        url: '{{ route("activeAuctions.get") }}',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let purchasedItems = [];
            var userEmail = "{{ auth()->user()->email }}";
            for (let i = 0; i < response.data.length; i++) {
                const item = response.data[i];
                if (item.highestBidderId === userEmail && item.pay === true) {
                    purchasedItems.push(item);
                }
            }
            displayPurchasedItems(purchasedItems);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });

    function displayPurchasedItems(items) {
    let html = '<div class="dropdown">';
    html += '<button class="btn btn-secondary dropdown-toggle" type="button" id="purchasedItemsDropdown" data-bs-toggle="dropdown" aria-expanded="false">';
    html += 'Items comprados: ' + items.length;
    html += '</button>';
    html += '<ul class="dropdown-menu" aria-labelledby="purchasedItemsDropdown">';
    
    if (items.length > 0) {
        for (let i = 0; i < items.length; i++) {
            const item = items[i];
            html += '<li><a class="dropdown-item" href="{{ route('auction.get', '') }}/' + item + '">';
            html += 'Objeto: ' + item.objectId + ' - Licitação mais alta: ' + item.highestBid;
            html += '</a></li>';
        }
    } else {
        html += '<li><span class="dropdown-item">Nenhuns items comprados</span></li>';
    }
    
    html += '</ul>';
    html += '</div>';
    
    $('#purchasedItemsContainer').html(html);

    // Adicionar evento para carregar os detalhes do objeto ao clicar em um item da lista
    $('#purchasedItemsContainer').on('click', '.dropdown-item', function(event) {
        event.preventDefault();
        let itemIndex = $(this).parent().index(); // Obtém o índice do item clicado
        let item = items[itemIndex]; // Obtém os dados do item correspondente
        
        // Fazer requisição AJAX para obter os detalhes do objeto
        $.ajax({
            url: '{{ route('found-object1.get', '') }}/' + item._id,
            method: 'GET',
            success: function(response) {
                if (response.status && response.data) {
                    // Construir o HTML com os detalhes do objeto encontrado
                    let detailsHtml = '<div><strong>Objeto:</strong> ' + response.data.objectId + '</div>';
                    detailsHtml += '<div><strong>Licitação mais alta:</strong> ' + item.highestBid + '</div>';
                    detailsHtml += '<div><strong>Marca:</strong> ' + response.data.brand + '</div>';
                    detailsHtml += '<div><strong>Cor:</strong> ' + response.data.color + '</div>';
                    // Adicionar mais informações conforme necessário
                    
                    // Substituir o conteúdo do item clicado com os detalhes
                    $(event.target).closest('.dropdown-item').html(detailsHtml);
                } else {
                    // Tratar caso não seja possível obter os detalhes do objeto
                    alert('Erro ao carregar detalhes do objeto.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Erro na requisição AJAX:', error);
                alert('Erro ao carregar detalhes do objeto.');
            }
        });
    });
}


    // Function to show bid history
    function showBidHistory(auctionId) {
        $.ajax({
            url: '{{ route("auction.history.get", "") }}/' + auctionId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let html = '<ul class="list-group">';
                response.bids_list.sort((a, b) => new Date(b.date) - new Date(a.date));
                response.bids_list.forEach(bid => {
                    html += `<li class="list-group-item">Licitação: ${bid.amount} - Data: ${new Date(bid.date).toLocaleString()}</li>`;
                });
                html += '</ul>';
                $('#bidHistoryContent').html(html);
                $('#bidHistoryModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
});

     function showBidHistory(auctionId) {
        $.ajax({
            url: '{{ route("auction.history.get", "") }}/' + auctionId,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let html = '<ul class="list-group">';
                response.bids_list.sort((a, b) => new Date(b.date) - new Date(a.date));
                response.bids_list.forEach(bid => {
                    html += `<li class="list-group-item">Licitação: ${bid.amount} - Data: ${new Date(bid.date).toLocaleString()}</li>`;
                });
                html += '</ul>';
                $('#bidHistoryContent').html(html);
                $('#bidHistoryModal').modal('show');
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
}

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




{{-- Filtro --}}
    {{-- <script>
        document.getElementById('lostObj').addEventListener('input', function() {
            const searchString = this.value.toLowerCase();
            renderObjects(searchString);
        });
        document.getElementById('foundObj').addEventListener('input', function() {
            const searchString = this.value.toLowerCase();
            renderObjects(searchString);
        });
    </script> --}}
    {{-- Objetos perdidos --}}
    {{-- <script >
     function renderObjects(searchString=''){
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
              if (
                        item.category.toLowerCase().includes(searchString) ||
                        item.brand.toLowerCase().includes(searchString) ||
                        item.color.toLowerCase().includes(searchString) ||
                        item.date_lost.toLowerCase().includes(searchString)
                    ) {
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
                let now = new Date();
                let createdDate = new Date(item.created_at);
                let oneHourAgo = new Date(now.getTime() - (60 * 60 * 1000));
                if (createdDate >= oneHourAgo) {
                  html += "<div class='row'><div class='col text-center'><p>Criado recentemente!</p></div></div>";
                } 
                // Add a horizontal line between each object
                counter+=1
                html += "</div>";
                if (counter % 3 === 0 && counter !== 0){
                html += "</div><br>";
                }
              }
            }
            }
            $('#lost_objects').html(html); // Insert the generated HTML into the DOM
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
      });}
      renderObjects();
    </script> --}}
    {{-- Objetos encontrados --}}
    {{-- <script>
      function renderObjects(searchString = ''){
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
                if (
                    item.category.toLowerCase().includes(searchString) ||
                    item.brand.toLowerCase().includes(searchString) ||
                    item.color.toLowerCase().includes(searchString) ||
                    item.date_lost.toLowerCase().includes(searchString)
                ) {
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
        }
            $('#found_objects').html(html); // Insert the generated HTML into the DOM
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
     });
      } 
      renderObjects();
    </script> --}}