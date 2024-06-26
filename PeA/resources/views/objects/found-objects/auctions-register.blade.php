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
                            <div class="card-header">Registar Leilão</div>
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
                                <form class="row g-3 needs-validation" novalidate action="{{ route('auction.register') }}" method="post">
                                    @csrf
                                    @method('POST')
                                    <div class="col-md-4">
                                        <label for="validationCustom03" class="form-label">Objeto a Leiloar</label>
                                        <select class="form-select" id="objectId" name="objectId" value="objectId" required>
                                            <option selected disabled value="">Choose...</option>
                                            @foreach ($foundObjects as $objecto)
                                            @if($objecto->estacao_policia == Auth::guard('police')->user()->policeStationId)
                                            <option>{{$objecto->objectId}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                        <div class="invalid-feedback">
                                            Please provide a valid ObjectId
                                        </div>
                                    </div>
                                    <input type="hidden" name="policeStationId" value="{{ Auth::guard('police')->user()->policeStationId }}">
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