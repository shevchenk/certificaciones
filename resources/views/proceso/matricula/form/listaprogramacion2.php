<!-- /.modal -->
<div class="modal fade" id="ModalListaprogramacion2" tabindex="-1" role="dialog" aria-hidden="true">
    <!-- <div class="modal fade" id="areaModal" tabindex="-1" role="dialog" aria-hidden="true"> -->
    <div class="modal-dialog modal-full">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Lista de Inicios / Cursos programados</h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="box">
                        <form id="ListaprogramacionForm2">
                            <div class="box-body table-responsive no-padding">
                                <table id="TableListaprogramacion2" class="table table-bordered table-hover">
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
                                                    <label><h4>Inicio / Curso:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_curso" id="txt_curso" placeholder="Curso" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                             <th class="col-xs-1">
                                                <div class="form-group">
                                                    <label><h4>Turno:</h4></label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="slct_turno" id="slct_turno">
                                                            <option value='' selected>.::Todo::.</option>
                                                            <option value='M'>Mañana</option>
                                                            <option value='T'>Tarde</option>
                                                            <option value='N'>Noche</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>Frecuencia:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_dia" id="txt_dia" placeholder="Aula" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>Aula:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_aula" id="txt_aula" placeholder="Aula" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2" style="min-width: 120px;">
                                                <div class="form-group">
                                                    <label><h4>Fecha Inicio:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_inicio" id="txt_inicio" placeholder="Fecha Inicio" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2" style="min-width: 120px;">
                                                <div class="form-group">
                                                    <label><h4>Fecha Final:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_final" id="txt_final" placeholder="Fecha Final" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2" style="min-width: 100px;">
                                                <div class="form-group">
                                                    <label><h4>Hora Inicio:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_hora_inicio" id="txt_inicio" placeholder="Hora Inicio" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2" style="min-width: 100px;">
                                                <div class="form-group">
                                                    <label><h4>Hora Final:</h4></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="txt_hora_final" id="txt_final" placeholder="Hora Final" onkeypress="return masterG.enterGlobal(event, '#txt_docente', 1);">
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
                                            <th>Turno</th>
                                            <th>Frecuencia</th>
                                            <th>Aula</th>
                                            <th>Fecha Inicio</th>
                                            <th>Fecha Final</th>
                                            <th>Hora Inicio</th>
                                            <th>Hora Final</th>
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
                <button type="button" class="btn btn-default active " onClick="ValidaCheck2();">Cargar Seleccionados</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->
