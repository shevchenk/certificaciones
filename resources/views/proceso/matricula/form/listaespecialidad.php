<!-- /.modal -->
<div class="modal fade" id="ModalListaespecialidad" tabindex="-1" role="dialog" aria-hidden="true">
    <!-- <div class="modal fade" id="areaModal" tabindex="-1" role="dialog" aria-hidden="true"> -->
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Lista de Carreras / Módulos</h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="box">
                        <form id="ListaespecialidadForm">
                            <div class="col-md-12">
                                <label>Ode de estudios</label>
                                <select  class="form-control selectpicker show-menu-arrow" id="slct_ode_estudio_id" name="slct_ode_estudio_id">
                                </select>
                            </div>
                            <div class="box-body table-responsive no-padding">
                                </div>
                                <table id="TableListaespecialidad" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="cabecera">
                                            <th class="col-md-2">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                    <label><h4>Carrera / Módulo:</h4></label>
                                                    </div>
                                                    <input type="text" class="form-control" name="txt_especialidad" id="txt_especialidad" placeholder="Carrera / Módulo" onkeypress="return masterG.enterGlobal(event, '#txt_fecha_inicio', 1);" style="width: 150px;">
                                                </div>
                                            </th>
                                            <th class="col-md-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                    <label><h4>Fecha de Inicio:</h4></label>
                                                    </div>
                                                    <input type="text" class="form-control" name="txt_fecha_inicio" id="txt_fecha_inicio" placeholder="Fecha de Inicio" onkeypress="return masterG.enterGlobal(event, '#txt_especialidad', 1);" style="width: 120px;">
                                                </div>
                                            </th>
                                            <th class="col-md-8">
                                                <div class="form-group">
                                                    <label><h4>Inicio / Curso:</h4></label>
                                                </div>
                                            </th>
                                            <th class="col-md-1">[-]</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr class="cabecera">
                                            <th>Carrera / Módulo</th>
                                            <th>Inicio / Curso</th>
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
