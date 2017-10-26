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
            <!-- <input type='hidden' class="mant" name='id' name="id"> -->
              <fieldset>
              <legend>Datos del Pago</legend>
              <div class="form-group"> 

                <div class="col-sm-12"> 
    <!--            <div class="col-md-2 form-group">-->
                  <label>Tipo de Pago:</label>
                    <select class="form-control" id="slct_tipo_pago" name="slct_tipo_pago">
                      <option value="1" selected>Boleta</option>
                      <option value="2">Voucher</option>
                      <option value="3">Factura</option>
                    </select>
                </div>

                <div class="col-sm-12"> 
    <!--            <div class="col-md-2 form-group">-->
                  <label>N° del Pago:</label>
                    <input type="text" class="form-control mant" id="txt_nro_pago" name="txt_nro_pago" value="">
                </div>
              
                <div class="col-md-12 txt_montboleta">
                    <div class="form-group">
                      <label>Monto Pagado</label>
                        <input type="text" onkeyup="masterG.DecimalMax(this, 2);" onkeypress="return masterG.validaDecimal(event, this);" class="form-control" id="txt_monto_pago" name="txt_monto_pago">
                    </div>
                </div>

                <div class="col-sm-12"> 
    <!--            <div class="col-md-2 form-group">-->
                  <label>Fecha del Pago:</label>
                    <div class="input-group">
                      <span id="spn_fecha_pago" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_pago" name="txt_fecha_pago" readonly/>
                    </div>
                </div>

              </div>
            </form><!-- FIN FORM-->
        </div><!-- FIN BOODY-->






        <div class="modal-footer">
          <button type="button" id="btnclose" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
          <button type="button" class="btn btn-primary" onClick="CambiarEstado_PagoAlumno();">Guardar</button>
        </div>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>


