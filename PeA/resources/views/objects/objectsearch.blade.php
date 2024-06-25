<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<link rel="icon" href="images/favicon.ico" type="image/x-icon">
<title>Buscar Objetos Perdidos e Achados</title>

<style>
    body {
        background-color: #f8f9fa;
        font-family: Arial, sans-serif;
    }

    .container {
        max-width: 800px;
        margin: 50px auto;
        background-color: #ffffff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .input-group {
        margin-bottom: 20px;
    }

    .btn-group {
        margin-bottom: 20px;
    }

    .table {
        margin-top: 20px;
    }

    #map {
        margin-top: 20px;
        height: 400px;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }

    .similar-description {
        background-color: #ffcccc; 
    }

    .btn.active {
        background-color: #007bff;
        color: white;
    }
</style>   
<!-- Bootstrap CSS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
      crossorigin="anonymous">

<!-- TomTom Maps SDK CSS -->
<link href="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.25.0/maps/maps.css"
      rel="stylesheet">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>

<!-- Bootstrap JS bundle (including Popper.js) -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-..."
        crossorigin="anonymous"></script>

<!-- TomTom Maps SDK JS -->
<script src="https://api.tomtom.com/maps-sdk-for-web/cdn/6.x/6.25.0/maps/maps-web.min.js"></script>

</head>
<body>
<header>
      @if (auth()->check())
        @include('components.navbar')
      @else
        @include('components.navbar-guest')
      @endif      
</header>
    <div class="container"> 
        <h1 class="text-center mb-4">Buscar Objetos Perdidos e Achados</h1>
        <div class="input-group mb-3">
            <input type="text" class="form-control" id="searchInput" placeholder="Pesquisar..." aria-label="Pesquisar" aria-describedby="basic-addon2">
            <div class="input-group-append">
                <button class="btn btn-primary" type="button" onclick="searchObjects()">Pesquisar</button>
            </div>
        </div>
        
        <div class="btn-group btn-group-toggle mb-3" role="group" aria-label="Tipo de Objetos">
            <button type="button" class="btn btn-outline-primary" id="toggleObjects" onclick="toggleObjects('all')">Todos</button>
            <button type="button" class="btn btn-outline-primary" id="toggleLost" onclick="toggleObjects('lost')">Objetos Perdidos</button>
            <button type="button" class="btn btn-outline-primary" id="toggleFound" onclick="toggleObjects('found')">Objetos Achados</button>
        </div>
        
        <div id="searchResults">
            <h2 id="allObjectsTitle" style="display: none;">Todos os Objetos</h2>
            <div id="allObjectsTable"></div>
        </div>
    </div>

    <div id="map"></div>

<script>
// Initialize with all objects shown
var currentDisplay = 'all';
var currentMarker = null;
var map = tt.map({
    key: 'YaHwXWGyliPES0fF3ymLjwaqwdo2IbZn',
    container: 'map',
    center: [2.2945, 48.8584],
    zoom: 12
});

map.addControl(new tt.NavigationControl());

// Function to toggle between displaying all, lost, or found objects
function toggleObjects(type) {
    currentDisplay = type;
    updateButtonStyles();
    fetchAllData();
}

// Function to search objects based on description
function searchObjects() {
    var searchTerm = document.getElementById('searchInput').value;
    if (searchTerm.trim() !== '') {
        searchObjectsByDescription(searchTerm);
    } else {
        fetchAllData();
    }
}

// Fetch all data function based on currentDisplay
function fetchAllData() {
    if (currentDisplay === 'all') {
        fetchAllLostObjects();
        fetchAllFoundObjects();
    } else if (currentDisplay === 'lost') {
        fetchAllLostObjects();
    } else if (currentDisplay === 'found') {
        fetchAllFoundObjects();
    }
}

// Function to fetch all lost objects from backend
function fetchAllLostObjects() {
    $.ajax({
        url: '/api/allLostObjects',
        method: 'GET',
        success: function(response) {
            displaySearchResults(response.data, "Objetos Perdidos", "allObjectsTable");
        },
        error: function(xhr, status, error) {
            console.error("Erro ao buscar todos os objetos perdidos:", error);
        }
    });
}

