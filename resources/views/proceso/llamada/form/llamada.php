<div class="modal" id="ModalLlamada" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Registro de llamada</h4>
            </div>
            <div class="modal-body">
                <form id="ModalLlamadaForm">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Alumno:</label>
                            <input class="form-control" type="text" id="txt_alumno" name="txt_alumno" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Teleoperadora:</label>
                            <input class="form-control" type="text" id="txt_teleoperadora" name="txt_teleoperadora" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha de Llamada:</label>
                            <input class="form-control" type="text" id="txt_fecha" name="txt_fecha" value="" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tipo Llamada:</label>
                            <select class="selectpicker form-control" id="slct_tipo_llamada" name="slct_tipo_llamada">
                                <option>.::Seleccione::.</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Comentario:</label>
                            <textarea rows="5" class="form-control" id="txt_comentario" name="txt_comentario">
                            </textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary pull-right">Confirmar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
