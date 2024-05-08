<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lost and Found Objects Search</title>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
    .similar-description {
        background-color: #ffcccc; /* Color to highlight similar objects */
    }
</style>
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
    <div id="reportFoundObject" style="display: none;">
        <h3>Report Possible Found Object</h3>
        <textarea id="reportFoundObjectText" class="form-control" rows="3" placeholder="Enter details..."></textarea>
        <button id="submitReport" class="btn btn-primary mt-2">Submit</button>
    </div>
  </div>
</div>

<div id="map" style="height: 400px;"></div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.25.0/maps/maps-web.min.js"></script>
<link href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.25.0/maps/maps.css" rel="stylesheet">

<script>
function displaySearchResults(results, objectType, tableId, searchTerm) {
    var tableDiv = document.getElementById(tableId);
    tableDiv.innerHTML = "";

    var table = document.createElement("table");
    table.className = "table";
    var thead = document.createElement("thead");
    var tbody = document.createElement("tbody");
    var headerRow = document.createElement("tr");
    var headerCell1 = document.createElement("th");
    headerCell1.textContent = "Description";
    var headerCell2 = document.createElement("th");
    headerCell2.textContent = "Location ID";
    var headerCell3 = document.createElement("th");
    headerCell3.textContent = "Action";
    headerRow.appendChild(headerCell1);
    headerRow.appendChild(headerCell2);
    headerRow.appendChild(headerCell3);
    thead.appendChild(headerRow);
    table.appendChild(thead);

    if (results.length === 0) {
        var noResultsRow = document.createElement("tr");
        var noResultsCell = document.createElement("td");
        noResultsCell.textContent = "No results found.";
        noResultsCell.setAttribute("colspan", "3");
        noResultsRow.appendChild(noResultsCell);
        tbody.appendChild(noResultsRow);
    } else {
        results.forEach(function(result) {
            var row = document.createElement("tr");
            var descriptionCell = document.createElement("td");
            descriptionCell.textContent = result.description;

            var locationIdCell = document.createElement("td");
            locationIdCell.textContent = result.locationId;

            if (searchTerm && result.description.toLowerCase().includes(searchTerm.toLowerCase())) {
                descriptionCell.classList.add('similar-description');
                showReportFoundObject();
            }

            var mapButtonCell = document.createElement("td");
            var mapButton = document.createElement("button");
            mapButton.textContent = "Show Location";
            mapButton.onclick = function() {
                var locationId = result.locationId.toString();
                if (locationId) {fetchLocationAddress(locationId)
                } else {
                    console.error('Location ID is undefined or null');
                }
            };
            mapButtonCell.appendChild(mapButton);
            row.appendChild(descriptionCell);
            row.appendChild(locationIdCell);
            row.appendChild(mapButtonCell);
            tbody.appendChild(row);
        });
    }

    table.appendChild(tbody);
    tableDiv.appendChild(table);
}

function fetchAllData() {
    fetchAllLostObjects();
    fetchAllFoundObjects();
    fetchAllLocations();
    displayCurrentLocation();
}

function displayCurrentLocation() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            var latitude = position.coords.latitude;
            var longitude = position.coords.longitude;
            displayLocationOnMap(latitude, longitude);
        });
    } else {
        console.error('Geolocation is not supported by this browser.');
    }
}

function fetchAllLostObjects() {
    $.ajax({
        url: '/api/allLostObjects',
        method: 'GET',
        success: function(response) {
            displaySearchResults(response.data, "Lost Objects", "lostObjectsTable");
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
            displaySearchResults(response.data, "Found Objects", "foundObjectsTable");
        },
        error: function(xhr, status, error) {
            console.error("Error fetching all found objects:", error);
        }
    });
}

function fetchAllLocations() {
    $.ajax({
        url: '/api/getAllLocations',
        method: 'GET',
        success: function(response) {
            displaylocations(response.data, "Locations", "allLocationsTable");
        },
        error: function(xhr, status, error) {
            console.error("Error fetching all locations:", error);
        }
    });
}

