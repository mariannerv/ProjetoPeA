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
    <div class="container vh-100">
        <div class="row border">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="auctions-tab" data-bs-toggle="tab" data-bs-target="#auctions-tab-pane" type="button" role="tab" aria-controls="auctions-tab-pane" aria-selected="false">Leilões</button>
                </li>
            </ul>
                <div class="tab-pane fade show active" id="auctions-tab-pane" role="tabpanel" aria-labelledby="auctions-tab" tabindex="0">
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
    {{-- Leilões --}}
    <script>
      $.ajax({
        //teste
        url: '{{ route("activeAuctions.get") }}',
        method: 'GET',
        dataType: 'json',
        success: function(response) {
            let html = '';
            var userEmail = "{{ auth()->user()->email }}";
            var userName = "{{ auth()->user()->name }}"
            for (let i = 0; i < response.data.length; i++) {
              const item = response.data[i];
              if (i+1 % 3 === 0 || i === 0){
              html += "<div class = 'row'>";
              }
              html += "<div class = 'col-4 border'>";
              html += "<p>Objeto: " + item.objectId + "</p>";
              html += "<p>Licitação mais alta: " + item.highestBid + "</p>";
              html += "<p>Acaba em: " + item.end_date + "</p>";
              html += "<p>Status: " + item.status + "</p>";
              if (item.bidder_list.includes(userEmail)) {
                html += "<p>Nome: " +  userName + " Inscrito no Leilao </p> "
              }
              if (item.bidder_list.includes(userEmail)) {
                html += "<a class='btn btn-secondary' href={{ url('signUpAuctions') }}/" + item._id + "/" + userEmail + ">Licitar </a> "
              }
              html += "<a class='btn btn-secondary' href={{ route('auction.get', '') }}/" + item._id + ">Ver Leilao </a> "
              if (!item.bidder_list.includes(userEmail)) {
                html += "<a class='btn btn-secondary' href={{ url('signUpAuctions') }}/" + item._id + "/" + userEmail + ">Inscrever no Leilao </a> "
              }
              if (item.highestBidderId ==  userEmail && item.pay == true) {
                html += "<br></br>";
                html += "Seu leilão já esta pago</p>";
              } 
              if (item.highestBidderId ==  userEmail && item.pay == false && item.status == "deactive") {
                 html += "<a class='btn btn-secondary' href={{ route('auction.pay', '') }}/" + item._id + ">Efetuar pagamento</a> "
              }
              
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
    @endif 
</body>
</html>
