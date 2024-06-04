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
        function fetchAllLostObjects() {
            $.ajax({
                url: '/api/allLostObjects',
                method: 'GET',
                success: function(response) {
                    clearMap();
                    response.data.forEach(function(object) {
                        fetchLocationAddress(object.locationId);
                    });
                    updateLostObjectsChart(response.data);
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
                    updateFoundObjectsChart(response.data);
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
                        geocodeAddress(response.data[0], response.data[1]);
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
                        var latitude = response.results[0].position.lat;
                        var longitude = response.results[0].position.lon;
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
            var marker = new tt.Marker().setLngLat([longitude, latitude]).addTo(map);
        }

        $('#displayLostObjects').on('click', function() {
            fetchAllLostObjects();
        });

        $('#displayFoundObjects').on('click', function() {
            fetchAllFoundObjects();
        });

        function groupByMonth(data) {
            const groupedData = {};
            data.forEach(object => {
                const month = new Date(object.date_found).getMonth(); // Assuming `date_found` is a date string
                if (!groupedData[month]) {
                    groupedData[month] = 0;
                }
                groupedData[month]++;
            });
            return groupedData;
        }

        function updateLostObjectsChart(data) {
            const groupedData = groupByMonth(data);
            const ctx = document.getElementById('lostObjectsChart').getContext('2d');
            const lostObjectsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(groupedData).map(month => new Date(0, month).toLocaleString('default', { month: 'long' })),
                    datasets: [{
                        label: 'Lost Objects',
                        data: Object.values(groupedData),
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
            const groupedData = groupByMonth(data);
            const ctx = document.getElementById('foundObjectsChart').getContext('2d');
            const foundObjectsChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: Object.keys(groupedData).map(month => new Date(0, month).toLocaleString('default', { month: 'long' })),
                    datasets: [{
                        label: 'Found Objects',
                        data: Object.values(groupedData),
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

        initMap();
    </script>
</body>
</html>
