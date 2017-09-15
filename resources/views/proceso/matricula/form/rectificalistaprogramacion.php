<!-- /.modal -->
<div class="modal fade" id="ModalListaprogramacion" tabindex="-1" role="dialog" aria-hidden="true">
    <!-- <div class="modal fade" id="areaModal" tabindex="-1" role="dialog" aria-hidden="true"> -->
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Lista de Cursos con Programación</h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="box">
                        <form id="ListaprogramacionForm">
                            <div class="box-body table-responsive no-padding">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Tipo Modalidad</label>
                                        <select  class="form-control selectpicker show-menu-arrow" id="slct_tipo_modalidad_id" name="slct_tipo_modalidad_id">
                                            <option value="0">.::Seleccione::.</option>
                                            <option value="1">Presencial</option>
                                            <option value="2">OnLine</option>
                                        </select>
                                    </div> 
                                </div>
                                <table id="TableListaprogramacion" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="cabecera">
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>Docente:</h4></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                        <input type="text" class="form-control" name="txt_docente" id="txt_docente" placeholder="Docente" onkeypress="return masterG.enterGlobal(event, '.input-group', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>ODE:</h4></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                        <input type="text" class="form-control" name="txt_sucursal" id="txt_sucursal" placeholder="ODE" onkeypress="return masterG.enterGlobal(event, '.input-group', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>Curso:</h4></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                        <input type="text" class="form-control" name="txt_curso" id="txt_curso" placeholder="Curso" onkeypress="return masterG.enterGlobal(event, '.input-group', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>Aula:</h4></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                        <input type="text" class="form-control" name="txt_aula" id="txt_aula" placeholder="Aula" onkeypress="return masterG.enterGlobal(event, '.input-group', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>Inicio:</h4></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                        <input type="text" class="form-control" name="txt_inicio" id="txt_inicio" placeholder="Inicio" onkeypress="return masterG.enterGlobal(event, '.input-group', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>Final:</h4></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                        <input type="text" class="form-control" name="txt_final" id="txt_final" placeholder="Final" onkeypress="return masterG.enterGlobal(event, '.input-group', 1);">
                                                    </div>
                                                </div>
                                            </th>

                                            <th class="col-xs-1">[-]</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr class="cabecera">
                                            <th>Docente</th>
                                            <th>ODE</th>
                                            <th>Curso</th>
                                            <th>Aula</th>
                                            <th>Inicio</th>
                                            <th>Final</th>
                                            <th>[-]</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div><!-- .box-body -->
                        </form><!-- .form -->
                    </div>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->



<!-- Actualizar los NRO PAGOS, MONTOS PAGOS-->
<div class="modal" id="ModalPagosMD" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content"> 
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">DETALLE MATRICULA - MONTOS DE PAGO</h4>
            </div>
            <div class="modal-body">
                <form id="ModalPagosMDForm">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nro. Pago</label>
                                    <input type="text" class="form-control" id="txt_nro_pago" name="txt_nro_pago">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Monto Pago</label>
                                    <input type="text" class="form-control" id="txt_monto_pago" name="txt_monto_pago">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Nro. Pago Certificado</label>
                                    <input type="text" class="form-control" id="txt_nro_pago_certificado" name="txt_nro_pago_certificado">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Monto Pago Certificado</label>
                                    <input type="text" class="form-control" id="txt_monto_pago_certificado" name="txt_monto_pago_certificado">
                                </div>
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
                <button type="button" class="btn btn-primary" onClick="actualizarPagosDMAjax()">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
<!-- -->