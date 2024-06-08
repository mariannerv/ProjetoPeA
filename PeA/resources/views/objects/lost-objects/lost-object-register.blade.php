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
                                <form class="row g-3 needs-validation" novalidate action="{{ route('lost-objects.register') }}" method="post">
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
      integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
      crossorigin="anonymous"
    ></script>
    {{-- Bootstrap --}}
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
      integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
      crossorigin="anonymous"
    ></script>
    {{-- Volta página anterior --}}
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    {{-- Validação formulário --}}
    <script>
        (() => {
            'use strict';

            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
    {{-- Registo Objeto --}}
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
                        toastr.success(response.message, 'Success', { closeButton: true });
                        $('#lostObjectRegister').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    <script>
        $(document).ready(function() {
            $('input[name="address-input-method"]').change(function() {
                if ($('#map').is(':checked')) {
                    $('#manual-address-input').hide();
                    $('#map-address-input').show();
                    initTomTomMap();
                } else {
                    $('#manual-address-input').show();
                    $('#map-address-input').hide();
                }
            });

            function initTomTomMap() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(function(position) {
                        var latitude = position.coords.latitude;
                        var longitude = position.coords.longitude;

                        var map = tt.map({
                            key: 'YOUR_TOMTOM_API_KEY',
                            container: 'map',
                            center: [longitude, latitude],
                            zoom: 15
                        });

                        var marker = new tt.Marker().setLngLat([longitude, latitude]).addTo(map);

                        map.on('click', function(event) {
                            var coords = event.lngLat;
                            marker.setLngLat(coords);

                            // Fetch address details using TomTom's Reverse Geocoding API
                            $.get(`https://api.tomtom.com/search/2/reverseGeocode/${coords.lat},${coords.lng}.json?key=YOUR_TOMTOM_API_KEY`, function(data) {
                                if (data && data.addresses && data.addresses.length > 0) {
                                    var address = data.addresses[0].address;
                                    $('#map-address').val(address.freeformAddress);
                                    $('#map-postalcode').val(address.postalCode);
                                    $('#map-city').val(address.localName);
                                }
                            });
                        });
                    });
                }
            }
        });
    </script>
    
</body>
</html>