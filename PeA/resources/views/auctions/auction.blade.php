<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
   
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
      @elseif (Auth::guard('police')->user())
        @include('components.navbar-police')
      @else
        @include('components.navbar-guest')
      @endif 
      
    </header>
    <br><br>

    <div class="container border">
        <div class="row">
            <div class="col align-self-center" id="obj_img">
                
            </div>
            <div class="col">
                <div class="row">
                    <div class="col" id="obj_info">
                        
                    </div>
                </div>
                    <div class="col" id="obj_descr">
                        
                    </div>
                <div class="row">
                    <div class="col">
                        <p>Data de início do leilão: {{ $auction->start_date }}</p>
                        <p>Data de fim do leilão: {{ $auction->end_date }}</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col">
                        <p>Licitação mais alta: {{$auction->highestBid}} </p>
                    </div>
                </div>
                <div id="bidHistory" style="max-height: 300px; overflow-y: auto;">
                <h3>Histórico de Licitações</h3>
                <div class="bid-history-container">
                    <!-- Placeholder for bid history -->
                    <!-- This will be dynamically populated -->
                </div>
</div>

            <!-- Placeholder for bid history -->
            <!-- This will be dynamically populated -->
        </div>
                <div class="row">
                    <div class="col">
                      @if(auth()->check() || Auth::guard('police')->check())
                      @if(Auth::guard('police')->check())
                        @if (Auth::guard('police')->user()->policeStationId === $auction->policeStationId )
                        @if ($auction->status === "deactive")
                        <a class="btn btn-primary" href="{{ route('user.updateAuction', ['id' => $auction->_id]) }} ">Editar leilão</a>
                        <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#auction" onclick="setID('<?php echo $auction->_id; ?>')">Eliminar leilão</button>
                        <a class='btn btn-secondary' href="{{ route('auctions.finalizeOrStart', ['id' => $auction->_id]) }} ">Ativar Leilao </a>
                        @else
                        <a class='btn btn-secondary' href="{{ route('auctions.finalizeOrStart', ['id' => $auction->_id]) }}">Desativar Leilao </a>
                        @if($auction->highestBid > 0)
                        <a class="btn btn-outline-danger" href="{{ route('auction.finish', ['id' => $auction->_id]) }}">Terminar leilão</a>
                        @endif
                        @endif
                        @elseif(Auth::guard('police')->check())
                        @if ($auction->status === "deactive")
                        <a class='btn btn-secondary' href="{{ route('auctions.finalizeOrStart', ['id' => $auction->_id]) }} ">Ativar Leilao </a>
                        @else
                        <a class='btn btn-secondary' href="{{ route('auctions.finalizeOrStart', ['id' => $auction->_id]) }}">Desativar Leilao </a>
                        @if($auction->highestBid > 0)
                        <a class="btn btn-outline-danger" href="{{ route('auction.finish', ['id' => $auction->_id]) }}">Terminar leilão</a>
                        @endif
                        @endif
                        @endif
                        @else
                        <a class="btn btn-primary" href="{{ route("auction.userBidding", ['auctionId' => $auction->auctionId]) }}">Licitar</a> 
                        @endif
                        
                      @endif
                        {{-- Este botão vai servir como notificação de possivel dono --}}
                    </div>
                </div>
            </div>
        </div>
        <br>
    </div>
    @include('components.modal-delete-auction')
    @include('components.footer')
    <script 
      src="https://code.jquery.com/jquery-3.6.0.min.js" 
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
      crossorigin="anonymous"
    ></script>
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
    {{-- Inserir ID --}}
    <script>
      function setID(id){
        $("#auctionIdInput").val(id);
        var formAction = "{{ route('auction.delete', '') }}/" + $('#auctionIdInput').val();
        $('#deleteAuction').attr('action', formAction);
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
                      $('#auction').modal('hide');
                      toastr.success(response.message, 'Success', { closeButton: true });
                      // Navigate back to the previous page
                      // window.history.back();
                      // setTimeout(function() {
                      //   window.location.reload();
                      // }, 1000);

                      window.location.href = "{{ route('auctions.get') }}";
                  },
                  error: function(xhr, status, error) {
                    toastr.error('Erro ao eliminar leilão.', 'Erro', { closeButton: true });
                    console.error(xhr.responseText);
                  }
              });
          });
      });
    </script>
    {{-- Escrever objeto --}}
    <script>
      var url = '{{ route("found-object1.get", ":objectId") }}';
      url = url.replace(':objectId', '{{ $auction->objectId }}');
        $.ajax({
            url: url,

            method: 'GET',
            dataType: 'json',
            success: function(response) {
                let object = response.data;
                $("#obj_img").html("<img src='{{ asset('images/lost-objects-img/') }}/" + object.image + "' alt='image representing there is no image' class='img-fluid'>");                
                $("#obj_info").html("<h2>" + object.category + "</h2>");
                $("#obj_info").html(
                                    "<h2>" + object.category + "</h2>" +
                                    "<p>" + object.brand + "; " + object.color + 
                                    (object.size ? "; Tamanho " + object.size : "") + 
                                    "</p>"
                                );
                $("obj_descr").html("<p>Objeto perdido: " + object.description + "</p>")
            }
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {
    var auctionId = '{{ $auction->auctionId }}'; 
    var bidsList = @json($auction->bids_list);

    function fetchBidDetails(bidId, callback) {
        var url = '{{ route("bid.get", ":bidId") }}';
        url = url.replace(':bidId', bidId);

        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            success: function(response) {
                callback(null, response);
            },
            error: function(xhr, status, error) {
                callback({ status: status, error: error, response: xhr.responseText });
            }
        });
    }

    function processBidIds(bidIds, index) {
      if (index < bidIds.length) {
        fetchBidDetails(bidIds[index], function(err, bid) {
            if (err) {
                console.error('Error fetching bid details:', err);
                $('#bidHistory .bid-history-container').append('<div class="bid-history-item"><p>Error fetching bid details for ' + bidIds[index] + '</p></div>');
            } else {
                console.log('Bid Details:', bid);

                var bidDate = null;
                if (bid.bidDate && bid.bidDate.$date && bid.bidDate.$date.$numberLong) {
                    bidDate = new Date(parseInt(bid.bidDate.$date.$numberLong));
                } else {
                    console.warn('Invalid bidDate structure:', bid.bidDate);
                }

                if (bidDate) {
                    var formattedDate = formatDate(bidDate);
                    var html = '<div class="bid-history-item"><p><strong>Licitante:</strong> ' + bid.bidderId + ', <strong>Quantia:</strong> ' + bid.amount + ', <strong>Data:</strong> ' + formattedDate + '</p></div>';
                    
                    // Adicionar o HTML no início do contêiner para organizar da mais recente para a mais antiga
                    $('#bidHistory .bid-history-container').prepend(html);
                } else {
                    $('#bidHistory .bid-history-container').append('<div class="bid-history-item"><p>Licitante: ' + bid.bidderId + ', Quantia: ' + bid.amount + ', Data: Invalid Date</p></div>');
                }
            }
            processBidIds(bidIds, index + 1);
        });
        }
    }

    processBidIds(bidsList, 0);
});

