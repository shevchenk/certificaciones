<div class="modal" id="ModalProgramacion" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
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
                    <div class="col-md-12">
                        <label>Local de Estudios:</label>
                        <input type="text" class="form-control" id="txt_sucursal" disabled>
                    </div>
                    <div class="col-md-12">
                        <label>Docente:</label>
                        <input type="text" class="form-control" id="txt_docente" disabled>
                    </div>
                    <div class="col-md-12">
                        <label>Curso:</label>
                        <input type="text" class="form-control" id="txt_curso" disabled>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-7 col-xs-7">
                            <label>Tiene Proyecto Final:</label>
                            <select onchange="validaProyectoFinal(this);" class="form-control selectpicker" id="slct_proyecto_final" name="slct_proyecto_final">
                                <option value="1">Si</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                        <div class="col-md-5 col-xs-5">
                            <label>Indique su peso:</label>
                            <select class="form-control selectpicker" id="slct_peso_proyecto_final" name="slct_peso_proyecto_final">
                                <option value="0" selected>0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br>
                        <div class="col-md-7">
                            <label>Tipos de Evaluaciones:</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_tipo_evaluacion">
                                <option value="">.::Seleccione::.</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <br>
                            <a onclick="ValidarTipoEvaluacion();" class="btn btn-info btn-flat">Agregar Cronograma<i class="fa fa-plus fa-lg"></i></a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br><br>
                        <table class="table table-bordered table-hover">
                            <thead class="bg-info">
                                <tr>
                                    <th style="text-align: center;" rowspan="2">Tipo Evaluación</th>
                                    <th style="text-align: center;" rowspan="2">Peso Evaluación</th>
                                    <th style="text-align: center;" colspan="2">Programación de evaluación</th>
                                    <th style="text-align: center;" rowspan="2">Eliminar</th>
                                </tr>
                                <tr>
                                    <th style="text-align: center;">Fecha Inicio</th>
                                    <th style="text-align: center;">Fecha Final</th>
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
