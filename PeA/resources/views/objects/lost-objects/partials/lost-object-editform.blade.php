<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="images/favicon.ico" type="image/x-icon">
    <title>Editar Objeto Perdido</title>
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
                            <div class="card-header">Editar Objeto Perdido</div>
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
                                <form class="row g-3 needs-validation" novalidate action="{{ route('lost-object.update', ['object' => $lostObject->_id]) }}" enctype="multipart/form-data" method="post" >
                                    @csrf
                                    @method('PUT')
                                    <div class="col-6">
                                        <label for="description" class="form-label">Descrição</label>
                                        <input type="text" class="form-control" id="description" value="{{$lostObject->description}}" name="description"  required>
                                    </div>
                                    <div class="col-6">
                                        <label for="date_lost" class="form-label">Data de Perda</label>
                                        <input type="date" class="form-control" id="date_lost" value="{{$lostObject->date_lost}}" name="date_lost" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="category" class="form-label">Categoria</label>
                                        <input type="text" class="form-control" id="category" value="{{$lostObject->category}}" name="category" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="brand" class="form-label">Marca</label>
                                        <input type="text" class="form-control" id="brand" value="{{$lostObject->brand}}" name="brand" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="color" class="form-label">Cor</label>
                                        <input type="text" class="form-control" id="color" value="{{$lostObject->color}}" name="color" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="size" class="form-label">Tamanho</label>
                                        <input type="text" class="form-control" id="size" value="{{$lostObject->size}}" name="size" required>
                                    </div>
                                    <div class="col-*">
                                        <label for="size" class="form-label">Morada</label>
                                        <input type="text" class="form-control" id="address" value="{{$lostObject->address}}" name="address" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="size" class="form-label">Código Postal</label>
                                        <input type="text" class="form-control" id="postalcode" value="{{$lostObject->postalcode}}" name="postalcode" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="size" class="form-label">Localidade</label>
                                        <input type="text" class="form-control" id="location" value="{{$lostObject->location}}" name="location" required>
                                    </div>
                                    <div class="col-6">
                                        <label for="img" class="form-label">Imagem</label>
                                        <input type="file" class="form-control" id="img" name="img" accept="image/*" onchange="previewImage(event)">
                                        @if($lostObject->image)
                                            <img id="currentImage" src="{{ asset('images/lost-objects-img/' . $lostObject->image) }}" style="max-width: 100%; margin-top: 10px;">
                                        @else
                                            <img id="currentImage" src="#" style="display:none; max-width: 100%; margin-top: 10px;">
                                        @endif
                                    </div>
                                    
                                    
                                    <input type="hidden" name="ownerEmail" value="{{ auth()->user()->email }}">
                                    <div class="col-12">
                                        <button class="btn btn-primary" type="submit">Editar</button>
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
        function previewImage(event) {
    const reader = new FileReader();
    reader.onload = function() {
       
        const currentImageOutput = document.getElementById('currentImage');
        currentImageOutput.src = reader.result;
        currentImageOutput.style.display = 'block';
    };
    reader.readAsDataURL(event.target.files[0]);
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
    {{-- Edição Objeto --}}
    <script>
    $(document).ready(function() {
        $('form').submit(function(event) {

            event.preventDefault();


            var formData = new FormData(this);


            $.ajax({
                url: $(this).attr('action'),
                method: $(this).attr('method'),
                data: formData,
                processData: false, 
                contentType: false, 
                dataType: 'json',
                success: function(response) {
                    toastr.success(response.message, 'Success', { closeButton: true });
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