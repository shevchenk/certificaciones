<div class="modal" id="ModalBoleta" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content"> 

        <div class="modal-header btn-info">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span></button>
           <h4 class="modal-title">Bandeja</h4>
        </div>

        <div class="modal-body"><!-- INICIO BODY-->
            <form id="ModalBoletaForm" name="ModalBoletaForm"><!-- INICIO FORM-->
              <fieldset>
          <legend>Datos Boleta</legend>
          <div class="form-group"> 

            <div class="col-sm-12"> 
<!--            <div class="col-md-2 form-group">-->
              <label>N° Boleta:</label>
                <input type="text" class="form-control mant" id="txt_nboleta" name="txt_nboleta" value="0">
            </div>
          
            <div class="col-md-12 txt_montboleta">
                <div class="form-group">
                  <label>Monto Boleta</label>
                    <input type="text" onkeyup="masterG.DecimalMax(this, 2);" onkeypress="return masterG.validaDecimal(event, this);" class="form-control" id="txt_montboleta" name="txt_montboleta">
                </div>
            </div>

          </div>


            </form><!-- FIN FORM-->
        </div><!-- FIN BOODY-->






        <div class="modal-footer">
          <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onClick="CambiarEstado_PagoAlumno();">Guardar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


