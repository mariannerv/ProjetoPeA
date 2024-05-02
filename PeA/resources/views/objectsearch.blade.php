<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lost and Found Objects Search</title>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container">
  <h1>Lost and Found Objects Search</h1>
  <div class="input-group mb-3">
    <input type="text" class="form-control" id="searchInput" placeholder="Search..." aria-label="Search" aria-describedby="basic-addon2">
    <div class="input-group-append">
      <button class="btn btn-outline-secondary" type="button" onclick="searchObjects()">Search</button>
    </div>
  </div>

  <div id="searchResults" class="mt-3">
    <h2>Lost Objects</h2>
    <div id="lostObjectsTable"></div>
    <h2>Found Objects</h2>
    <div id="foundObjectsTable"></div>
  </div>
</div>

<div id="map" style="height: 400px;"></div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.11.0/maps/maps-web.min.js"></script>
<link rel="stylesheet" href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.11.0/maps/maps.css" />

<script>
// Function to fetch all lost objects initially and display them
function fetchAllLostObjects() {
    $.ajax({
        url: '/api/allLostObjects',
        method: 'GET',
        success: function(response) {
            displaySearchResults(response.data, "Lost Objects", "lostObjectsTable");
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

// Function to fetch all found objects initially and display them
function fetchAllFoundObjects() {
    $.ajax({
        url: '/api/allFoundObjects',
        method: 'GET',
        success: function(response) {
            displaySearchResults(response.data, "Found Objects", "foundObjectsTable");
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

// Function to fetch all objects initially when the page loads
function fetchAllObjects() {
    // Call the fetchAllLostObjects and fetchAllFoundObjects functions
    fetchAllLostObjects();
    fetchAllFoundObjects();
}



// Function to display search results in a table
function displaySearchResults(results, objectType, tableId) {
    var tableDiv = document.getElementById(tableId);
    tableDiv.innerHTML = ""; 

    var table = document.createElement("table");
    table.className = "table";
    var thead = document.createElement("thead");
    var tbody = document.createElement("tbody");
    var headerRow = document.createElement("tr");
    var headerCell = document.createElement("th");
    headerCell.textContent = objectType;
    headerCell.setAttribute("colspan", "4"); 
    headerRow.appendChild(headerCell);
    thead.appendChild(headerRow);
    table.appendChild(thead);

    if (results.length === 0) {
        var noResultsRow = document.createElement("tr");
        var noResultsCell = document.createElement("td");
        noResultsCell.textContent = "No results found.";
        noResultsCell.setAttribute("colspan", "4");
        noResultsRow.appendChild(noResultsCell);
        tbody.appendChild(noResultsRow);
    } else {
        results.forEach(function(result) {
            var row = document.createElement("tr");
            var nameCell = document.createElement("td");
            nameCell.textContent = result.name; 
            var dateCell = document.createElement("td");
            dateCell.textContent = result.date; 
            var descriptionCell = document.createElement("td");
            descriptionCell.textContent = result.description; 
            var mapButtonCell = document.createElement("td");
            var mapButton = document.createElement("button");
            mapButton.textContent = "Show Location";
            mapButton.onclick = function() {
                // Call a function to display the location on a map (using TomTom API)
                displayLocationOnMap(result.location);
            };
            mapButtonCell.appendChild(mapButton);
            row.appendChild(nameCell);
            row.appendChild(dateCell);
            row.appendChild(descriptionCell);
            row.appendChild(mapButtonCell);
            tbody.appendChild(row);
        });
    }

    table.appendChild(tbody);
    tableDiv.appendChild(table);
}

// Function to display location on map using TomTom API
function displayLocationOnMap(locationCoords) {
    // Parse the location coordinates string
    var coordinates = locationCoords.split(',');

    // Extract latitude and longitude
    var latitude = parseFloat(coordinates[0]);
    var longitude = parseFloat(coordinates[1]);

    // Initialize the map
    var map = tt.map({
        key: 'YOUR_TOMTOM_API_KEY',
        container: 'map',
        style: 'tomtom://vector/1/basic-main',
        center: [latitude, longitude],
        zoom: 13
    });

    // Add a marker to the map
    var marker = new tt.Marker().setLngLat([latitude, longitude]).addTo(map);
}
// Function to search for lost objects based on the entered search term
function searchLostObjects() {
    var searchTerm = document.getElementById("searchInput").value;

    // Perform search for lost objects based on the entered search term
    $.ajax({
        url: '/api/lost-object-search-by-description',
        method: 'GET',
        data: { description: searchTerm }, 
        success: function(response) {
            displaySearchResults(response.data, "Lost Objects", "lostObjectsTable");
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

// Function to search for found objects based on the entered search term
function searchFoundObjects() {
    var searchTerm = document.getElementById("searchInput").value;

    // Perform search for found objects based on the entered search term
    $.ajax({
        url: '/api/found-object-search-by-description',
        method: 'GET',
        data: { description: searchTerm }, 
        success: function(response) {
            displaySearchResults(response.data, "Found Objects", "foundObjectsTable");
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

// Function to search for objects based on the entered search term
function searchObjects() {
    // Call the searchLostObjects and searchFoundObjects functions
    searchLostObjects();
    searchFoundObjects();
}

// Fetch all objects initially when the page loads
fetchAllObjects();

</script>

</body>
</html>
