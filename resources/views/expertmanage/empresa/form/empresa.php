<div class="modal" id="ModalEmpresa" tabindex="-1" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header btn-info">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Empresa</h4>
        </div>
        <div class="modal-body">
          <form id="ModalEmpresaForm" name="ModalEmpresaForm">
          

            <div class="form-group">

            <div class="col-md-12"> <!--INICIO DE COL SM 12-->
              <label>Persona</label>
            </div>

            <div class="input-group margin">              
              <input type="hidden" class="form-control mant" id="txt_persona_id" name="txt_persona_id" readonly="">
              <input type="text" class="form-control" id="txt_persona" name="txt_persona" readonly="">
                
                <span class="input-group-btn">
                    <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#ModalListapersona" data-filtros="estado:1" data-empresaid="ModalEmpresa #txt_empresa_id" data-empresa="ModalEmpresa #txt_empresa" data-personaid="ModalEmpresa #txt_persona_id" data-persona="ModalEmpresa #txt_persona">Persona</button>
                </span>
                         
            </div>

            <div class="col-md-12">
                <div class="col-sm-6">
                  <label>Razon Social</label>
                  <input type="text"  class="form-control" id="txt_razon_social" name="txt_razon_social" placeholder="Razon Social">
                </div>

                <div class="col-sm-6">
                  <label>Ruc</label>
                  <input type="text" onkeypress="return masterG.validaNumerosMax(event,this,11);" class="form-control" id="txt_ruc" name="txt_ruc" placeholder="Ruc">
                </div>
            </div>
         
            <div class="col-md-12"> <!--INICIO DE COL SM 12--> 
                <div class="col-sm-6">
                  <label>Nombre_Comercial</label>
                  <input type="text" class="form-control" id="txt_nombre_comercial" name="txt_nombre_comercial" placeholder="Nombre_Comercial">
                </div>
                <div class="col-sm-6">
                  <label>Email</label>
                  <input type="text" class="form-control" id="txt_email" name="txt_email" placeholder="Email">
                </div>      
            </div>

            <div class="col-md-12"> <!--INICIO DE COL SM 12-->
                  <label>Direccion Fiscal</label>
                  <input type="text" class="form-control" id="txt_direccion_fiscal" name="txt_direccion_fiscal" placeholder="Direccion Fiscal">
            </div>

            <div class="col-md-12"> <!--INICIO DE COL SM 12-->
                <div class="col-sm-4">
                  <label>Telefono</label>
                  <input type="text" onkeypress="return masterG.validaNumerosMax(event,this,7);" class="form-control" id="txt_telefono" name="txt_telefono" placeholder="Telefono">
                </div>

                <div class="col-sm-4">
                  <label>Celular</label>
                  <input type="text" onkeypress="return masterG.validaNumerosMax(event,this,9);" class="form-control" id="txt_celular" name="txt_celular" placeholder="Celular">
                </div>
            

                <div class="col-sm-4">
                  <label>Estado</label>
                  <select class="form-control" name="slct_estado" id="slct_estado">
                    <option value='0'>Inactivo</option>
                    <option value='1' selected>Activo</option>
                  </select>
                </div>
            </div>
            <div class="form-group"> 
                         <label></label>
           </div>
            <div class="col-md-8">
                <div class="form-group">
                            <label>Imagen</label>
                            <input type="text"  readOnly class="form-control input-sm" id="txt_imagen_nombre"  name="txt_imagen_nombre" value="">
                            <input type="text" style="display: none;" id="txt_imagen_archivo" name="txt_imagen_archivo">
                            <label class="btn btn-default btn-flat margin btn-xs">
                                <i class="fa fa-file-image-o fa-lg"></i>
                                <input type="file" style="display: none;" onchange="onImagen(event);" >
                            </label>

                </div>  
            </div> 

            <div class="col-md-4">
                        <div class="form-group">
                            <img class="img-circle" style="height: 142px;width: 100%;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px"> 
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
