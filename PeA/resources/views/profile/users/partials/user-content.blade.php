<br><br>
    <div class="container">
        <div class="row border">
            <div class="col-2">
                  <img src="../images/Sample_User_Icon.png" class="img-fluid" id="prof-pic" alt="User icon">
            </div>
            <div class="col">
                <h2>{{auth()->user()->name}}</h2>
                <br>
                <h4 >{{auth()->user()->email}}</h4>
            </div>
            <div class="col-auto align-self-end">
                <button class="btn btn-primary">Editar perfil</button>
                <button class="btn btn-danger">Eliminar perfil</button>
            </div>
        </div>
    </div>
    <br><br>
    <div class="container">
        <div class="row border">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item" role="presentation">
                  <button class="nav-link active" id="lost-objects-tab" data-bs-toggle="tab" data-bs-target="#lost-objects-tab-pane" type="button" role="tab" aria-controls="lost-objects-tab-pane" aria-selected="true">Objetos perdidos</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="found-objects-tab" data-bs-toggle="tab" data-bs-target="#found-objects-tab-pane" type="button" role="tab" aria-controls="found-objects-tab-pane" aria-selected="false">Objetos encontrados</button>
                </li>
                <li class="nav-item" role="presentation">
                  <button class="nav-link" id="auctions-tab" data-bs-toggle="tab" data-bs-target="#auctions-tab-pane" type="button" role="tab" aria-controls="auctions-tab-pane" aria-selected="false">Leilões</button>
                </li>
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="lost-objects-tab-pane" role="tabpanel" aria-labelledby="lost-objects-tab" tabindex="0">
                    <div class="container">
                      <div class="row">
                        <h2>Os meus objetos perdidos</h2>
                    </div> 
                    <div class="row">
                      <input class="form-control" id="lostObj" type="text" placeholder="Procurar..">
                    </div>
                    <hr>
                    <div class="row ">
                      <div class="col d-flex justify-content-start">
                        <a class="btn btn-outline-secondary" href="{{ route('lost-objects.register-form') }}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-file-earmark-plus" viewBox="0 0 16 16">
                          <path d="M8 6.5a.5.5 0 0 1 .5.5v1.5H10a.5.5 0 0 1 0 1H8.5V11a.5.5 0 0 1-1 0V9.5H6a.5.5 0 0 1 0-1h1.5V7a.5.5 0 0 1 .5-.5"/>
                          <path d="M14 4.5V14a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V2a2 2 0 0 1 2-2h5.5zm-3 0A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v12a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1V4.5z"/>
                        </svg> Adicionar um objeto</a>
                      </div>
                      <div class="col-3 d-flex justify-content-end">
                          <select class="form-select" id="cat-select" aria-label="Default select example">
                            <option selected>Escolher categoria</option>
                            <option value="1">Todas</option>
                            <option value="2">Eletrodomésticos</option>
                            <option value="3">Roupa</option>
                            <option value="4">Dispositivos</option>
                            <option value="5">Acessórios</option>
                            <option value="6">Material escritório</option>
                            <option value="7">Outro</option>
                          </select>
                      </div>
                      <div class="col-auto d-flex justify-content-start">
                        <button class="btn btn-secondary">Filtrar</button>
                      </div>
                      
                    </div>
                    <br>
                    <div class="row">

                      <div class="col-*" id="lost_objects">

                      </div>
                    </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="found-objects-tab-pane" role="tabpanel" aria-labelledby="found-objects-tab" tabindex="0">
                  <div class="container">
                    <div class="row">
                      <h2>Os meus objetos encontrados</h2>
                  </div> 
                  <div class="row">
                    <input class="form-control" id="foundObj" type="text" placeholder="Procurar..">
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-*" id="found_objects">

                    </div>
                  </div>
                </div>
                </div>
                <div class="tab-pane fade" id="auctions-tab-pane" role="tabpanel" aria-labelledby="auctions-tab" tabindex="0">
                  <div class="container">
                    <div class="row">
                    <div class="col">
                      <h2>Os meus leilões</h2>
                    </div>
                    <div class="col-auto">
                      <div class="dropdown">
                        <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                          Filtrar
                        </button>
                        <ul class="dropdown-menu">
                          <li><button class="dropdown-item" type="button">Todos</button></li>
                          <li><button class="dropdown-item" type="button">Presente</button></li>
                          <li><button class="dropdown-item" type="button">Passado</button></li>
                          <li><button class="dropdown-item" type="button">Futuro</button></li>
                        </ul>
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <p>Aqui serão inseridos os leilões em que o utilizador está envolvido e pode-se filtrar + pesquisar</p>  
                    <input class="form-control" id="auction" type="text" placeholder="Procurar..">
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-*" id="auctions">

                    </div>
                  </div>
                  </div>
                </div>
              </div>
        </div>
    </div>