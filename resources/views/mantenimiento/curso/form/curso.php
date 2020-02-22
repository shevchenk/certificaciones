<div class="modal" id="ModalCurso" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content"> 
        <div class="modal-header btn-info">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Iniciao / Curso</h4>
        </div>
        <div class="modal-body"><!-- INICIO BODY-->
            <form id="ModalCursoForm" name="ModalCursoForm"><!-- INICIO FORM-->
            <fieldset>
              <div class="form-group"><!-- INICIO FORM GROUP-->

                <div class="col-md-12"><!-- INICIO CLASS 12 -->       
                  <div class="col-md-6">       
                    <label>Inicio / Curso</label>
                    <input type="text" class="form-control" id="txt_curso" name="txt_curso" placeholder="Curso">
                  </div>

                  <div class="col-md-6">          
                    <label>Certificado Curso</label>
                    <input type="text" class="form-control" id="txt_certificado_curso" name="txt_certificado_curso" placeholder="Certificado Curso">       
                  </div>
                </div><!-- FIN CLASS 12 -->

                


              

                <div class="col-md-12"> <!-- INICIO CLASS 12 -->
                  <div class="col-md-3">     
                      <br>
                      <label>Apocope</label>
                        <input type="text" class="form-control" id="txt_curso_apocope" name="txt_curso_apocope" placeholder="Curso Apocope">
                  </div>

                  <div class="col-md-3">
                      <br>
                      <label># Horas académicas</label>
                        <input type="text" class="form-control" id="txt_hora" name="txt_hora" onkeypress="return masterG.validaNumerosMax(event,this,4);" placeholder="Horas académicas">
                  </div>

                  <div class="col-md-3">
                      <br>
                      <label># Créditos</label>
                        <input type="text" class="form-control" id="txt_credito" name="txt_credito" onkeypress="return masterG.validaNumerosMax(event,this,2);" placeholder="Créditos">
                  </div>

                  <div class="col-md-5" style="display: none;">
                      <br>
                      <label>Tipo Curso</label>
                        <select class="form-control selectpicker show-menu-arrow" data-live-search="true" name="slct_tipo_curso" id="slct_tipo_curso">
                          <option value="0">.::Seleccione::.</option>
                          <option value='1' selected>Curso</option>
                          <option value='2'>Seminario</option>
                        </select>     
                  </div>

                  <div class="col-md-3">
                      <br>
                      <label>Tipo Inicio / Curso</label>
                        <select class="form-control selectpicker show-menu-arrow" data-live-search="true" name="slct_tipo_inicio_curso" id="slct_tipo_inicio_curso">
                          <option value="0" selected>.::Seleccione::.</option>
                          <option value='1'>Inicio</option>
                          <option value='2'>Curso</option>
                        </select>     
                  </div>

                  <div class="col-md-3" style="display: none;">
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
