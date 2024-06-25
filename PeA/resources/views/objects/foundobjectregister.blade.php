<?php
if (!Auth::guard('police')->check()) {
    header('Location: ' . route('home'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Registar Objeto Encontrado</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.25.0/maps/maps.css" rel="stylesheet">
</head>
<body>
    <header>
        @include('components.navbar-police')
    </header>
    <main class="my-5">
        <div class="container">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Registar Objeto Encontrado</div>
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

                                @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                                @endif

                                <form id="foundObjectForm" class="row g-3 needs-validation" novalidate action="{{ route('found-objects.register') }}" enctype="multipart/form-data" method="post">
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
                                        <div class="col-md-6">
                                            <label for="category" class="form-label">Categoria</label>
                                            <input type="text" class="form-control" id="category" name="category" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="description" class="form-label">Descrição</label>
                                            <input type="text" class="form-control" id="description" name="description" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="date_found" class="form-label">Data de Aparecimento</label>
                                            <input type="date" class="form-control" id="date_found" name="date_found" required>
                                        </div>
                                        <div class="col-md-6">
                                            <label for="brand" class="form-label">Marca</label>
                                            <input type="text" class="form-control" id="brand" name="brand">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="color" class="form-label">Cor</label>
                                            <input type="text" class="form-control" id="color" name="color">
                                        </div>
                                        <div class="col-md-6">
                                            <label for="size" class="form-label">Tamanho</label>
                                            <input type="text" class="form-control" id="size" name="size">
                                        </div>
                                    </div>

                                    <!-- Map address input fields -->
                                    <div id="map-address-input" style="display: none;">
                                        <div class="mb-3">
                                            <label class="form-label">Selecione a localização no mapa:</label>
                                            <div id="map" style="height: 400px;"></div>
                                            <input type="hidden" id="map-address" name="map-address">
                                            <input type="hidden" id="map-postalcode" name="map-postalcode">
                                            <input type="hidden" id="map-city" name="map-city">
                                        </div>
                                        <p id="map-coordinates"></p>
                                    </div>

                                    <input type="hidden" name="policeStationId" value="{{ Auth::guard('police')->user()->policeStationId }}">
                                    <input type="hidden" name="uuid" id="uuid" value="{{ Str::uuid() }}">
                                    <input type="hidden" name="latitude" id="latitude">
                                    <input type="hidden" name="longitude" id="longitude">

                                    <div class="col-12">
                                        <button class="btn btn-primary" type="submit">Registar</button>
                                        <button class="btn btn-secondary" type="button" onclick="goBack()">Cancelar</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    @include('components.footer')

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.25.0/maps/maps-web.min.js"></script>

    <script>
        $(document).ready(function () {
            $('input[name="address-input-method"]').on('change', function () {
                if (this.value === 'manual') {
                    $('#manual-address-input').show();
                    $('#map-address-input').hide();
                } else {
                    $('#manual-address-input').hide();
                    $('#map-address-input').show();

                    if ($('#map').children().length === 0) {
                        initMap();
                    }
                }
            });

            var map;
            var marker;

            function initMap() {
                map = tt.map({
                    key: 'YaHwXWGyliPES0fF3ymLjwaqwdo2IbZn',
                    container: 'map-address-input',
                    center: [0, 0],
                    zoom: 2
                });

                map.on('click', function (event) {
                    var coordinates = event.lngLat;

                    if (marker) {
                        marker.remove();
                    }

                    marker = new tt.Marker().setLngLat(coordinates).addTo(map);
                    map.flyTo({
                        center: [coordinates.lng, coordinates.lat],
                        zoom: 14
                    });

                    var latLngString = coordinates.lat + ',' + coordinates.lng;
                    document.getElementById('map-coordinates').textContent = 'Coordenadas: ' + latLngString;
                    document.getElementById('latitude').value = coordinates.lat;
                    document.getElementById('longitude').value = coordinates.lng;

                    reverseGeocode(coordinates);
                });
            }

            function reverseGeocode(coordinates) {
                var url = `https://api.tomtom.com/search/2/reverseGeocode/${coordinates.lat},${coordinates.lng}.json?key=YaHwXWGyliPES0fF3ymLjwaqwdo2IbZn`;

                $.get(url, function (data) {
                    var address = data.addresses[0].address;
                    document.getElementById('map-address').value = address.freeformAddress;
                    document.getElementById('map-postalcode').value = address.postalCode;
                    document.getElementById('map-city').value = address.municipality;
                });
            }
        });

        function goBack() {
            window.history.back();
        }

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
</body>
</html>
