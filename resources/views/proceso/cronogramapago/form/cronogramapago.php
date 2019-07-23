<div class="modal" id="ModalEspecialidadProgramacion" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-lg">
      <div class="modal-content"> 
        <div class="modal-header btn-info">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Especialidad - Programación</h4>
        </div>
        <div class="modal-body"><!-- INICIO BODY-->
            <form id="ModalEspecialidadProgramacionForm"><!-- INICIO FORM-->
          <fieldset>
            <div class="form-group"><!-- INICIO FORM GROUP-->

              <div class="col-md-12">
                <label>Especialidad:</label>
                <select class="form-control" id="slct_especialidad_id" name="slct_especialidad_id"></select>
              </div>
              <div class="col-md-12">
                <div class="col-md-6">
                  <label>Odes:</label>
                  <select class="form-control selectpicker show-menu-arrow" multiple data-selected-text-format="count > 3" data-live-search="true"  data-actions-box='true' multiple id="slct_sucursal_id" name="slct_sucursal_id[]"></select>
                </div>
                <div class="col-md-6">
                  <label>Fecha de Inicio:</label>
                  <input type="text" class="form-control fecha" id="txt_fecha_inicio" name="txt_fecha_inicio" placeholder="YYYY-MM-DD">
                </div>
              </div>
              <!--div class="col-md-12">
                  <label>Horario:</label>
                  <select class="form-control selectpicker"  data-actions-box='true' multiple name="slct_horario[]" id="slct_horario">
                      <option value="LU">Lunes</option>
                      <option value="MA">Martes</option>
                      <option value="MI">Miercoles</option>
                      <option value="JU">Jueves</option>
                      <option value="VI">Viernes</option>
                      <option value="SA">Sabado</option>
                      <option value="DO">Domingo</option>
                  </select>
              </div-->
              <div class="col-md-12">  
                <div class="col-md-6">
                  <br>         
                    <label>Fecha de cronograma de pago:</label>
                    <br>
                    <input type="text" class="form-control fecha" id="txt_fecha_cronograma" placeholder="YYYY-MM-DD">
                </div>
                <div class="col-md-2">
                  <br>
                  <br>
                  <a onclick="CargarCronograma();" class="btn btn-info btn-flat">Agregar Cronograma<i class="fa fa-plus fa-lg"></i></a>
                </div>
                <div class="col-md-6" style="display: none;">    
                <br>
                  <label>Estado</label>
                  <select class="form-control selectpicker show-menu-arrow" name="slct_estado" id="slct_estado">
                    <option value='0'>Inactivo</option>
                    <option value='1' selected>Activo</option>
                  </select>
                </div>
              </div>
            <div class="col-md-12">
              <br>
                <table class="table table-bordered table-striped">
                  <thead class="bg-info">
                    <tr>
                      <th style='width:10% !important;'>Nro Cuota</th>
                      <th style='width:80% !important;'>Fecha</th>
                      <th style='width:10% !important;'>Eliminar</th>
                    </tr>
                  </thead>
                  <tbody id="sortable">
                  </tbody>
                </table>
            </div>

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
