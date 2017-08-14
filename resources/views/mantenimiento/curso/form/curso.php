<div class="modal" id="ModalCurso" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content"> 
        <div class="modal-header btn-info">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Curso</h4>
        </div>
        <div class="modal-body"><!-- INICIO BODY-->
            <form id="ModalCursoForm"><!-- INICIO FORM-->
            <div class="form-group"><!-- INICIO FORM GROUP-->

              <div class="col-md-12">              
                <label>Curso</label>
                <input type="text" class="form-control" id="txt_curso" name="txt_curso" placeholder="Curso">
              </div>
            

            <div class="col-md-12">
              <div class="form-group">
                <label>Certificado Curso</label>
                <input type="text" class="form-control" id="txt_certificado_curso" name="txt_certificado_curso" placeholder="Certificado Curso">
              </div>
            </div>



            <div class="col-md-12"> <!-- INICIO CLASS 12 -->

              <div class="col-sm-6">
                <label>Tipo Curso</label>
                  <select class="form-control" name="slct_tipo_curso" id="slct_tipo_curso">
                    <option value='1'>Curso</option>
                    <option value='2' selected>Seminario</option>
                  </select>
              </div>


              <div class="col-sm-6">
                <label>Estado</label>
                  <select class="form-control" name="slct_estado" id="slct_estado">
                    <option value='0'>Inactivo</option>
                    <option value='1' selected>Activo</option>
                  </select>
              </div>
              
            </div> <!-- FIN CLASS 12 -->

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
