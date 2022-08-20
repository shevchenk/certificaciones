<div class="modal" id="ModalCentroOperacion" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content"> 
        <div class="modal-header btn-info">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Centro de Operación</h4>
        </div>
        <div class="modal-body"><!-- INICIO BODY-->
            <form id="ModalCentroOperacionForm" name="ModalCentroOperacionForm"><!-- INICIO FORM-->
            <fieldset>
              <div class="form-group"><!-- INICIO FORM GROUP-->

                <div class="col-md-12"><!-- INICIO CLASS 12 -->       
                  <div class="col-md-6">
                    <label>Centro Operacion</label>
                    <input type="text" class="form-control" id="txt_centro_operacion" name="txt_centro_operacion" placeholder="Centro Operacion">
                  </div>
                  <div class="col-md-6">
                    <label>Dirección</label>
                    <input type="text" class="form-control" id="txt_direccion" name="txt_direccion" placeholder="Dirección">
                  </div>
                </div><!-- FIN CLASS 12 -->

                <div class="col-md-12"> <!-- INICIO CLASS 12 -->
                  <div class="col-sm-6 paisafectado">
                      <label>Distrito Nacimiento:</label>
                      <input type="hidden" class="mant" id="txt_distrito_id" name="txt_distrito_id">
                      <input type="hidden" class="mant" id="txt_provincia_id" name="txt_provincia_id">
                      <input type="hidden" class="mant" id="txt_region_id" name="txt_region_id">
                      <div id="txt_distrito_ico" class="has-error has-feedback">
                          <input type="text" class="form-control" onblur="Limpiar('txt_distrito_id,#txt_provincia_id,#txt_region_id,#txt_provincia,#txt_region');" id="txt_distrito" placeholder="Distrito">
                          <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                      </div>
                  </div>
                  <div class="col-sm-3 paisafectado2">
                      <label>Provincia:</label>
                      <input type="text" disabled class="form-control" id="txt_provincia" placeholder="Provincia">
                  </div>
                  <div class="col-sm-3 paisafectado2">
                      <label>Región:</label>
                      <input type="text" disabled class="form-control" id="txt_region" placeholder="Región">
                  </div>

                </div>
                <div class="col-md-12"> <!-- INICIO CLASS 12 -->
                  <div class="col-md-6">
                  <br>    
                      <label>Estado</label>
                        <select class="form-control" name="slct_estado" id="slct_estado">
                          <option value='0'>Inactivo</option>
                          <option value='1' selected>Activo</option>
                        </select>               
                  </div>
                </div> <!-- FIN CLASS 12 -->

            </div> <!-- FIN FORM GROUP-->
          </fieldset>
          </form><!-- FIN FORM-->
        </div><!-- FIN BOODY-->
        <div class="modal-footer">
          <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
