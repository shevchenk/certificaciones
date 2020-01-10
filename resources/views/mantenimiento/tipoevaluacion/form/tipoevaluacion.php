<div class="modal" id="ModalTipoEvaluacion" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content"> 
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tipo Evaluación</h4>
            </div>
            <div class="modal-body">
                <form id="ModalTipoEvaluacionForm">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Tipo Evaluación</label>
                                <input type="text" class="form-control" id="txt_tipo_evaluacion" name="txt_tipo_evaluacion">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nro Preguntas</label>
                                <select id="slct_nro_pregunta" name="slct_nro_pregunta" class="form-control selectpicker">
                                    <option value="">.::Seleccione::.</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="10">10</option>
                                    <option value="20">20</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Estado</label>
                                <select class="form-control selectpicker show-menu-arrow" name="slct_estado" id="slct_estado">
                                    <option  value='0'>Inactivo</option>
                                    <option  value='1'>Activo</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group"> 
                            <label></label>
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
