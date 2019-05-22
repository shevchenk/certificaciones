<div class="modal" id="ModalEntrega" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Entrega de Certificado</h4>
            </div>
            <div class="modal-body">
                <form id="ModalEntregaForm">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label>Alumno:</label>
                            <input class="form-control mant" type="hidden" id="txt_persona_id" name="txt_persona_id" value="">
                            <input class="form-control" type="text" id="txt_alumno" name="txt_alumno" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Seminario:</label>
                            <input type="hidden" class="mant" name="txt_id" id="txt_id" value="">
                            <input class="form-control fecha" type="text" id="txt_seminario" name="txt_seminario" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha de Seminario:</label>
                            <input class="form-control" type="text" id="txt_fecha_seminario" name="txt_fecha_seminario" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha que se entregó Certificado:</label>
                            <input class="form-control fecha" type="text" id="txt_fecha_entrega" name="txt_fecha_entrega" value="" readonly>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Comentario:</label>
                            <textarea rows="5" class="form-control" id="txt_comentario_entrega" name="txt_comentario_entrega">
                            </textarea>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="RegistrarEntrega();" class="btn btn-primary pull-right">Confirmar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
