<div class="modal" id="ModalEspecialidad" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content"> 
        <div class="modal-header btn-info">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Carrera / Módulo</h4>
        </div>
        <div class="modal-body"><!-- INICIO BODY-->
            <form id="ModalEspecialidadForm"><!-- INICIO FORM-->
          <fieldset>
            <div class="form-group"><!-- INICIO FORM GROUP-->

              <div class="col-md-12">   

                <div class="col-sm-6">       
                  <label>Carrera / Módulo</label>
                  <input type="text" class="form-control" id="txt_especialidad" name="txt_especialidad" placeholder="Carrera / Módulo">
                </div>

                <div class="col-md-6">
                  <label>Certificado Carrera / Módulo</label>
                  <input type="text" class="form-control" id="txt_certificado_especialidad" name="txt_certificado_especialidad" placeholder="Certificado Carrera / Módulo">
                  </div>

              </div>
            <div class="col-md-12">  
              <div class="col-md-6">
                  <br>         
                      <label>Inicio / Curso</label>
                      <br>
                        <select class="selectpicker form-control show-menu-arrow"  data-actions-box="true" data-live-search="true" id="slct_curso_id">
                        </select>
              </div>
              <div class="col-md-2">
                  <br>
                  <br>
                  <a onclick="AgregarCurso();" class="btn btn-info btn-flat">Agregar Inicio / Curso<i class="fa fa-plus fa-lg"></i></a>
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
                      <th style='width:10% !important;'>Nro</th>
                      <th style='width:80% !important;'>Inicio / Curso</th>
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
