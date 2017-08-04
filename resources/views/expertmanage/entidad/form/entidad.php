<div class="modal" id="ModalEntidad" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header btn-info">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Entidad</h4>
        </div>
        <div class="modal-body">
          <form id="ModalEntidadForm" name="ModalEntidadForm">
          <fieldset>

            <div class="form-group">
            <div class="col-sm-12"> <!--INICIO DE COL SM 12-->
              <label>Persona</label>
              <input type="text" class="form-control" id="txt_persona" name="txt_persona" readonly="">
              <input type="hidden" class="form-control mant" id="txt_persona_id" name="txt_persona_id" readonly="">
              
              <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ModalListapersona" data-empresaid="ModalEntidad #txt_empresa_id" data-empresa="ModalEntidad #txt_empresa" data-personaid="ModalEntidad #txt_persona_id" data-persona="ModalEntidad #txt_persona">Persona</a>
            </div> <!--FIN DE COL SM 12-->
          

              <div class="col-sm-12"> <!--INICIO DE COL SM 12-->
              <div class="col-sm-4">
              <label>Entidad</label>
              <input type="text" class="form-control" id="txt_entidad" name="txt_entidad" placeholder="Entidad">
              </div>
           
              <div class="col-sm-4">
              <label>Nombre Comercial</label>
              <input type="text" class="form-control" id="txt_nombre_comercial" name="txt_nombre_comercial" placeholder="Nombre Comercial">
              </div>
         
              <div class="col-sm-4">
              <label>Ruc</label>
              <input type="text" class="form-control" id="txt_ruc" name="txt_ruc" placeholder="Ruc">
              </div>
              </div>

              <div class="col-sm-12"> <!--INICIO DE COL SM 12-->
              <div class="col-sm-4">
              <label>IGV</label>
              <input type="text" class="form-control" id="txt_igv" name="txt_igv" placeholder="IGV">
              </div>

              <div class="col-sm-4">
              <label>Cambio de Moneda</label>
              <input type="text" class="form-control" id="txt_cambio_moneda" name="txt_cambio_moneda" placeholder="Cambio de Moneda">
              </div>

              <!--<div class="col-sm-4">
              <label>Celular</label>
              <input type="text" class="form-control" id="txt_celular" name="txt_celular" placeholder="Celular">
              </div>-->
            </div>

              <div class="col-sm-12"> <!--INICIO DE COL SM 12-->
              <div class="col-sm-4">
              <label>Email</label>
              <input type="text" class="form-control" id="txt_email" name="txt_email" placeholder="Email">
              </div>
            

            <!--<div class="form-group">
              <label>Logo</label>
              <input type="text" class="form-control" id="txt_Logo" name="txt_Logo" placeholder="Logo">
            </div>-->

              <div class="col-sm-4">
              <label>Estado</label>
              <select class="form-control" name="slct_estado" id="slct_estado">
                <option value='0'>Inactivo</option>
                <option value='1' selected>Activo</option>
              </select>
              </div>
              </div>
            </div>
            </fieldset>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary">Save</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
