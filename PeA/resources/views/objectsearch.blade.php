<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Lost and Found Objects Search</title>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
<style>
    .similar-description {
        background-color: #ffcccc; /* Cor para destacar objetos semelhantes */
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
    fetchAllFoundObjects();
}

function displaySearchResults(results, objectType, tableId, searchTerm) {
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
            var addressCell = document.createElement("td");
            addressCell.textContent = result.address; 

            // Comparar a descrição com o termo de busca
            if (searchTerm && result.description.toLowerCase().includes(searchTerm.toLowerCase())) {
                addressCell.classList.add('similar-description'); // Adicionar classe de estilo
                showReportFoundObject(); // Mostrar o elemento de relatório
            }

            var mapButtonCell = document.createElement("td");
            var mapButton = document.createElement("button");
            mapButton.textContent = "Show Location";
            mapButton.onclick = function() {
                geocodeAddress(result.address);
            };
            mapButtonCell.appendChild(mapButton);
            row.appendChild(nameCell);
            row.appendChild(dateCell);
            row.appendChild(addressCell);
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
    tt.setProductInfo('YourAppName', '1.0');

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

function geocodeAddress(address) {
    $.ajax({
        url: 'https://api.tomtom.com/search/2/geocode/' + encodeURI(address) + '.json',
        method: 'GET',
        data: {
            key: 'YhDS9lVCH9D9Ep2imuZKAG79jv7GuvQG'
        },
        success: function(response) {
            if (response && response.results && response.results.length > 0) {
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

function searchLostObjects() {
    var searchTerm = document.getElementById("searchInput").value;

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

function searchFoundObjects() {
    var searchTerm = document.getElementById("searchInput").value;

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

function searchObjects() {
    searchLostObjects();
    searchFoundObjects();
}

fetchAllObjects();

document.getElementById('submitReport').addEventListener('click', function() {
    var reportText = document.getElementById('reportFoundObjectText').value;
    // Envie o relatório para o servidor
    submitReport(reportText);
});

function submitReport(reportText) {
    // Lógica para enviar o relatório para o servidor
    console.log("Report submitted:", reportText);
    // Limpar caixa de texto
    document.getElementById('reportFoundObjectText').value = "";
    // Esconder o elemento de relatório
    document.getElementById('reportFoundObject').style.display = 'none';
}
</script>

</body>
</html>
