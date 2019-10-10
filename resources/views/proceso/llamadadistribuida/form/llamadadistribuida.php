<div class="modal" id="ModalLlamada" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Registro de Llamadas</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                <form id="ModalLlamadaForm">
                    <div class="col-md-12">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Alumno:</label>
                            <input class="form-control mant" type="hidden" id="txt_persona_id" name="txt_persona_id" value="">
                            <input class="form-control" type="text" id="txt_alumno" name="txt_alumno" value="" disabled>
                            <a class="btn btn-primary btn-sm" id="btnEditPersona" onClick="EditarPersona()"><i class="fa fa-edit fa-lg"></i> </a>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Teléfono/Celular:</label>
                            <textarea class="form-control" id="txt_celular" name="txt_celular" value="" disabled></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha distribuida:</label>
                            <input class="form-control" type="text" id="txt_fecha_distribucion" name="txt_fecha_distribucion" value="" disabled>
                        </div>
                    </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Carrera:</label>
                            <input class="form-control" type="text" id="txt_carrera" name="txt_carrera" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Empresa:</label>
                            <input class="form-control" type="text" id="txt_empresa" name="txt_empresa" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fuente:</label>
                            <input class="form-control" type="text" id="txt_fuente" name="txt_fuente" value="" disabled>
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
                            <label>Teleoperador(a):</label>
                            <input class="form-control" type="text" id="txt_teleoperadora" name="txt_teleoperadora" value="<?php echo Auth::user()->paterno.' '.Auth::user()->materno.', '.Auth::user()->nombre; ?>" disabled>
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
                        <div class="col-md-4">
                            <label>Objeción:</label>
                            <textarea rows="5" class="form-control" id="txt_objecion" name="txt_objecion"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label>Preguntas:</label>
                            <textarea rows="5" class="form-control" id="txt_pregunta" name="txt_pregunta"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label>Comentario:</label>
                            <textarea rows="5" class="form-control" id="txt_comentario" name="txt_comentario" disabled></textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead style="background-color: #F4AA39;color:black">
                                <tr>
                                    <th colspan="2" style="text-align: center;"> Información Solicitada </th>
                                </tr>
                                <tr>
                                    <th>Fecha de la información</th>
                                    <th>Información</th>
                                </tr>
                            </thead>
                            <tbody id="tb_info">
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped">
                            <thead style="background-color: #A9D08E;color:black">
                                <tr>
                                    <th colspan="5" style="text-align: center;"> Historico </th>
                                </tr>
                                <tr>
                                    <th>Fecha de Llamada</th>
                                    <th>Teleoperador(a)</th>
                                    <th>Tipo Llamada</th>
                                    <th>Fecha Programada</th>
                                    <th>Comentario</th>
                                </tr>
                            </thead>
                            <tbody id="tb_llamada">
                            </tbody>
                        </table>
                    </div>
                </div>
                </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="RegistrarLlamada();" class="btn btn-primary pull-right">Confirmar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
