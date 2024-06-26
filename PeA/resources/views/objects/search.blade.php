<?php
if (!auth()->check()) {
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
    <title>Entregar Objeto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link href="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.css" rel="stylesheet">
    <script src="https://cdn.datatables.net/v/dt/jq-3.7.0/dt-2.0.7/datatables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
<header>
  @include('components.navbar-guest')
</header>

<h1>Tabela de achados</h1>
<table id="foundObject" class="table table-striped" style="width:100%">
    <thead>
        <tr>
            <th>Marca</th>
            <th>Cor</th>
            <th>Categoria</th>
            <th>Descrição</th>
            <th>Data</th>
            <th>Comparar</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($foundObjects as $foundObject)
       
        <tr>
            <td>{{$foundObject->brand}}</td>
            <td>{{$foundObject->color}}</td>
            <td>{{$foundObject->category}}</td>
            <td>{{ $foundObject->description}}</td>
            <td>{{ $foundObject->date_found}}</td>
            <td><a href="{{route('compare.objects' , [$foundObject->_id , $id])}}"><button class="btn btn-primary" >Comparar</button></a></td>
        </tr>
        @endforeach
    </tbody>
</table>

<script>
document.addEventListener('DOMContentLoaded', (event) => {
    let selectedFoundObject = null;
    let selectedLostObject = null;

    const checkSelections = () => {
        if (selectedFoundObject && selectedLostObject) {
            Swal.fire({
                title: "Are you sure?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, compare them!"
            }).then((result) => {
                if (result.isConfirmed) {
                    const url = `{{ route('compare.objects', ['foundObject' => ':foundObject', 'lostObject' => ':lostObject']) }}`
                        .replace(':foundObject', selectedFoundObject)
                        .replace(':lostObject', selectedLostObject);
                    window.location.href = url;
                } else {
                    document.querySelectorAll('input[name="foundObject"]:checked').forEach((radio) => {
                        radio.checked = false;
                    });
                    document.querySelectorAll('input[name="lostObject"]:checked').forEach((radio) => {
                        radio.checked = false;
                    });
                    selectedFoundObject = null;
                    selectedLostObject = null;
                }
            });
        }
    };

    const handleRadioChange = (event) => {
        const target = event.target;
        if (target.name === 'foundObject') {
            selectedFoundObject = target.value;
        } else if (target.name === 'lostObject') {
            selectedLostObject = target.value;
        }
        checkSelections();
    };

    const assignRadioEventListeners = () => {
        document.querySelectorAll('input[name="foundObject"]').forEach((radio) => {
            radio.removeEventListener('change', handleRadioChange);
            radio.addEventListener('change', handleRadioChange);
        });

        document.querySelectorAll('input[name="lostObject"]').forEach((radio) => {
            radio.removeEventListener('change', handleRadioChange);
            radio.addEventListener('change', handleRadioChange);
        });
    };

    let tableFoundObject = new DataTable('#foundObject');
    let tableLostObject = new DataTable('#lostObject');

    tableFoundObject.on('draw', assignRadioEventListeners);
    tableLostObject.on('draw', assignRadioEventListeners);

    assignRadioEventListeners();
});
</script>

@include('components.footer')
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
</body>
</html>
