<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lost and Found Objects Statistics</title>
    <!-- Include jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
    <!-- HTML for Statistical Graphs -->
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

    <!-- Button to Toggle Map Display -->
    <button id="toggleMapDisplay" class="btn btn-primary mt-3">Display Lost Objects on Map</button>

    <!-- Map Display -->
    <div id="mapContainer" class="container mt-3">
      <div id="map" style="height: 400px;"></div>
    </div>

    <script>
      // JavaScript Code to Fetch Data and Update Graphs

      // Function to fetch data and update statistical graphs
      function updateGraphs() {
        // Fetch data for lost objects categories and dates via AJAX requests
        $.ajax({
          url: '/api/getLostObjectsStatistics',
          method: 'GET',
          success: function(response) {
            // Update lost objects chart
            updateLostObjectsChart(response.data);
          },
          error: function(xhr, status, error) {
            console.error("Error fetching lost objects statistics:", error);
          }
        });

        // Fetch data for found objects categories and dates via AJAX requests
        $.ajax({
          url: '/api/getFoundObjectsStatistics',
          method: 'GET',
          success: function(response) {
            // Update found objects chart
            updateFoundObjectsChart(response.data);
          },
          error: function(xhr, status, error) {
            console.error("Error fetching found objects statistics:", error);
          }
        });
      }

      // Function to update lost objects chart
      function updateLostObjectsChart(data) {
        var ctx = document.getElementById('lostObjectsChart').getContext('2d');
        var lostObjectsChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.categories,
            datasets: [{
              label: 'Number of Lost Objects',
              data: data.counts,
              backgroundColor: 'rgba(255, 99, 132, 0.2)',
              borderColor: 'rgba(255, 99, 132, 1)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      }

      // Function to update found objects chart
      function updateFoundObjectsChart(data) {
        var ctx = document.getElementById('foundObjectsChart').getContext('2d');
        var foundObjectsChart = new Chart(ctx, {
          type: 'bar',
          data: {
            labels: data.categories,
            datasets: [{
              label: 'Number of Found Objects',
              data: data.counts,
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1
            }]
          },
          options: {
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true
                }
              }]
            }
          }
        });
      }

      // Initial update of statistical graphs
      updateGraphs();
    </script>
</body>
</html>
