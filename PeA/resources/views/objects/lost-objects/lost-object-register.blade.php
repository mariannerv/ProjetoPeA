<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Registar Objeto Perdido</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
</head>
<body>
    <header>
        @if (auth()->check())
            @include('components.navbar')
        @else
            @include('components.navbar-guest')
        @endif 
    </header>
    @if (auth()->check())
    <main class="my-5">
        <div class="container">
            <div class="container mt-5">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Registar Objeto Perdido</div>
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
                                <form class="row g-3 needs-validation" novalidate action="{{ route('lost-objects.register') }}" method="post">
                                    @csrf
                                    @method('POST')
                                    <div class="col-6">
                                        <label for="description" class="form-label">Descrição</label>
                                        <input type="text" class="form-control" id="description" name="description" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="date_lost" class="form-label">Data de Perda</label>
                                        <input type="date" class="form-control" id="date_lost" name="date_lost" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="category" class="form-label">Categoria</label>
                                        <input type="text" class="form-control" id="category" name="category" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="brand" class="form-label">Marca</label>
                                        <input type="text" class="form-control" id="brand" name="brand">
                                    </div>
                                    <div class="col-6">
                                        <label for="color" class="form-label">Cor</label>
                                        <input type="text" class="form-control" id="color" name="color">
                                    </div>
                                    <div class="col-6">
                                        <label for="size" class="form-label">Tamanho</label>
                                        <input type="text" class="form-control" id="size" name="size">
                                    </div>
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
                                    <input type="hidden" name="ownerEmail" value="{{ auth()->user()->email }}">
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
    <div class="modal fade" id="lostObjectRegister" tabindex="-1" role="dialog" aria-labelledby="lostObjectRegisterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="exampleModalLongTitle">Adicionar mais objetos?</h5>
              <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
              </button>
            </div>
            <div class="modal-body">
              Se pretender adicionar mais objetos, por favor, selecione Continuar, caso contrário, selecione Retroceder.
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Continuar</button>
              <a role="button" href="{{route('home')}}" class="btn btn-primary">Retroceder</a>
            </div>
          </div>
        </div>
      </div>
    @else
    @include('auth.noaccess')
    @endif
    @include('components.footer')
    {{-- JQuery --}}
    <script 
      src="https://code.jquery.com/jquery-3.6.0.min.js" 
      integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" 
      crossorigin="anonymous"
    ></script>
    {{-- Popper --}}
    <script
      src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
      integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB"
      crossorigin="anonymous"
    ></script>
    {{-- Bootstrap --}}
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"
      integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13"
      crossorigin="anonymous"
    ></script>
    {{-- Volta página anterior --}}
    <script>
        function goBack() {
            window.history.back();
        }
    </script>
    {{-- Validação formulário --}}
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
    {{-- Registo Objeto --}}
    <script>
        $(document).ready(function() {
            $('form').submit(function(event) {
                // Prevent the default form submission
                event.preventDefault();
    
                // Serialize form data
                var formData = $(this).serialize();
    
                // Submit form data via AJAX
                $.ajax({
                    url: $(this).attr('action'),
                    method: $(this).attr('method'),
                    data: formData,
                    dataType: 'json',
                    success: function(response) {
                        toastr.success(response.message, 'Success', { closeButton: true });
                        $('#lostObjectRegister').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            });
        });
    </script>
    
</body>
</html>