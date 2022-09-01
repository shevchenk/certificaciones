<!-- /.modal -->
<div class="modal fade" id="ModalLlamada" tabindex="-1" role="dialog" aria-hidden="true">
    <!-- <div class="modal fade" id="areaModal" tabindex="-1" role="dialog" aria-hidden="true"> -->
    <div class="modal-dialog modal-xlg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Historial de Llamadas</h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <div class="box">
                        <form id="LlamadaForm">
                            <label>Trabajador:</label>
                            <label class="form-control" id="trabajador">Hola</label>
                            <div class="box-body table-responsive no-padding">
                                <table id="TableLlamada" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="cabecera">
                                            <th class="col-md-2">
                                                <label><h4>Fecha Llamada:</h4></label>
                                                <input type="text" class="form-control" name="txt_fecha_llamada" id="txt_fecha_llamada" placeholder="Fecha Llamada" onkeypress="return masterG.enterGlobal(event, '#txt_persona', 1);" style="width: 100px;">
                                            </th>
                                            <th class="col-md-2">
                                                <label><h4>Estado Llamada:</h4></label>
                                                <input type="text" class="form-control" name="txt_estado_llamada" id="txt_estado_llamada" placeholder="Estado Llamada" onkeypress="return masterG.enterGlobal(event, '#txt_persona', 1);" style="width: 100px;">
                                            </th>
                                            <th class="col-md-3">
                                                <label><h4>Persona:</h4></label>
                                                <input type="text" class="form-control" name="txt_persona" id="txt_persona" placeholder="Persona" onkeypress="return masterG.enterGlobal(event, '#txt_fecha_llamada', 1);" style="width: 180px;">
                                            </th>                                            
                                            <th class="col-md-2">
                                                <label><h4>Fecha Programada:</h4></label>
                                                <input type="text" class="form-control" name="txt_fecha_programada" id="txt_fecha_programada" placeholder="Fecha Programada" onkeypress="return masterG.enterGlobal(event, '#txt_persona', 1);" style="width: 100px;">
                                            </th>
                                            <th class="col-md-2">
                                                <label><h4>Comentario</h4></label>
                                            </th>
                                            <th class="col-md-1">[-]</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr class="cabecera">
                                            <th>Fecha Llamada</th>
                                            <th>Estado Llamada</th>
                                            <th>Persona</th>
                                            <th>Fecha Programada</th>
                                            <th>Comentario</th>
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
