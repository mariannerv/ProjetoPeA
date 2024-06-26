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

                      window.location.href = "{{ route('auctions.viewAll') }}";
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
      