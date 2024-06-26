<div class="container">
    <div class="row">
      <h2>Objetos perdidos</h2>
  </div> 
  <div class="row">
    <p>Aqui podem-se encontrar todos os objetos que foram perdidos.</p>  
  </div>
  <hr>
  <br>
  <div class="row">

    <div class="col-*" id="lost_objects">

    </div>
  </div><hr>
  </div>
</div>

<div class="container">
    <div class="row">
      <h2>Objetos encontrados</h2>
  </div> 
  <div class="row">
    <p>Aqui podem-se encontrar todos os objetos que foram encontrados.</p>  
  </div>
  <hr>
  <div class="row">
    <div class="col-*" id="found_objects">
      <meta name="csrf-token" content="{{ csrf_token() }}">

    </div>
  </div><hr>
</div>
</div>

<div class="container">
  <div class="row">
    <div class="col">
      <h2>Leilões</h2>
    </div>
  </div>
  <div class="row">
    <p>Aqui podem-se encontrar todos os leilões existentes.</p>  
  </div>
  <hr>
  <div class="row">
    <div class="col-*" id="auctions"></div>
  </div>
  <div class="row">
    <div class="col">
      <button id="show_bid_history" class="btn btn-primary">Mostrar Histórico de Licitações</button>
    </div>
  </div>
</div>



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
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
      </div>
    </div>
  </div>
</div>

    {{-- JQuery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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
    {{-- Objetos perdidos --}}
    <script >
        $.ajax({
          url: '{{ route("lost-objects.get") }}',
          method: 'GET',
          dataType: 'json',
          success: function(response) {
              let html = '';
              let counter = 0;
              for (let i = 0; i < 6; i++) {
              const item = response.data[i];
              
              if (item.status === "Lost"){
                  if (counter % 3 === 0 || counter === 0){
                  html += "<div class = 'row'>";
                  }
                  html += "<div class = 'col-4 border'>";
                  html += "<div class = 'row'>"; 
                  html += "<div class= 'col'><br>";
                  html += "<p>Categoria: " + item.category + "</p>";
                  html += "</div>";  
                  html += "</div>";  
                  html += "<p>Marca: " + item.brand + "</p>";
                  html += "<p>Cor: " + item.color + "</p>";
                  html += "<p>Data do desaparecimento: " + item.date_lost + "</p>";
                  html += "<div class = 'row justify-content-end'>";
                  html += "<div class = 'col-auto'>";
                  html += "<a class='btn btn-secondary' href={{ route('lost-object.get', '') }}/" + item._id + ">Ver Objeto </a><br><br>";
                  html += "</div>";
                  html += "</div>";
                  
                  // Add a horizontal line between each object
                  counter+=1
                  html += "</div>";
                  if (response.data[i] == response.data[5] || response.data[i] == response.data[-1]){
                    html += "<div class = 'col-4 align-self-center d-flex justify-content-center'>";
                    html += "<a class='btn btn-lg btn-outline-secondary' href={{ route('lost-objects.get') }}>Ver mais ></a>";
                    html += "</div>";
                  }
                  if (counter % 3 === 0 && counter !== 0){
                  html += "</div><br>";
                  }
                  
              }
              if (response.data[i] === response.data[-1]){
                break;
              }
              }
              
              $('#lost_objects').html(html); // Insert the generated HTML into the DOM
          },
          error: function(xhr, status, error) {
              console.error(xhr.responseText);
          }
        });
</script>
        {{-- // Objetos encontrados --}}
    <script>
            $.ajax({
                url: '{{ route("found-objects.get") }}',
                method: 'GET',
                dataType: 'json',
                success: function(response) {
                    let html = '';
                    let counter = 0;
    
                    for (let i = 0; i < 6; i++) {
                        const item = response.data[i];
                        if (item) {
                            if (counter % 3 === 0 || counter === 0) {
                                html += "<div class='row'>";
                            }
                            html += "<div class='col-4 border'>";
                            html += "<div class='row'>"; 
                            html += "<div class='col'><br>";
                            html += "<p>Categoria: " + (item.category || "N/A") + "</p>";
                            html += "</div>";
                            html += "</div>";  
                            html += "<p>Marca: " + (item.brand || "N/A") + "</p>";
                            html += "<p>Cor: " + (item.color || "N/A") + "</p>";
                            html += "<p>Data de aparecimento: " + (item.date_found || "N/A") + "</p>";
                            html += "<div class='row justify-content-end'>";
                            html += "<div class='col-auto'>";
                            html += "<a class='btn btn-secondary' href='{{ route('found-object.get', '') }}/" + (item._id || "") + "'>Ver Objeto</a><br><br>";
                            html += "</div>";
                            html += "</div>";
                            counter += 1;
                            html += "</div>";
    
                            if (i === 5 || i === response.data.length - 1) {
                                html += "<div class='col-4 align-self-center d-flex justify-content-center'>";
                                html += "<a class='btn btn-lg btn-outline-secondary' href='{{ route('lost-objects.get') }}'>Ver mais ></a>";
                                html += "</div>";
                            }
    
                            if (counter % 3 === 0 && counter !== 0) {
                                html += "</div><br>";
                            }
                        }
                    }
    
                    $('#found_objects').html(html); 
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
    </script>
    {{-- // Leilões --}}
    <script>
        $.ajax({
    url: '{{ route("auctions.get") }}',
    method: 'GET',
    dataType: 'json',
    success: function(response) {
        let html = '';
        for (let i = 0; i < 6; i++) {
            const item = response.data[i];
            if (item && item.status === "active") {
                if (i % 3 === 0 || i === 0) {
                    html += "<div class='row'>";
                }
                html += "<div class='col-4 border'>";
                html += "<p>Objeto: " + (item.objectId || "N/A") + "</p>";
                html += "<p>Licitação mais alta: " + item.highestBid + "</p>";
                html += "<p>Acaba em: " + item.end_date + "</p>";
                html += "<p>Status: " + (item.status || "N/A") + "</p>";
                html += "<a class='btn btn-secondary' href={{ route('auction.get', '') }}/" + item._id + ">Ver Leilao </a> ";
                html += "<button class='btn btn-info' onclick='showBidHistory(\"" + item._id + "\")'>Histórico de Licitações</button>";
                html += "</div>";
                if (i === 5 || i === response.data.length - 1) {
                    html += "<div class='col-4 align-self-center d-flex justify-content-center'>";
                    html += "<a class='btn btn-lg btn-outline-secondary' href='{{ route('auctions.get') }}'>Ver mais ></a>";
                    html += "</div>";
                }
                if (i % 3 === 0) {
                    html += "</div>";
                }
            }
        }
        $('#auctions').html(html);
    },
    error: function(xhr, status, error) {
        console.error(xhr.responseText);
    }
});

       
  function showBidHistory(auctionId) {
    $.ajax({
        url: '{{ route("auction.history.get", "") }}/' + auctionId,
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let html = '<h4>Histórico de Licitações</h4>';
            html += '<ul class="list-group">';
            response.data.bids_list.sort((a, b) => new Date(b.date) - new Date(a.date));
            response.data.bids_list.forEach(bid => {
                html += `<li class="list-group-item">Licitação: ${bid.amount} - Data: ${new Date(bid.date).toLocaleString()}</li>`;
            });
            html += '</ul>';
            $('#auctions').append(html);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

      </script>
    
    