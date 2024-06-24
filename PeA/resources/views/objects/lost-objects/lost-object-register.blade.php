<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Registar Objeto Perdido</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- TomTom Maps SDK CSS -->
    <link href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.25.0/maps/maps.css" rel="stylesheet">
</head>
<body>
    <header>
        <!-- Navbar inclusion based on authentication status -->
        @if (auth()->check())
            @include('components.navbar')
        @else
            @include('components.navbar-guest')
        @endif 
    </header>
    <main class="my-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Registar Objeto Perdido</div>
                        <div class="card-body">
                            <!-- Form with dynamic address input selection -->
                            <form id="lostObjectForm" class="needs-validation" novalidate action="{{ route('lost-objects.register') }}" enctype="multipart/form-data" method="post">
                                @csrf
                                @method('POST')

                                <div class="mb-3">
                                    <label class="form-label">Método de Entrada de Endereço</label>
                                    <div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="manual" name="address-input-method" value="manual" checked>
                                            <label class="form-check-label" for="manual">Manual</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" id="map" name="address-input-method" value="map">
                                            <label class="form-check-label" for="map">Mapa</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Manual address input fields -->
                                <div id="manual-address-input">
                                    <div class="mb-3">
                                        <label for="address" class="form-label">Morada</label>
                                        <input type="text" class="form-control" id="address" name="address">
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="postalcode" class="form-label">Código Postal</label>
                                            <input type="text" class="form-control" id="postalcode" name="postalcode">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="city" class="form-label">Localidade</label>
                                            <input type="text" class="form-control" id="city" name="city">
                                        </div>
                                    </div>
                                </div>

                                <!-- Map address input fields -->
                                <div id="map-address-input">
                                    <div class="mb-3">
                                        <label class="form-label">Selecione a localização no mapa:</label>
                                        <div id="map" style="height: 400px;"></div>
                                        <input type="hidden" id="map-address" name="address">
                                        <input type="hidden" id="map-postalcode" name="postalcode">
                                        <input type="hidden" id="map-city" name="city">
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6 mb-3">
                                        <label for="description" class="form-label">Descrição</label>
                                        <input type="text" class="form-control" id="description" name="description" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="date_lost" class="form-label">Data de Perda</label>
                                        <input type="date" class="form-control" id="date_lost" name="date_lost" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="category" class="form-label">Categoria</label>
                                        <input type="text" class="form-control" id="category" name="category" required>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="brand" class="form-label">Marca</label>
                                        <input type="text" class="form-control" id="brand" name="brand">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="color" class="form-label">Cor</label>
                                        <input type="text" class="form-control" id="color" name="color">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="size" class="form-label">Tamanho</label>
                                        <input type="text" class="form-control" id="size" name="size">
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label for="img" class="form-label">Imagem</label>
                                        <input type="file" id="img" name="img" accept="image/*" onchange="previewImage(event)">
                                        <img id="preview" src="#" alt="" style="display:none; max-width: 100%; margin-top: 10px;">
                                    </div>
                                </div>

                                <!-- Hidden input for owner's email -->
                                <input type="hidden" name="ownerEmail" value="{{ auth()->user()->email }}">

                                <div class="mb-3">
                                    <button class="btn btn-primary" type="submit">Registar</button>
                                    <button class="btn btn-secondary" type="button" onclick="goBack()">Cancelar</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Bootstrap JS and TomTom Maps SDK JS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.25.0/maps/maps-web.min.js"></script>

    <script>$(document).ready(function () {
    // Function to handle radio button change
    $('input[name="address-input-method"]').on('change', function () {
        if (this.value === 'manual') {
            $('#manual-address-input').show();
            $('#map-address-input').hide();
        } else {
            $('#manual-address-input').hide();
            $('#map-address-input').show();
            
            // Initialize the map if it's not already initialized
            if ($('#map').children().length === 0) {
                initMap();
            }
        }
    });

    // Function to initialize the map
    var map;
    var marker;

    function initMap() {
        map = tt.map({
            key: 'your_api_key_here', // Replace with your TomTom API key
            container: 'map',
            center: [0, 0],
            zoom: 2
        });

        // Event listener for map click to place marker
        map.on('click', function (event) {
            var coordinates = event.lngLat;

            // Remove existing marker if present
            if (marker) {
                marker.remove();
            }

            // Place new marker at clicked coordinates
            marker = new tt.Marker().setLngLat(coordinates).addTo(map);

            // Update hidden inputs with selected coordinates
            document.getElementById('map-coordinates').value = coordinates.lat + ',' + coordinates.lng;

            // Reverse geocoding to update other address details if needed
            reverseGeocode(coordinates);
        });
    }

    // Function for reverse geocoding
    function reverseGeocode(coordinates) {
        var apiKey = 'your_api_key_here'; // Replace with your TomTom API key
        var url = `https://api.tomtom.com/search/2/reverseGeocode/${coordinates.lat},${coordinates.lng}.json?key=${apiKey}`;
        
        $.ajax({
            url: url,
            type: 'GET',
            success: function(data) {
                if (data.addresses && data.addresses.length > 0) {
                    var address = data.addresses[0].address;
                    document.getElementById('map-street').value = address.streetName || '';
                    document.getElementById('map-municipality').value = address.municipality || '';
                    document.getElementById('map-postalcode').value = address.postalCode || '';
                    document.getElementById('map-country').value = address.country || '';
                }
            },
            error: function(xhr, status, error) {
                console.error('Error during reverse geocoding:', error);
            }
        });
    }

    // Other JavaScript functions remain unchanged...
});

    </script>
</body>
</html>
