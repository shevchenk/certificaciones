<div class="modal" id="ModalVerentregavalidaalumno" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pago de Alumno</h4>
            </div>
            <div class="modal-body">
                <form id="ModalVerentregavalidaalumnoForm">
                    <div class="col-md-6">
                    <div class="col-md-12">
                        <input type="hidden" class="mant" id="txt_certificado_estado_id" name="txt_certificado_estado_id" value="10">
                        <div class="form-group">
                            <label>¿Contestó el Alumno?</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_estado_contesto" name="slct_estado_contesto">
                                <option value>.::Seleccione::.</option>
                                <option value="1">Si Contestó</option>
                                <option value="0">No Contestó</option>
                            </select>
                        </div> 
                    </div>
                        <div class="col-md-12" id="respuesta" style="display: none">
                        <div class="form-group">
                            <label id="label_curso">Respuesta</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_contesta_id" name="slct_contesta_id">
                                <option value="0">.::Seleccione::.</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Observación</label>
                            <textarea class="form-control" id="txt_observacion" name="txt_observacion" rows="4"></textarea>
                        </div>
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>ODE</label>
                            <input type="text" class="form-control" id="txt_ode" name="txt_ode" disabled="">
                        </div> 
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="label_curso">DNI</label>
                            <input type="text" class="form-control" id="txt_dni" name="txt_dni" disabled="">
                        </div> 
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Nombre Completo</label>
                            <input type="text" class="form-control" id="txt_nombre" name="txt_nombre" disabled="">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Trámite</label>
                            <input type="text" class="form-control" id="txt_tramite" name="txt_tramite" disabled="">
                        </div>
                    </div>
                    </div>
                    <div class="col-md-12">
                        <div class="box">
                            <div class="box-body table-responsive no-padding">
                                    <table id="TableVerpagoalumno" class="table table-bordered table-hover">
                            <thead>
                                <tr class="cabecera">
                                      <th>N°</th>
                                      <th>¿Contestó?</th>
                                      <th>Respuesta</th>
                                      <th>Observación</th>
                                      <th>Fecha de Llamada</th>  
                                      <th>Usuario</th>  
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                      <th>N°</th>
                                      <th>¿Contestó?</th>
                                      <th>Respuesta</th>
                                      <th>Observación</th>
                                      <th>Fecha de Llamada</th>  
                                      <th>Usuario</th>  
                                </tr>
                            </tfoot>
                            </table>
                            </div><!-- .box-body -->
                        </div><!-- .box -->
                    </div>
                     <div class="form-group"> 
                         <label></label>
                     </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
