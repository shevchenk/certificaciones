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
                  <label>Tipo de Programación:</label>
                  <select class="form-control selectpicker show-menu-arrow" data-actions-box='true' onchange="ValidaTipo(this.value);" id="slct_tipo" name="slct_tipo">
                    <option value="">.::Seleccione::.</option>
                    <option value="1">Pago en Cuota(s)</option>
                    <option value="2">Pago por Curso</option>
                  </select>
                </div>
                <div class="col-md-6">
                  <label>Odes:</label>
                  <select class="form-control selectpicker show-menu-arrow" multiple data-selected-text-format="count > 3" data-live-search="true"  data-actions-box='true' multiple id="slct_sucursal_id" name="slct_sucursal_id[]"></select>
                </div>
              </div>
              <div class="col-md-12">
                <div class="col-md-6">
                  <label>Fecha de Inicio:</label>
                  <input type="text" class="form-control fecha" id="txt_fecha_inicio" name="txt_fecha_inicio" placeholder="YYYY-MM-DD">
                </div>
              </div>
            </div>
          </fieldset>
          <br><br>
          <fieldset>
            <div class="form-group validatipo">
              <legend>Programación del Cronograma</legend>
              <div class="col-md-12">  
                <div class="col-md-4">
                    <label>Escala:</label>
                    <br>
                    <div class="input-group">
                      <select name="slct_nro_cuota" id="slct_nro_cuota" class="form-control selectpicker">
                        <option value="">.::Seleccione::.</option>
                        <?php 
                          for($i=1; $i<=24; $i++){
                            echo "<option value='".$i."C'>".$i."</option>";
                          }
                        ?>
                      </select>
                      <span class="input-group-addon"><i class="fa fa-repeat"></i></span>
                      <input type="text" name="txt_monto_cuota" id="txt_monto_cuota" onkeypress="return masterG.validaDecimal(event, this);" onkeyup="return masterG.DecimalMax(this,2);" class="form-control">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Inscripción:</label>
                        <input type="text" onkeypress="return masterG.validaDecimal(event, this);" onkeyup="return masterG.DecimalMax(this,2);" class="form-control" id="txt_costo" name="txt_costo" value="0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label>Matrícula:</label>
                        <input type="text" onkeypress="return masterG.validaDecimal(event, this);" onkeyup="return masterG.DecimalMax(this,2);" class="form-control" id="txt_costo_mat" name="txt_costo_mat" value="0">
                    </div>
                </div>
              </div>
              <div class="col-md-12">  
                <div class="col-md-4">
                  <br>         
                    <label>Fecha de cronograma de pago:</label>
                    <br>
                    <input type="text" class="form-control fecha" id="txt_fecha_cronograma" placeholder="YYYY-MM-DD">
                </div>
                <div class="col-md-4">
                  <br>         
                    <label>Monto de cronograma de pago:</label>
                    <br>
                    <input type="text" class="form-control" id="txt_monto_cronograma" placeholder="0.00" onkeypress="return masterG.validaDecimal(event, this);" onkeyup="return masterG.DecimalMax(this,2);">
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
                      <th style='width:40% !important;'>Fecha</th>
                      <th style='width:40% !important;'>Monto</th>
                      <th style='width:10% !important;'>Eliminar</th>
                    </tr>
                  </thead>
                  <tbody id="sortable">
                  </tbody>
                </table>
            </div>

          </div> <!-- FIN FORM GROUP-->
          
          </form><!-- FIN FORM-->
        </div><!-- FIN BOODY-->
        <div class="modal-footer">
          <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
