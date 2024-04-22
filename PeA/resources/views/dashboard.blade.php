<!-- dashboard.blade.php -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Add any necessary meta tags, CSS, or JavaScript libraries here -->
    <link href="path/to/bootstrap.min.css" rel="stylesheet">
    <!-- Add any custom stylesheets here -->
    <style>
        /* Add custom CSS styles here */
    </style>
</head>
<body>
@include('components.navbar')

<div class="container">
    @yield('content')
</div>
    
    <div>
        <!-- No surplus words or unnecessary actions. - Marcus Aurelius -->
    </div>

    <!-- Add any necessary JavaScript libraries here -->
    <script src="path/to/bootstrap.bundle.min.js"></script>
    <!-- Add any custom JavaScript here -->
    <script>
        // Add custom JavaScript code here
    </script>
</body>
</html>
