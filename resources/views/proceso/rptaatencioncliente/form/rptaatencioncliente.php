<div class="modal" id="ModalEntrega" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Respuesta de Atención al Cliente</h4>
            </div>
            <div class="modal-body">
                <form id="ModalEntregaForm">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Alumno:</label>
                            <input class="form-control mant" type="hidden" id="txt_persona_id" name="txt_persona_id" value="">
                            <input class="form-control" type="text" id="txt_alumno" name="txt_alumno" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Teléfono / Celular:</label>
                            <textarea class="form-control" id="txt_celular" name="txt_celular" disabled>
                            </textarea>
                        </div>
                    </div>
                    <div class="col-md-6 seminario">
                        <div class="form-group">
                            <label>Seminario:</label>
                            <input type="hidden" class="mant" name="txt_id" id="txt_id" value="">
                            <input class="form-control fecha" type="text" id="txt_seminario" name="txt_seminario" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-4 seminario">
                        <div class="form-group">
                            <label>Fecha de Seminario:</label>
                            <input class="form-control" type="text" id="txt_fecha_seminario" name="txt_fecha_seminario" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Comentario:</label>
                            <textarea class="form-control" id="txt_comentario" name="txt_comentario" disabled></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha de Registro:</label>
                            <input class="form-control fecha" type="text" id="txt_fecha_registro" name="txt_fecha_registro" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Respuesta:</label>
                            <textarea rows="5" class="form-control" id="txt_respuesta" name="txt_respuesta">
                            </textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="RegistrarEntrega();" class="btn btn-primary pull-right">Guardar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
