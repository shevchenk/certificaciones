<div class="modal" id="ModalPersona" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Datos Personales</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                <form id="ModalPersonaForm" name="ModalPersonaForm">
                    <div class="col-sm-12"> <!--INICIO DE COL SM 12-->
                        <div class="col-sm-4">
                            <label>Nombre:</label>
                            <input type="text" class="form-control" id="txt_nombre" name="txt_nombre" placeholder="Nombre">
                        </div>
                        <div class="col-sm-4">
                            <label>Apellido Paterno:</label>
                            <input type="text" class="form-control" id="txt_paterno" name="txt_paterno" placeholder="Apellido Paterno">
                        </div>
                        <div class="col-sm-4">
                            <label>Apellido Materno:</label>
                            <input type="text" class="form-control" id="txt_materno" name="txt_materno" placeholder="Apellido Materno">
                        </div>           
                    </div> <!--FIN DE COL SM 12-->
                    <div class="col-sm-12"><!--INICIO DE COL SM 12-->
                        <div class="col-sm-4">
                            <label>DNI:</label>
                            <input type="text" onkeypress="return masterG.validaNumerosMax(event, this, 10);" class="form-control" id="txt_dni" name="txt_dni" placeholder="DNI"  autocomplete="off">
                        </div>
                        <div class="col-sm-4">
                            <label>Email:</label>
                            <input type="text" class="form-control" id="txt_email" name="txt_email" placeholder="Email">
                        </div>
                    </div><!--FIN DE COL SM 12-->   
                    <div class="col-sm-12"><!--INICIO DE COL SM 12-->
                        <div class="col-sm-4">
                            <label>Sexo:</label>
                            <select class="form-control selectpicker show-menu-arrow" id="slct_sexo" name="slct_sexo">
                                <option value="">.::Seleccione::.</option>
                                <option data-icon="fa fa-female" value="F">Femenino</option>
                                <option data-icon="fa fa-male" value="M">Masculino</option>
                            </select>
                        </div>    
                        <div class="col-sm-4">
                            <label>Teléfono:</label>
                            <textarea class="form-control" id="txt_telefono" name="txt_telefono" placeholder="Teléfono">
                            </textarea>
                        </div>
                        <div class="col-sm-4">
                            <label>Celular:</label>
                            <textarea class="form-control" id="txt_celular" name="txt_celular" placeholder="Celular">
                            </textarea>
                        </div>
                    </div>
                    <div class="col-sm-12 panel">
                        <hr>
                        <div class="panel-heading bg-info"><center>REFERENCIA DEL VISITANTE</center></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <label>Distrito Dirección:</label>
                            <input type="hidden" class="mant" id="txt_distrito_id_dir" name="txt_distrito_id_dir">
                            <input type="hidden" class="mant" id="txt_provincia_id_dir" name="txt_provincia_id_dir">
                            <input type="hidden" class="mant" id="txt_region_id_dir" name="txt_region_id_dir">
                            <div id="txt_distrito_dir_ico" class="has-error has-feedback">
                                <input type="text" class="form-control" onblur="masterG.Limpiar('#txt_distrito_id_dir,#txt_provincia_id_dir,#txt_region_id_dir,#txt_provincia_dir,#txt_region_dir');" id="txt_distrito_dir" placeholder="Distrito Dirección">
                                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <label>Provincia:</label>
                            <input type="text" disabled class="form-control" id="txt_provincia_dir" placeholder="Provincia">
                        </div>
                        <div class="col-sm-3">
                            <label>Región:</label>
                            <input type="text" disabled class="form-control" id="txt_region_dir" placeholder="Región">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <label>Referencia:</label>
                            <textarea class="form-control" id="txt_referencia_dir" name="txt_referencia_dir" placeholder="Referencia">
                            </textarea>
                        </div>
                    </div>
                    <div class="col-sm-12 panel">
                        <hr>
                        <div class="panel-heading bg-info"><center>PREFERENCIAS DEL VISITANTE</center></div>
                    </div>
                    <div class="col-sm-12"><!--INICIO DE COL SM 12-->
                        <div class="col-sm-4">
                            <label>Sede donde se registra al Visitante:</label>
                            <select class="form-control" id="slct_sucursal" name="slct_sucursal">
                                <option>.::Seleccione::.</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label>Medio Publicitario:</label>
                            <select class="form-control" id="slct_medio_publicitario" name="slct_medio_publicitario">
                                <option>.::Seleccione::.</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label>Carrera/Especialidad Interesado(a):</label>
                            <input type="text" class="form-control" id="txt_carrera" name="txt_carrera" placeholder="Carrera">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <label>Frecuencia:</label>
                            <select class="form-control selectpicker"  data-actions-box='true' multiple name="slct_dia[]" id="slct_dia" onChange="ValidaOnline(this.value);">
                                <option value="LU">Lunes</option>
                                <option value="MA">Martes</option>
                                <option value="MI">Miercoles</option>
                                <option value="JU">Jueves</option>
                                <option value="VI">Viernes</option>
                                <option value="SA">Sabado</option>
                                <option value="DO">Domingo</option>
                                <option value="ON">OnLine</option>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <label>Hora Inicio:</label>
                            <input type="text" class="form-control fechas" id="txt_hora_inicio" name="txt_hora_inicio" readonly="" >
                        </div>
                        <div class="col-sm-4">
                            <label>Hora Final:</label>
                            <input type="text" class="form-control fechas" id="txt_hora_final" name="txt_hora_final" readonly="" >
                        </div>
                    </div>
                    <div class="col-sm-12 panel" style="display: none;">
                        <hr>
                        <div class="panel-heading bg-info"><center>ESTADO DEL VISITANTE</center></div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-md-6" style="display: none;">
                            <div class="form-group">
                                <label>Estado:</label>
                                <select class="selectpicker form-control show-menu-arrow" onchange="ActivarComentario()" id="slct_tipo_llamada" name="slct_tipo_llamada">
                                    <option value="">.::Seleccione::.</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 tipo1" style="display: none;">
                            <div class="form-group">
                                <label class="fechadinamica">Fecha:</label>
                                <input class="form-control fecha" type="text" id="txt_fechas" name="txt_fechas" value="" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-md-5 tipo2" style="display: none;">
                            <label>Sub Estado:</label>
                                <select class="selectpicker form-control show-menu-arrow" onchange="ActivarDetalle()" id="slct_sub_tipo_llamada" name="slct_sub_tipo_llamada">
                                    <option value="">.::Seleccione::.</option>
                                </select>
                        </div>
                        <div class="col-md-7 tipo2" style="display: none;">
                            <label>Detalle Sub Estado:</label>
                                <select class="selectpicker form-control show-menu-arrow" id="slct_detalle_tipo_llamada" name="slct_detalle_tipo_llamada">
                                    <option value="">.::Seleccione::.</option>
                                </select>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