// Function to fetch all found objects from backend
function fetchAllFoundObjects() {
    $.ajax({
        url: '/api/allFoundObjects',
        method: 'GET',
        success: function(response) {
            displaySearchResults(response.data, "Objetos Achados", "allObjectsTable");
        },
        error: function(xhr, status, error) {
            console.error("Erro ao buscar todos os objetos achados:", error);
        }
    });
}

// Function to search objects by description
function searchObjectsByDescription(searchTerm) {
    $.ajax({
        url: '/api/searchObjectsByDescription',
        method: 'GET',
        data: { description: searchTerm },
        success: function(response) {
            displaySearchResults(response.data, "Objetos", "allObjectsTable", searchTerm);
        },
        error: function(xhr, status, error) {
            console.error("Erro ao buscar objetos:", error);
        }
    });
}

// Function to update button styles
function updateButtonStyles() {
    document.getElementById('toggleObjects').classList.remove('active');
    document.getElementById('toggleLost').classList.remove('active');
    document.getElementById('toggleFound').classList.remove('active');

    if (currentDisplay === 'all') {
        document.getElementById('toggleObjects').classList.add('active');
    } else if (currentDisplay === 'lost') {
        document.getElementById('toggleLost').classList.add('active');
    } else if (currentDisplay === 'found') {
        document.getElementById('toggleFound').classList.add('active');
    }
}

// Function to display search results on frontend
function displaySearchResults(results, objectType, tableId, searchTerm) {
    var tableDiv = document.getElementById(tableId);
    tableDiv.innerHTML = "";

    var table = document.createElement("table");
    table.className = "table table-striped";
    var thead = document.createElement("thead");
    var tbody = document.createElement("tbody");
    var headerRow = document.createElement("tr");
    var headerCell1 = document.createElement("th");
    headerCell1.textContent = "Descrição";
    var headerCell2 = document.createElement("th");
    headerCell2.textContent = "ID da Localização";
    var headerCell3 = document.createElement("th");
    headerCell3.textContent = "Ação";
    headerRow.appendChild(headerCell1);
    headerRow.appendChild(headerCell2);
    headerRow.appendChild(headerCell3);
    thead.appendChild(headerRow);
    table.appendChild(thead);

    if (results.length === 0) {
        var noResultsRow = document.createElement("tr");
        var noResultsCell = document.createElement("td");
        noResultsCell.textContent = "Nenhum resultado encontrado.";
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
            }

            var mapButtonCell = document.createElement("td");
            var mapButton = document.createElement("button");
            mapButton.textContent = "Mostrar Localização";
            mapButton.className = "btn btn-sm btn-outline-primary";
            mapButton.onclick = function() {
                fetchLocationCoordinates(result.locationId, function(coordenadas) {
                    if (coordenadas) {
                        var latitude = coordenadas.latitude;
                        var longitude = coordenadas.longitude;
                        displayLocationOnMap(latitude, longitude);
                    } else {
                        console.error('Coordenadas não estão definidas para esta localização.');
                    }
                });
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

function fetchLocationCoordinates(locationId, callback) {
    fetch('/api/locations/' + locationId)
        .then(response => response.json())
        .then(location => {
            if (location && location.data && location.data.coordenadas) {
                callback(location.data.coordenadas);
            } else {
                console.error('Localização não encontrada ou coordenadas ausentes.');
                callback(null);
            }
        })
        .catch(error => {
            console.error('Erro ao buscar coordenadas da localização:', error);
            callback(null);
        });
}

// Function to display location on map
function displayLocationOnMap(latitude, longitude) {
    if (currentMarker) {
        currentMarker.remove();
    }

    currentMarker = new tt.Marker()
        .setLngLat([longitude, latitude])
        .addTo(map);

    map.flyTo({
        center: [longitude, latitude],
        zoom: 14
    });
}

// Initial fetch of all data when page loads
fetchAllData();

</script>

</body>
</html>
