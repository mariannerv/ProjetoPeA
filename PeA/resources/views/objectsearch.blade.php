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
    <input type="text" class="form-control" id="searchInput" placeholder="Search..." aria-label="Search" aria-describedby="basic-addon2" onkeyup="searchObjects()">
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
function fetchAllLostObjects() {
    $.ajax({
        url: '/api/allLostObjects',
        method: 'GET',
        success: function(response) {
            displaySearchResults(response.data, "Lost Objects", "lostObjectsTable");
            fetchAllFoundObjects(); // Call fetchAllFoundObjects inside the success callback
        },
        error: function(xhr, status, error) {
            console.error(error);
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
            console.error(error);
        }
    });
}

function fetchAllObjects() {
    fetchAllLostObjects();
}

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


function showReportFoundObject() {
    document.getElementById('reportFoundObject').style.display = 'block';
}

function displayLocationOnMap(latitude, longitude) {
    var map = tt.map({
        key: 'YhDS9lVCH9D9Ep2imuZKAG79jv7GuvQG',
        container: 'map',
        style: 'tomtom://vector/1/basic-main',
        center: [longitude, latitude],
        zoom: 13
    });

    var marker = new tt.Marker()
        .setLngLat([longitude, latitude])
        .addTo(map);
}
function fetchLocationAddress(locationId) {
    $.ajax({
        url: '/api/fetchLocationAddress/' + locationId,
        method: 'GET',
        success: function(response) {
            if (response.status) {
                geocodeAddress(response.data);
            } else {
                console.error('Error fetching location address:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching location address:', error);
        }
    });
}

function geocodeAddress(address) {
    $.ajax({
        url: 'https://api.tomtom.com/search/2/geocoding/search?key=YhDS9lVCH9D9Ep2imuZKAG79jv7GuvQG&query=' + encodeURI(address),
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

function searchObjects() {
    var searchTerm = document.getElementById("searchInput").value.trim(); // Trim the search term
    searchLostObjects(searchTerm);
    searchFoundObjects(searchTerm);
}

function searchLostObjects(searchTerm) {
    $.ajax({
        url: '/api/lost-object-search-by-description',
        method: 'GET',
        data: { description: searchTerm },
        success: function(response) {
            displaySearchResults(response.data, "Lost Objects", "lostObjectsTable", searchTerm);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

function searchFoundObjects(searchTerm) {
    $.ajax({
        url: '/api/found-object-search-by-description',
        method: 'GET',
        data: { description: searchTerm },
        success: function(response) {
            displaySearchResults(response.data, "Found Objects", "foundObjectsTable", searchTerm);
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}

fetchAllObjects();

document.getElementById('submitReport').addEventListener('click', function() {
    var reportText = document.getElementById('reportFoundObjectText').value;
    submitReport(reportText);
});

function submitReport(reportText) {
    console.log("Report submitted:", reportText);
    document.getElementById('reportFoundObjectText').value = "";
    document.getElementById('reportFoundObject').style.display = 'none';
}
</script>

</body>
</html>
