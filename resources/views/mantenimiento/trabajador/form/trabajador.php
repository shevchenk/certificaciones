<div class="modal" id="ModalTrabajador" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content"> 
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Trabajador</h4>
            </div>
            <div class="modal-body">
                <form id="ModalTrabajadorForm">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Persona</label>
                        </div>
                        <div class="input-group margin">              
                            <input type="hidden" class="form-control mant" id="txt_persona_id" name="txt_persona_id" readonly="">
                            <input type="text" class="form-control" id="txt_trabajador" name="txt_trabajador" disabled="">

                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat bntpersona" data-toggle="modal" data-target="#ModalListapersona" data-filtros="estado:1" data-epersona=1 data-personaid="ModalTrabajadorForm #txt_persona_id" data-persona="ModalTrabajadorForm #txt_trabajador">Persona</button>
                            </span>

                        </div>

                        <div class="col-md-8">
                            <div class="form-group">
                                <label>Centro de Operación</label>
                                <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_centro_operacion_id" name="slct_centro_operacion_id">
                                    <option value="">.::Seleccione::.</option>
                                </select>
                            </div> 
                        </div>
                        
                        <div class="col-md-4 nuevo">
                            <div class="form-group">
                                <label>Código</label>
                                <input type="text"  class="form-control" id="txt_codigo" name="txt_codigo" readonly>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Rol</label>
                                <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_rol_id" name="slct_rol_id" onchange="CargaTarea(this.value);">
                                    <option value="0">.::Seleccione::.</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Tarea</label>
                                <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_tarea_id" name="slct_tarea_id">
                                    <option value="0">.::Seleccione::.</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-12 validamedio">
                            <div class="form-group">
                                <label>Medio de Captación</label>
                                <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_medio_captacion_id" name="slct_medio_captacion_id">
                                    <option value="">.::Seleccione::.</option>
                                </select>
                            </div> 
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Remuneración</label>
                                <input type="text"  class="form-control" id="txt_remuneracion" name="txt_remuneracion" >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Horario</label>
                                <input type="text"  class="form-control" id="txt_horario" name="txt_horario" >
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Fecha de ingreso</label>
                                <input type="text"  class="form-control fecha" id="txt_fecha_ingreso" name="txt_fecha_ingreso" readonly>
                            </div>
                        </div>

                        <div class="col-md-4 nuevo">
                            <div class="form-group">
                                <label>Fecha de termino</label>
                                <input type="text"  class="form-control fecha" id="txt_fecha_termino" name="txt_fecha_termino" readonly>
                            </div>
                        </div>

                        <div class="col-md-8 nuevo">
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea rows="4"  class="form-control" id="txt_observacion" name="txt_observacion" ></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Estado</label>
                                <select class="form-control selectpicker show-menu-arrow" name="slct_estado" id="slct_estado">
                                    <option  value='0'>Inactivo</option>
                                    <option  value='1'>Activo</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group"> 
                            <label></label>
                        </div>

                    </div>
                </form>

                <table class="table table-bordered table-striped">
                    <thead style="background-color: #8898D7;color:black">
                        <tr>
                            <th class="text-center" colspan="10">Histórico</th>
                        </tr>
                        <tr>
                            <th class="text-center">Código</th>
                            <th class="text-center">Rol</th>
                            <th class="text-center">Tarea</th>
                            <th class="text-center">Medio de Captación</th>
                            <th class="text-center">Centro de Operación</th>
                            <th class="text-center">Remuneración</th>
                            <th class="text-center">Horario</th>
                            <th class="text-center">Fecha de Ingreso</th>
                            <th class="text-center">Fecha de Termino</th>
                            <th class="text-center">Observación</th>
                        </tr>
                    </thead>
                    <tbody id="tb_historico" ></tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
                <button type="button" class="btn btn-warning">Agregar Escalafon</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
