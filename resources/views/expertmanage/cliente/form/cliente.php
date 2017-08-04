<div class="modal" id="ModalCliente" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header btn-info">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Cliente</h4>
        </div>
        <div class="modal-body">
          <form id="ModalClienteForm">
            <div class="form-group">
              <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ModalListaempresa" data-filtros="estado:1" data-empresaid="ModalCliente #txt_empresa_id" data-empresa="ModalCliente #txt_empresa" data-personaid="ModalCliente #txt_persona_id" data-persona="ModalCliente #txt_persona">Empresa</a>
              <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ModalListapersona" data-filtros="estado:1" data-empresaid="ModalCliente #txt_empresa_id" data-empresa="ModalCliente #txt_empresa" data-personaid="ModalCliente #txt_persona_id" data-persona="ModalCliente #txt_persona">Persona</a>
            </div>
              <div class="form-group persona"  style="display: none">
              <label>Persona</label>
              <input type="hidden" class="form-control mant" id="txt_persona_id" name="txt_persona_id" readOnly="">
              <input type="text" class="form-control" id="txt_persona" name="txt_persona" disabled="">
            </div>
              <div class="form-group empresa" style="display: none"  >
              <label>Empresa</label>
              <input type="hidden" class="form-control mant" id="txt_empresa_id" name="txt_empresa_id" readOnly="">
              <input type="text" class="form-control" id="txt_empresa" name="txt_empresa" disabled="">
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
