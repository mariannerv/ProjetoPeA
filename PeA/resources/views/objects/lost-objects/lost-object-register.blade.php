<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Registar Objeto Perdido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@tomtom-international/web-sdk-maps@6.19/dist/maps-web.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@tomtom-international/web-sdk-maps@6.19/dist/maps.css" rel="stylesheet">
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
    <main class="my-5">
        <div class="container">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Registar Objeto Perdido</div>
                            <div class="card-body">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <form class="row g-3 needs-validation" novalidate action="{{ route('lost-objects.register') }}" enctype="multipart/form-data" method="post">
                                    @csrf
                                    @method('POST')
                                    <div class="col-12">
                                        <label for="address-input-method" class="form-label">Método de Entrada de Endereço</label>
                                        <div>
                                            <input type="radio" id="manual" name="address-input-method" value="manual" checked>
                                            <label for="manual">Manual</label>
                                            <input type="radio" id="map" name="address-input-method" value="map">
                                            <label for="map">Mapa</label>
                                        </div>
                                    </div>
                                    <div id="manual-address-input" class="col-12">
                                        <div class="col-6">
                                            <label for="address" class="form-label">Morada</label>
                                            <input type="text" class="form-control" id="address" name="address">
                                        </div>
                                        <div class="col-6">
                                            <label for="postalcode" class="form-label">Código Postal</label>
                                            <input type="text" class="form-control" id="postalcode" name="postalcode">
                                        </div>
                                        <div class="col-6">
                                            <label for="city" class="form-label">Localidade</label>
                                            <input type="text" class="form-control" id="city" name="city">
                                        </div>
                                    </div>
                                    <div id="map-address-input" class="col-12" style="display: none;">
                                        <div id="map" style="width: 100%; height: 400px;"></div>
                                        <input type="hidden" id="map-address" name="address">
                                        <input type="hidden" id="map-postalcode" name="postalcode">
                                        <input type="hidden" id="map-city" name="city">
                                    </div>
                                    <div class="col-6">
                                        <label for="description" class="form-label">Descrição</label>
                                        <input type="text" class="form-control" id="description" name="description" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="date_lost" class="form-label">Data de Perda</label>
                                        <input type="date" class="form-control" id="date_lost" name="date_lost" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="category" class="form-label">Categoria</label>
                                        <input type="text" class="form-control" id="category" name="category" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="brand" class="form-label">Marca</label>
                                        <input type="text" class="form-control" id="brand" name="brand">
                                    </div>
                                    <div class="col-6">
                                        <label for="color" class="form-label">Cor</label>
                                        <input type="text" class="form-control" id="color" name="color">
                                    </div>
                                    <div class="col-6">
                                        <label for="size" class="form-label">Tamanho</label>
                                        <input type="text" class="form-control" id="size" name="size">
                                    </div>
                                    <div class="col-6">
                                        <label for="img" class="form-label">Imagem</label>
                                        <input type="file" id="img" name="img" accept="image/*" onchange="previewImage(event)">
                                        <img id="preview" src="#" alt="" style="display:none; max-width: 100%; margin-top: 10px;">
                                    </div>
                                    <input type="hidden" name="ownerEmail" value="{{ auth()->user()->email }}">
                                    <div class="col-12">
                                        <button class="btn btn-primary" type="submit">Registar</button>
                                        <button class="btn btn-secondary" onclick="goBack()">Cancelar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="modal fade" id="lostObjectRegister" tabindex="-1" role="dialog" aria-labelledby="lostObjectRegisterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Adicionar mais objetos?</h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Se pretender adicionar mais objetos, por favor, selecione Continuar, caso contrário, selecione Retroceder.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continuar</button>
              <a role="button" href="{{route('home')}}" class="btn btn-primary">Retroceder</a>
            </div>
          </div>
        </div>
      </div>
    @else
    @include('auth.noaccess')
    @endif
    @include('components.footer')
    {{-- JQuery --}}
    <script 
      src="https://code.jquery.com/jquery-3.6.0.min.js" 
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
      crossorigin="anonymous"
    ></script>
    {{-- Popper --}}
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
      integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz2D9Ht5H57EAP4gl8z4ykG1qKfI5pVb"
      crossorigin="anonymous"
    ></script>
    {{-- Bootstrap --}}
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
      integrity="sha384-QJHtvGhmr9DZ/bvYUCuVjCJBlrRJZ9fEXlWV9I6YICpC7MA4G/7aD64cD9B5QW1T"
      crossorigin="anonymous"
    ></script>
    {{-- Form Validation --}}
    <script>
      (function () {
        'use strict';
        var forms = document.querySelectorAll('.needs-validation');
        Array.prototype.slice.call(forms).forEach(function (form) {
          form.addEventListener('submit', function (event) {
            if (!form.checkValidity()) {
              event.preventDefault();
              event.stopPropagation();
            }
            form.classList.add('was-validated');
          }, false);
        });
      })();
    </script>
    {{-- AJAX Form Submission --}}
    <script>
      $(document).ready(function () {
        $('form').on('submit', function (e) {
          e.preventDefault();
          var formData = new FormData(this);
          $.ajax({
            type: 'POST',
            url: $(this).attr('action'),
            data: formData,
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
              toastr.success(response.message);
              $('#lostObjectRegister').modal('show');
            },
            error: function (response) {
              toastr.error(response.responseJSON.message);
            },
          });
        });
      });
    </script>
    {{-- Image Preview --}}
    <script>
      function previewImage(event) {
        var reader = new FileReader();
        reader.onload = function(){
          var output = document.getElementById('preview');
          output.src = reader.result;
          output.style.display = 'block';
        };
        reader.readAsDataURL(event.target.files[0]);
      }
    </script>
    {{-- TomTom Map --}}
    <script>
  var map = tt.map({
    key: 'YaHwXWGyliPES0fF3ymLjwaqwdo2IbZn',
    container: 'map',
    style: 'tomtom://vector/1/basic-main',
    center: [0, 0],
    zoom: 2
  });

  var marker;
  map.on('click', function(event) {
    var coordinates = event.lngLat;
    if (marker) {
      marker.remove();
    }
    marker = new tt.Marker().setLngLat(coordinates).addTo(map);
    document.getElementById('longitude').value = coordinates.lng;
    document.getElementById('latitude').value = coordinates.lat;
    geocodeCoordinates(coordinates.lng, coordinates.lat);
  });

  function geocodeCoordinates(lon, lat) {
    var apiKey = 'YaHwXWGyliPES0fF3ymLjwaqwdo2IbZn';
    var url = `https://api.tomtom.com/search/2/reverseGeocode/${lat},${lon}.json?key=${apiKey}`;
    fetch(url)
      .then(response => response.json())
      .then(data => {
        if (data.addresses.length > 0) {
          var address = data.addresses[0].address;
          document.getElementById('address').value = address.freeformAddress;
          document.getElementById('city').value = address.municipality;
          document.getElementById('postalcode').value = address.postalCode;
        }
      });
  }

  $('input[name="address-input-method"]').on('change', function() {
    if (this.value === 'manual') {
      $('#manual-address-input').show();
      $('#map-address-input').hide();
    } else {
      $('#manual-address-input').hide();
      $('#map-address-input').show();
    }
  });
</script>

</body>
</html>
