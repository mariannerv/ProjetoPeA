<!DOCTYPE html>
<html lang="en">
<head>

<!--ideia : procura objetos perdidos e achados e mostra a localizacao no mapa atraves do tomtom-->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Lost and Found Objects Search</title>
  <!-- Bootstrap CSS -->
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

  <!-- Search Results -->
  <div id="searchResults" class="mt-3">
    <!-- Results will be displayed here -->
  </div>
</div>

<!-- Bootstrap JS (optional, only if you need JavaScript features) -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<!-- Custom JavaScript -->
<script>
  // Function to search for lost and found objects
  function searchObjects() {
    var searchTerm = document.getElementById("searchInput").value;

    // Make AJAX request to search endpoint
    $.ajax({
      url: '/api/search', // Update the URL with your API endpoint
      method: 'GET',
      data: { query: searchTerm },
      success: function(response) {
        // Handle successful response
        displaySearchResults(response.data); // Assuming the response contains data in a specific format
      },
      error: function(xhr, status, error) {
        // Handle error
        console.error(error);
      }
    });
  }

  // Function to display search results
  function displaySearchResults(results) {
    var searchResultsDiv = document.getElementById("searchResults");
    searchResultsDiv.innerHTML = ""; // Clear previous results

    if (results.length === 0) {
      searchResultsDiv.innerHTML = "<p>No results found.</p>";
    } else {
      // Iterate over results and display them
      results.forEach(function(result) {
        // Create HTML elements to display result
        var resultDiv = document.createElement("div");
        resultDiv.className = "card mt-3";
        resultDiv.innerHTML = `
          <div class="card-body">
            <h5 class="card-title">${result.title}</h5>
            <p class="card-text">${result.description}</p>
            <!-- Add more details as needed -->
          </div>
        `;
        searchResultsDiv.appendChild(resultDiv);
      });
    }
  }
</script>
</body>
</html>