function displaylocations(locations, objectType, tableId) {
    var tableDiv = document.getElementById(tableId);
    tableDiv.innerHTML = "";

    var table = document.createElement("table");
    table.className = "table";
    var thead = document.createElement("thead");
    var tbody = document.createElement("tbody");
    var headerRow = document.createElement("tr");
    var headerCell1 = document.createElement("th");
    headerCell1.textContent = "Location ID";
    var headerCell2 = document.createElement("th");
    headerCell2.textContent = "Street";
    var headerCell3 = document.createElement("th");
    headerCell3.textContent = "Freguesia";
    var headerCell4 = document.createElement("th");
    headerCell4.textContent = "Municipio";
    var headerCell5 = document.createElement("th");
    headerCell5.textContent = "Distrito";
    var headerCell6 = document.createElement("th");
    headerCell6.textContent = "Postal Code";
    var headerCell7 = document.createElement("th");
    headerCell7.textContent = "Country";

    headerRow.appendChild(headerCell1);
    headerRow.appendChild(headerCell2);
    headerRow.appendChild(headerCell3);
    headerRow.appendChild(headerCell4);
    headerRow.appendChild(headerCell5);
    headerRow.appendChild(headerCell6);
    headerRow.appendChild(headerCell7);
    thead.appendChild(headerRow);
    table.appendChild(thead);

    if (locations.length === 0) {
        var noResultsRow = document.createElement("tr");
        var noResultsCell = document.createElement("td");
        noResultsCell.textContent = "No locations found.";
        noResultsCell.setAttribute("colspan", "7");
        noResultsRow.appendChild(noResultsCell);
        tbody.appendChild(noResultsRow);
    } else {
        locations.forEach(function(location) {
            var row = document.createElement("tr");
            var locationIdCell = document.createElement("td");
            locationIdCell.textContent = location._id;
            var streetCell = document.createElement("td");
            streetCell.textContent = location.rua;
            var freguesiaCell = document.createElement("td");
            freguesiaCell.textContent = location.freguesia;
            var municipioCell = document.createElement("td");
            municipioCell.textContent = location.municipio;
            var distritoCell = document.createElement("td");
            distritoCell.textContent = location.distrito;
            var postalCodeCell = document.createElement("td");
            postalCodeCell.textContent = location.codigo_postal;
            var countryCell = document.createElement("td");
            countryCell.textContent = location.pais;

            row.appendChild(locationIdCell);
            row.appendChild(streetCell);
            row.appendChild(freguesiaCell);
            row.appendChild(municipioCell);
            row.appendChild(distritoCell);
            row.appendChild(postalCodeCell);
            row.appendChild(countryCell);
            tbody.appendChild(row);
        });
    }

    table.appendChild(tbody);
    tableDiv.appendChild(table);
}
function fetchLocationAddress(locationId) {
    $.ajax({
        url: '/api/fetchLocationAddress/' + locationId,
        method: 'GET',
        success: function(response) {
            if (response && response.data) {
                geocodeAddress(response.data);
            } else {
                console.error("Invalid response format for location address data.");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching location address:", error);
        }
    });
}

function geocodeAddress(address) {
    $.ajax({
        url: 'https://api.tomtom.com/search/2/geocoding/search?key=esi2aEIxbZ9vM3sFRH07xvj9wdMWEPZb&query=' + encodeURI(address),
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

function displayLocationOnMap(latitude, longitude) {
    var map = tt.map({
        key: 'esi2aEIxbZ9vM3sFRH07xvj9wdMWEPZb',
        container: 'map',
        center: [longitude, latitude],
        zoom: 10
    });
    map.on('load', () => {
    var marker = new tt.Marker().setLngLat([longitude, latitude]).addTo(map);
})
}

function searchObjects() {
    var searchTerm = document.getElementById("searchInput").value.trim(); // Trim the search term
    searchObjectsByDescription(searchTerm);
}

function searchObjectsByDescription(searchTerm) {
    $.ajax({
        url: '/api/searchObjectsByDescription',
        method: 'GET',
        data: { description: searchTerm },
        success: function(response) {
            displaySearchResults(response.data, "Objects", "searchResults", searchTerm);
        },
        error: function(xhr, status, error) {
            console.error("Error searching objects:", error);
        }
    });
}

document.getElementById('submitReport').addEventListener('click', function() {
    var reportText = document.getElementById('reportFoundObjectText').value;
    submitReport(reportText);
});

function submitReport(reportText) {
    console.log("Report submitted:", reportText);
    document.getElementById('reportFoundObjectText').value = "";
    document.getElementById('reportFoundObject').style.display = 'none';
}

fetchAllData();
</script>

</body>
</html>
