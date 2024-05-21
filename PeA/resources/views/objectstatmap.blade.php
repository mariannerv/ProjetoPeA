<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost and Found Objects Statistics</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.14.0/maps/maps-web.min.js"></script>
    <link rel="stylesheet" href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.14.0/maps/maps.css">
</head>
<body>
    <div class="container">
        <h2>Lost and Found Objects Statistics</h2>
        <div class="row">
            <div class="col-md-6">
                <canvas id="lostObjectsChart"></canvas>
            </div>
            <div class="col-md-6">
                <canvas id="foundObjectsChart"></canvas>
            </div>
        </div>
    </div>

    <button id="displayLostObjects" class="btn btn-primary mt-3">Display Lost Objects on Map</button>
    <button id="displayFoundObjects" class="btn btn-primary mt-3">Display Found Objects on Map</button>

    <div id="mapContainer" class="container mt-3">
        <div id="map" style="height: 400px;"></div>
    </div>

    <script>
        function updateGraphs() {
            $.ajax({
                url: '/api/getLostObjectsStatistics',
                method: 'GET',
                success: function(response) {
                    updateLostObjectsChart(response.data);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching lost objects statistics:", error);
                }
            });

            $.ajax({
                url: '/api/getFoundObjectsStatistics',
                method: 'GET',
                success: function(response) {
                    updateFoundObjectsChart(response.data);
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching found objects statistics:", error);
                }
            });
        }

        function updateLostObjectsChart(data) {
            const ctx = document.getElementById('lostObjectsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.category_id),
                    datasets: [{
                        label: 'Number of Lost Objects',
                        data: data.map(item => item.count),
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function updateFoundObjectsChart(data) {
            const ctx = document.getElementById('foundObjectsChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: data.map(item => item.categoryId),
                    datasets: [{
                        label: 'Number of Found Objects',
                        data: data.map(item => item.count),
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        }

        function fetchAllLostObjects() {
            $.ajax({
                url: '/api/allLostObjects',
                method: 'GET',
                success: function(response) {
                    clearMap();
                    response.data.forEach(function(object) {
                        fetchLocationAddress(object.locationId);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching all lost objects:", error);
                }
            });
        }

        function fetchAllFoundObjects() {
            $.ajax({
                url: '/api/allFoundObjects',
                method: 'GET',
                success: function(response) {
                    clearMap();
                    response.data.forEach(function(object) {
                        fetchLocationAddress(object.locationId);
                    });
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching all found objects:", error);
                }
            });
        }

        function fetchLocationAddress(locationId) {
            $.ajax({
                url: '/api/fetchLocationAddress/' + locationId,
                method: 'GET',
                success: function(response) {
                    if (response && response.data) {
                        geocodeAddress(response.data.address, response.data.apiKey);
                    } else {
                        console.error("Invalid response format for location address data.");
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching location address:", error);
                }
            });
        }

        function geocodeAddress(address, apiKey) {
            $.ajax({
                url: 'https://api.tomtom.com/search/2/geocode/' + encodeURIComponent(address) + '.json?key=' + apiKey,
                method: 'GET',
                success: function(response) {
                    if (response && response.results && response.results.length > 0 && response.results[0].type === "Point Address") {
                        const latitude = response.results[0].position.lat;
                        const longitude = response.results[0].position.lon;
                        displayLocationOnMap(latitude, longitude);
                    } else {
                        console.error('No coordinates found for the address:', address);
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error geocoding address:', error);
                }
            });
        }

        var map;
        function initMap() {
            map = tt.map({
                key: 'YaHwXWGyliPES0fF3ymLjwaqwdo2IbZn',
                container: 'map',
                center: [0, 0],
                zoom: 2
            });
        }

        function clearMap() {
            if (map) {
                map.remove();
            }
            initMap();
        }

        function displayLocationOnMap(latitude, longitude) {
            if (!map) {
                initMap();
            }
            new tt.Marker().setLngLat([longitude, latitude]).addTo(map);
        }

        $('#displayLostObjects').on('click', function() {
            fetchAllLostObjects();
        });

        $('#displayFoundObjects').on('click', function() {
            fetchAllFoundObjects();
        });

        // Initialize charts and map
        updateGraphs();
        initMap();
    </script>
</body>
</html>
