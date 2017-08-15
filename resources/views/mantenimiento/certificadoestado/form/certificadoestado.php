<div class="modal" id="ModalCertificadoEstado" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content"> 
        <div class="modal-header btn-info">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Certificado Estado</h4>
        </div>
        <div class="modal-body">
          <form id="ModalCertificadoEstadoForm">
            <div class="form-group">
              <div class="col-md-12">
              
                <label>Certificado Estado</label>
                <input type="text" class="form-control" id="txt_estado_certificado" name="txt_estado_certificado" placeholder="Certificado Estado">
              </div>
            

            <div class="col-md-12">
              <div class="form-group">
                <label>Detalle</label>
                <input type="text" class="form-control" id="txt_detalle" name="txt_detalle" placeholder="Detalle">
              </div>
            </div>

            <div class="col-md-12">
              <div class="col-sm-6">
                <label>Tiempo de Espera</label>
                <input type="text" class="form-control" id="txt_tiempo_espera" name="txt_tiempo_espera" placeholder="Tiempo de Espera">
              </div>

              <div class="col-sm-6">
                <label>Estado</label>
                  <select class="form-control" name="slct_estado" id="slct_estado">
                    <option value='0'>Inactivo</option>
                    <option value='1' selected>Activo</option>
                  </select>
              </div>
            </div>
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
