<!-- /.modal -->
<div class="modal fade" id="ModalListaespecialidad" tabindex="-1" role="dialog" aria-hidden="true">
    <!-- <div class="modal fade" id="areaModal" tabindex="-1" role="dialog" aria-hidden="true"> -->
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Lista Programación</h4>
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
                                                    <label><h4>Especialidad:</h4></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                        <input type="text" class="form-control" name="txt_especialidad" id="txt_especialidad" placeholder="Especialidad" onkeypress="return masterG.enterGlobal(event, '.input-group', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-2">
                                                <div class="form-group">
                                                    <label><h4>Certificado de Especialidad:</h4></label>
                                                    <div class="input-group">
                                                        <div class="input-group-addon"><i class="fa fa-search"></i></div>
                                                        <input type="text" class="form-control" name="txt_certificado_especialidad" id="txt_certificado_especialidad" placeholder="Certificado Especialidad" onkeypress="return masterG.enterGlobal(event, '.input-group', 1);">
                                                    </div>
                                                </div>
                                            </th>
                                            <th class="col-xs-1">
                                                <div class="form-group">
                                                    <label><h4>Número de Veces:</h4></label>
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
                                            <th>Certificado de Especialidad</th>
                                            <th>N° de Veces</th>
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
