<nav class="navbar navbar-dark bg-dark fixed-top">
<div class="container-fluid">
    <a class="navbar-brand" href="#">PeA</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasDarkNavbar" aria-controls="offcanvasDarkNavbar" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
    </button>
    <div class="offcanvas offcanvas-end text-white bg-dark" tabindex="-1" id="offcanvasDarkNavbar" aria-labelledby="offcanvasDarkNavbarLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="offcanvasDarkNavbarLabel">Perdidos e Achados</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="http://localhost:8000/home">Home</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Registar um objeto</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Ver os meus objetos</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Ver leilões</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="#">Procurar objetos</a>
        </li>
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            Utilizador(com sessão iniciada)
            </a>
            <ul class="dropdown-menu dropdown-menu-dark">
            <li><a class="dropdown-item" href="#">Alterar perfil</a></li>
            <li><a class="dropdown-item" href="#">Eliminar perfil</a></li>
            <li>
                <hr class="dropdown-divider">
            </li>
            <li><a class="dropdown-item" href="#">Terminar sessão</a></li>
            </ul>
        </li>
        </ul>
        <form class="d-flex mt-3" role="search">
        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search">
        <button class="btn btn-success" type="submit">Search</button>
        </form>
    </div>
    </div>
</div>
</nav>