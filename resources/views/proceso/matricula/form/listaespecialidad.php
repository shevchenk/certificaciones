<!-- /.modal -->
<div class="modal fade" id="ModalListaespecialidad" tabindex="-1" role="dialog" aria-hidden="true">
    <!-- <div class="modal fade" id="areaModal" tabindex="-1" role="dialog" aria-hidden="true"> -->
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Lista de Especialidades</h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="box">
                        <form id="ListaespecialidadForm">
                            <div class="box-body table-responsive no-padding">
                                <table id="TableListaespecialidad" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="cabecera">
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                    <label><h4>Especialidad:<button id="btnbuscar" type="button" class="btn btn-default"><i class="fa fa-search"></i></button></h4></label>
                                                    </div>
                                                    <input type="text" class="form-control" name="txt_docente" id="txt_docente" placeholder="Especialidad" onkeypress="return masterG.enterGlobal(event, '#btnbuscar', 1);">
                                                </div>
                                            </th>
                                            <th class="col-xs-9">
                                                <div class="form-group">
                                                    <label><h4>Cursos:</h4></label>
                                                </div>
                                            </th>
                                            <th class="col-xs-1">[-]</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr class="cabecera">
                                            <th>Especialidad</th>
                                            <th>Cursos</th>
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
