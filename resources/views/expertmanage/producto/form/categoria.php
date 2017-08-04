<div class="modal" id="ModalCategoria" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header btn-info">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Categoria</h4>
        </div>
        <div class="modal-body">
          <form id="ModalCategoriaForm">
            <div class="form-group">
              <label>Categoria</label>
              <input type="text" class="form-control" id="txt_categoria" name="txt_categoria" placeholder="Categoria">
            </div>
            <div class="form-group">
              <label>Estado</label>
              <select class="form-control selectpicker show-menu-arrow" name="slct_estado" id="slct_estado">
                <option value='0'>Inactivo</option>
                <option value='1' selected>Activo</option>
              </select>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
