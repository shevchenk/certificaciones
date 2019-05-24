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
                            <input class="form-control mant" type="hidden" id="txt_persona_id" name="txt_persona_id" value="">
                            <input class="form-control" type="text" id="txt_alumno" name="txt_alumno" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Teleoperadora:</label>
                            <select class="selectpicker form-control show-menu-arrow" data-live-search="true" id="slct_teleoperadora" name="slct_teleoperadora">
                                <option>.::Seleccione::.</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha de Llamada:</label>
                            <input class="form-control fecha" type="text" id="txt_fecha" name="txt_fecha" value="" readonly>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Tipo Llamada:</label>
                            <select class="selectpicker form-control show-menu-arrow" onchange="ActivarComentario()" id="slct_tipo_llamada" name="slct_tipo_llamada">
                                <option>.::Seleccione::.</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4 tipo1" style="display: none;">
                        <div class="form-group">
                            <label class="fechadinamica">Fecha:</label>
                            <input class="form-control fechas" type="text" id="txt_fechas" name="txt_fechas" value="" readonly>
                        </div>
                    </div>
                    <div class="col-md-5 tipo2" style="display: none;">
                        <label>Sub Tipo Llamada:</label>
                            <select class="selectpicker form-control show-menu-arrow" onchange="ActivarDetalle()" id="slct_sub_tipo_llamada" name="slct_sub_tipo_llamada">
                                <option>.::Seleccione::.</option>
                            </select>
                    </div>
                    <div class="col-md-7 tipo2" style="display: none;">
                        <label>Detalle Tipo Llamada:</label>
                            <select class="selectpicker form-control show-menu-arrow" id="slct_detalle_tipo_llamada" name="slct_detalle_tipo_llamada">
                                <option>.::Seleccione::.</option>
                            </select>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Comentario:</label>
                            <textarea rows="5" class="form-control" id="txt_comentario" name="txt_comentario" disabled>
                            </textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="RegistrarLlamada();" class="btn btn-primary pull-right">Confirmar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>