<div class="modal" id="ModalProgramacion" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Programación</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                <form id="ModalProgramacionForm">
                    <div class="col-md-8">
                        <label>Tipos de Evaluaciones</label>
                        <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_tipo_evaluacion" name="slct_tipo_evaluacion">
                            <option value="0">.::Seleccione::.</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <br>
                        <a onclick="ValidarTipoEvaluacion();" class="btn btn-primary btn-flat">Agregar Tipo de Evaluación</a>
                    </div>
                    <div class="col-md-12">
                        <br><br>
                        <table class="table table-bordered table-hover">
                            <thead>
                                <tr>
                                    <th>Tipo Evaluación</th>
                                    <th>Fecha Programada</th>
                                    <th>Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id='tb_te'>
                            </tbody>
                        </table>
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
