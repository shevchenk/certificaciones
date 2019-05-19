<div class="modal" id="ModalComentario" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Programación</h4>
            </div>
            <div class="modal-body">
                <form id="ModalComentarioForm">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Fecha del Seminario:</label>
                            <input class="form-control" type="text" id="txt_fecha" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Seminario:</label>
                            <input class="form-control" type="text" id="txt_curso" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Comentario del Seminario:</label>
                            <textarea rows="10" class="form-control" placeholder="Ingrese Comentario" id="txt_comentario" name="txt_comentario"></textarea>
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
