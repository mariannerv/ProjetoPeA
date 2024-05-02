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

  </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
  function searchObjects() {
    var searchTerm = document.getElementById("searchInput").value;

    
    $.ajax({
        url: '/api/lost-object-search-by-description',
        method: 'GET',
        data: { description: searchTerm }, 
        success: function(response) {
            displaySearchResults(response.data, "Lost Objects");
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });

   
    $.ajax({
        url: '/api/found-object-search-by-description',
        method: 'GET',
        data: { description: searchTerm }, 
        success: function(response) {
            displaySearchResults(response.data, "Found Objects");
        },
        error: function(xhr, status, error) {
            console.error(error);
        }
    });
}


function displaySearchResults(results, objectType) {
    var searchResultsDiv = document.getElementById("searchResults");
    searchResultsDiv.innerHTML = ""; 

    var table = document.createElement("table");
    table.className = "table";
    var thead = document.createElement("thead");
    var tbody = document.createElement("tbody");
    var headerRow = document.createElement("tr");
    var headerCell = document.createElement("th");
    headerCell.textContent = objectType;
    headerCell.setAttribute("colspan", "3");
    headerRow.appendChild(headerCell);
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
            var nameCell = document.createElement("td");
            nameCell.textContent = result.name; 
            var locationCell = document.createElement("td");
            locationCell.textContent = result.location; 
            row.appendChild(nameCell);
            row.appendChild(locationCell);
            tbody.appendChild(row);
        });
    }

    table.appendChild(tbody);
    searchResultsDiv.appendChild(table);
}

</script>
</body>
</html>
