<?php
if (!Auth::guard('police')->check()) {
    header('Location: ' . route('home'));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Registar Objeto Perdido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
</head>
<body>
<header>
        @include('components.navbar-police')
    </header>
    <main class="my-5">
        <div class="container">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Registar Objeto Encontrado</div>
                            <div class="card-body">

                                @if ($errors->any())
                                <div class="alert alert-danger">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                              @endif
                              
                              @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                              @endif
                                <form class="row g-3 needs-validation" novalidate action="{{ route('found-objects.register') }}" enctype="multipart/form-data" method="post">
                                    @csrf
                                    @method('POST')
                                    <div class="col-md-6">
                                        <label for="category" class="form-label">Categoria</label>
                                        <input type="text" class="form-control" id="category" name="category" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="description" class="form-label">Descrição</label>
                                        <input type="text" class="form-control" id="description" name="description" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date_found" class="form-label">Data de aparecimento</label>
                                        <input type="date" class="form-control" id="date_found" name="date_found" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="brand" class="form-label">Marca</label>
                                        <input type="text" class="form-control" id="brand" name="brand">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="color" class="form-label">Cor</label>
                                        <input type="text" class="form-control" id="color" name="color">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="size" class="form-label">Tamanho</label>
                                        <input type="text" class="form-control" id="size" name="size">
                                    </div>
                                    <input type="hidden" name="policeStationId" value="{{ Auth::guard('police')->user()->policeStationId }}">
                                    <div class="col-*">
                                        <label for="size" class="form-label">Morada</label>
                                        <input type="text" class="form-control" id="address" name="address">
                                    </div>
                                    <div class="col-6">
                                        <label for="size" class="form-label">Código Postal</label>
                                        <input type="text" class="form-control" id="postalcode" name="postalcode">
                                    </div>
                                    <div class="col-6">
                                        <label for="size" class="form-label">Localidade</label>
                                        <input type="text" class="form-control" id="location" name="location">
                                    </div>
                                    <div class="col-6">
                                        <label for="img" class="form-label">Imagem</label>
                                        <input type="file" id="img" name="img" accept="image/*" onchange="previewImage(event)">
                                        <img id="preview" src="#" alt="" style="display:none; max-width: 100%; margin-top: 10px;">
                                    </div>
                                    <p>Informação sobre a pessoa que encontrou o objeto:</p>
                                    <div class="col-md-6">
                                        <label for="size" class="form-label">Nome</label>
                                        <input type="text" class="form-control" id="name" name="name">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="size" class="form-label">Número</label>
                                        <input type="text" class="form-control" id="number" name="number">
                                    </div>
                                    <div class="col-md-6">
                                        <label for="size" class="form-label">Email</label>
                                        <input type="text" class="form-control" id="email" name="email">
                                    </div>
                                    <div class="col-12">
                                        <button class="btn btn-primary" type="submit">Registar</button>
                                        <button class="btn btn-secondary" onclick="goBack()">Cancelar</button>
                                    </div>
                                </form>
                               
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    @include('components.footer')
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    <script>
        function goBack() {
            window.history.back();
        }

        function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function(){
        const output = document.getElementById('preview');
        output.src = reader.result;
        output.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
}

    </script>
    <script>
        (() => {
            'use strict';

            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
</body>
</html>