function formatDate(date) {
    try {
        const options = { year: 'numeric', month: 'long', day: 'numeric', hour: 'numeric', minute: 'numeric', hour12: true };
        return date.toLocaleString('pt-PT', options); 
    } catch (error) {
        console.error('Error formatting date:', date, error);
        return 'Invalid Date';
    }
}
</script>


<style>
  #bidHistory {
    max-height: 300px; /* Altura máxima do histórico */
    overflow-y: auto; /* Adicionar scroll vertical se necessário */
    border: 1px solid #ccc; /* Borda para separar o histórico */
    padding: 10px; /* Espaçamento interno */
}

#bidHistory h3 {
    margin-top: 0; /* Remover margem superior do título */
    font-size: 1.2em; /* Tamanho de fonte do título */
    color: #333; /* Cor do texto */
}

.bid-history-item {
    margin-bottom: 10px; /* Espaçamento entre itens */
    padding: 8px; /* Espaçamento interno dos itens */
    background-color: #f9f9f9; /* Cor de fundo dos itens */
    border: 1px solid #ddd; /* Borda dos itens */
    border-radius: 4px; /* Cantos arredondados dos itens */
}

.bid-history-item p {
    margin: 0; /* Remover margem padrão dos parágrafos */
    font-size: 0.9em; /* Tamanho de fonte dos detalhes */
    color: #666; /* Cor do texto dos detalhes */
}

.bid-history-item p strong {
    color: #333; /* Cor do texto forte (licitante e quantidade) */
}

</style>