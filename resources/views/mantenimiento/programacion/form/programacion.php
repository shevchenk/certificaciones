<div class="modal" id="ModalProgramacion" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Programación</h4>
            </div>
            <div class="modal-body">
                <form id="ModalProgramacionForm">
                    <div class="col-md-12">
                        <label>Docente</label>
                    </div>
                        <div class="input-group margin">
                            <input type="hidden" class="form-control mant" id="txt_docente_id" name="txt_docente_id" readOnly="">
                            <input type="hidden" name="txt_persona_id" id="txt_persona_id" class="form-control mant" readonly="">
                            <input type="text" class="form-control" id="txt_docente" name="txt_docente"  disabled="">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#ModalListadocente" data-personaid="ModalProgramacionForm #txt_persona_id" data-docenteid="ModalProgramacionForm #txt_docente_id" data-docente="ModalProgramacionForm #txt_docente">Buscar</button>
                            </span>
                        </div>           
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>ODE</label>
                            <select  class="form-control selectpicker show-menu-arrow" onchange="ValidaOde(this.value);" data-live-search="true" id="slct_sucursal_id" name="slct_sucursal_id">
                                <option value="0">.::Seleccione::.</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label id="label_curso">Curso</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_curso_id" name="slct_curso_id">
                                <option value="0">.::Seleccione::.</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-3 txt_aula validaode">
                        <div class="form-group">
                            <label>Aula</label>
                            <input type="text" class="form-control" id="txt_aula" name="txt_aula">
                        </div>
                    </div>
                    <div class="col-md-3 slct_dia validaode">
                        <div class="form-group">
                            <label>Frecuencia</label>
                            <select class="form-control selectpicker"  data-actions-box='true' multiple name="slct_dia[]" id="slct_dia">
                                <option value="LU">Lunes</option>
                                <option value="MA">Martes</option>
                                <option value="MI">Miercoles</option>
                                <option value="JU">Jueves</option>
                                <option value="VI">Viernes</option>
                                <option value="SA">Sabado</option>
                                <option value="DO">Domingo</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 validaode">
                        <div class="form-group">
                            <label>Hora de Inicio</label>
                            <input type="text" class="form-control horas" id="txt_hora_inicio" name="txt_hora_inicio" readonly="" >
                        </div>
                    </div>
                    <div class="col-md-3 validaode">
                        <div class="form-group">
                            <label>Hora de Final</label>
                            <input type="text" class="form-control horas" id="txt_hora_final" name="txt_hora_final" readonly="" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha de Inicio</label>
                            <input type="text" class="form-control fecha" id="txt_fecha_inicio" name="txt_fecha_inicio" readonly="" >
                        </div>
                    </div>
                    <div class="col-md-6 validaode">
                        <div class="form-group">
                            <label>Fecha Final</label>
                            <input type="text" class="form-control fecha" id="txt_fecha_final" name="txt_fecha_final" readonly="" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha Campaña</label>
                            <input type="text" class="form-control fecha" id="txt_fecha_campaña" name="txt_fecha_campaña" readonly="" >
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Meta Max</label>
                            <input type="text" onkeypress="return masterG.validaNumeros(event, this);" class="form-control" id="txt_meta_max" name="txt_meta_max">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Meta Min</label>
                            <input type="text" onkeypress="return masterG.validaNumeros(event, this);" class="form-control" id="txt_meta_min" name="txt_meta_min">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Costo</label>
                            <input type="text" onkeypress="return masterG.validaDecimal(event, this);" onkeyup="return masterG.DecimalMax(this,2);" class="form-control" id="txt_costo" name="txt_costo" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Turno</label>
                            <select class="form-control selectpicker" name="slct_turno" id="slct_turno">
                                <option value='' selected>.::Todo::.</option>
                                <option value='M'>Mañana</option>
                                <option value='T'>Tarde</option>
                                <option value='N'>Noche</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Inscripción:</label>
                            <input type="text" onkeypress="return masterG.validaDecimal(event, this);" onkeyup="return masterG.DecimalMax(this,2);" class="form-control" id="txt_costo_ins" name="txt_costo_ins" value="0">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Matrícula:</label>
                            <input type="text" onkeypress="return masterG.validaDecimal(event, this);" onkeyup="return masterG.DecimalMax(this,2);" class="form-control" id="txt_costo_mat" name="txt_costo_mat" value="0">
                        </div>
                    </div>
                    <div class="col-md-12 txt_link">
                        <div class="form-group">
                            <label>Link del Seminario</label>
                            <textarea class="form-control" id="txt_link" name="txt_link"></textarea>
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
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary pull-right">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
