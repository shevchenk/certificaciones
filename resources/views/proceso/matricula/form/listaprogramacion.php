<!-- /.modal -->
<div class="modal fade" id="ModalListaprogramacion" tabindex="-1" role="dialog" aria-hidden="true">
    <!-- <div class="modal fade" id="areaModal" tabindex="-1" role="dialog" aria-hidden="true"> -->
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Lista de seminarios programados</h4>
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
                                                        <input type="text" class="form-control" name="txt_docente" id="txt_docente" placeholder="Docente" onkeypress="return masterG.enterGlobal(event, '#txt_sucursal', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>Local De Estudios:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_sucursal" id="txt_sucursal" placeholder="ODE" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>Seminario:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_curso" id="txt_curso" placeholder="Seminario" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2" style="display: none;">
                                                <div class="form-group">
                                                    <label><h4>Frecuencia:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_dia" id="txt_dia" placeholder="Aula" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2" style="display: none;">
                                                <div class="form-group">
                                                    <label><h4>Aula:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_aula" id="txt_aula" placeholder="Aula" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>Inicio:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_inicio" id="txt_inicio" placeholder="Inicio" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>Final:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_final" id="txt_final" placeholder="Final" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
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
                                            <th>Semianrio</th>
                                            <th style="display: none;">Frecuencia</th>
                                            <th style="display: none;">Aula</th>
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
                <button type="button" class="btn btn-default active " onClick="ValidaCheck();">Cargar Seleccionados</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->
