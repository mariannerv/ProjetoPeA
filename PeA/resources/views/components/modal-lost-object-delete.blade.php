<div class="modal fade" id="lostObject" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLongTitle">Apagar objeto</h5>
          <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <p>Tem a certeza que deseja eliminar este objeto? </p> 
          <p>Depois desta ação, não será possível voltar atrás.</p> 
        </div>
        <div class="modal-footer">
            <form id="deleteObject" class="g-3 needs-validation" action="" novalidate method="delete">
                @csrf
                <input type="hidden" id="lostObjectIdInput" name="lostObjectId" value=""></input>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                <button type="submit" class="btn btn-danger">Eliminar</a>
            </form>
        </div>
      </div>
    </div>
  </div>