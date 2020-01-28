<div class="modal" id="ModalTipoLlamada" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tipo Llamada</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                <form id="ModalTipoLlamadaForm">
                    <div class="col-md-12">
                        <div class="col-md-7 col-xs-7">
                            <label>Tipo de Llamada:</label>
                            <input type="text" class="form-control" id="txt_tipo_llamada" name="txt_tipo_llamada">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-5 col-xs-5">
                            <label>Indique su peso:</label>
                            <select class="form-control selectpicker" id="slct_peso" name="slct_peso">
                                <option value="0" selected>0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                                <option value="6">6</option>
                                <option value="7">7</option>
                                <option value="8">8</option>
                                <option value="9">9</option>
                                <option value="10">10</option>
                            </select>
                        </div>
                        <div class="col-md-5 col-xs-5">
                            <label>Acción a realizar:</label>
                            <select class="form-control selectpicker" id="slct_obs" name="slct_obs">
                                <option value="0" selected>Ninguna</option>
                                <option value="1">Ver Fecha de Interesado</option>
                                <option value="2">Ver Fecha de Pendiente</option>
                                <option value="3">Ver Detalle</option>
                            </select>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary pull-right">Guardar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
