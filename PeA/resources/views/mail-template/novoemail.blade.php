<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Verificação de email</title>
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3"
        crossorigin="anonymous"
    />
    <style>
        /* Additional style to center the text */
        .centered-content {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh; /* Adjust as needed */
        }
    </style>
</head>
<body>
<header>
    @include('navbar')
</header>

<div class="container">
    <!-- Centered content div -->
    <div class="centered-content">
        <div class="text-center">
            <h2>Um novo email de verificação foi enviado.</h2>
        </div>
    </div>
</div>

</body>
</html>

