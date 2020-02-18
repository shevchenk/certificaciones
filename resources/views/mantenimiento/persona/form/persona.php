<div class="modal" id="ModalPersona" tabindex="-1" role="dialog" data-backdrop="false" data-keyboard="false">
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
                            <label>Nombre</label>
                            <input type="text" class="form-control" id="txt_nombre" name="txt_nombre" placeholder="Nombre">
                        </div>
                        <div class="col-sm-4">
                            <label>Apellido Paterno</label>
                            <input type="text" class="form-control" id="txt_paterno" name="txt_paterno" placeholder="Apellido Paterno">
                        </div>
                        <div class="col-sm-4">
                            <label>Apellido Materno</label>
                            <input type="text" class="form-control" id="txt_materno" name="txt_materno" placeholder="Apellido Materno">
                        </div>           
                    </div> <!--FIN DE COL SM 12-->
                    <div class="col-sm-12"><!--INICIO DE COL SM 12-->
                        <div class="col-sm-4">
                            <label>DNI</label>
                            <input type="text" onkeypress="return masterG.validaNumerosMax(event, this, 10);" class="form-control" id="txt_dni" name="txt_dni" placeholder="DNI"  autocomplete="off">
                        </div>
                        <div class="col-sm-4">
                            <label>Email</label>
                            <input type="text" class="form-control" id="txt_email" name="txt_email" placeholder="Email">
                        </div>
                        <div class="col-sm-4" style="display: none;">
                            <label>Carrera</label>
                            <input type="text" class="form-control" id="txt_carrera" name="txt_carrera" placeholder="Carrera">
                        </div>
                        <div class="col-sm-4">
                            <label>Estado Civil:</label>
                            <select class="form-control selectpicker show-menu-arrow" name="slct_estado_civil" id="slct_estado_civil">
                                <option  value='S'>Soltero(a)</option>
                                <option  value='C'>Casado(a)</option>
                                <option  value='D'>Divorsiado(a)</option>
                                <option  value='V'>Viudo(a)</option>
                            </select>
                        </div>
                    </div><!--FIN DE COL SM 12-->   
                    <div class="col-sm-12"><!--INICIO DE COL SM 12-->
                        <div class="col-sm-4">
                            <label>Sexo</label>
                            <select class="form-control selectpicker show-menu-arrow" id="slct_sexo" name="slct_sexo">
                                <option value="">.::Seleccione::.</option>
                                <option data-icon="fa fa-female" value="F">Femenino</option>
                                <option data-icon="fa fa-male" value="M">Masculino</option>
                            </select>
                        </div>    
                        <div class="col-sm-4">
                            <label>Teléfono</label>
                            <textarea class="form-control" id="txt_telefono" name="txt_telefono" placeholder="Teléfono">
                            </textarea>
                        </div>
                        <div class="col-sm-4">
                            <label>Celular</label>
                            <textarea class="form-control" id="txt_celular" name="txt_celular" placeholder="Celular">
                            </textarea>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <label>Fecha Nacimiento</label>
                            <input type="text" class="form-control fecha" id="txt_fecha_nacimiento" name="txt_fecha_nacimiento" placeholder="AAAA-MM-DD" readonly=""> <!-- onfocus="blur()"/-->
                        </div>
                    </div>
                    <div class="col-sm-12 panel">
                        <hr>
                        <div class="panel-heading bg-info"><center>DATOS ADICIONALES</center></div>
                    </div>
                    <div class="col-sm-12"> <!--INICIO DE COL SM 12-->
                        <div class="col-sm-4">
                            <label>Pais Nacimiento:</label>
                            <input type="hidden" class="mant" id="txt_pais_id" name="txt_pais_id">
                            <div id="txt_pais_ico" class="has-error has-feedback">
                                <input type="text" class="form-control" onblur="LimpiarPersonaModal('txt_pais_id');" id="txt_pais" placeholder="Pais">
                                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-sm-8 paisafectado">
                            <label>Colegio:</label>
                            <input type="hidden" class="mant" id="txt_colegio_id" name="txt_colegio_id">
                            <div id="txt_colegio_ico" class="has-error has-feedback">
                                <input type="text" class="form-control" onblur="LimpiarPersonaModal('txt_colegio_id');" id="txt_colegio" placeholder="Colegio">
                                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                            </div>
                        </div>
                    </div> <!--FIN DE COL SM 12-->
                    <div class="col-sm-12">
                        <div class="col-sm-6 paisafectado">
                            <label>Distrito Nacimiento:</label>
                            <input type="hidden" class="mant" id="txt_distrito_id" name="txt_distrito_id">
                            <input type="hidden" class="mant" id="txt_provincia_id" name="txt_provincia_id">
                            <input type="hidden" class="mant" id="txt_region_id" name="txt_region_id">
                            <div id="txt_distrito_ico" class="has-error has-feedback">
                                <input type="text" class="form-control" onblur="LimpiarPersonaModal('txt_distrito_id,#txt_provincia_id,#txt_region_id,#txt_provincia,#txt_region');" id="txt_distrito" placeholder="Distrito Nacimiento">
                                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-sm-3 paisafectado2">
                            <label>Provincia:</label>
                            <input type="text" disabled class="form-control" id="txt_provincia" placeholder="Provincia">
                        </div>
                        <div class="col-sm-3 paisafectado2">
                            <label>Región:</label>
                            <input type="text" disabled class="form-control" id="txt_region" placeholder="Región">
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <label>Distrito Dirección:</label>
                            <input type="hidden" class="mant" id="txt_distrito_id_dir" name="txt_distrito_id_dir">
                            <input type="hidden" class="mant" id="txt_provincia_id_dir" name="txt_provincia_id_dir">
                            <input type="hidden" class="mant" id="txt_region_id_dir" name="txt_region_id_dir">
                            <div id="txt_distrito_dir_ico" class="has-error has-feedback">
                                <input type="text" class="form-control" onblur="LimpiarPersonaModal('txt_distrito_id_dir,#txt_provincia_id_dir,#txt_region_id_dir,#txt_provincia_dir,#txt_region_dir');" id="txt_distrito_dir" placeholder="Distrito Dirección">
                                <span class="glyphicon glyphicon-remove form-control-feedback"></span>
                            </div>
                        </div>
                        <div class="col-sm-2">
                            <label>Provincia:</label>
                            <input type="text" disabled class="form-control" id="txt_provincia_dir" placeholder="Provincia">
                        </div>
                        <div class="col-sm-2">
                            <label>Región:</label>
                            <input type="text" disabled class="form-control" id="txt_region_dir" placeholder="Región">
                        </div>
                        <div class="col-sm-2">
                            <label>Tenencia:</label>
                            <select class="form-control selectpicker show-menu-arrow" name="slct_tenencia" id="slct_tenencia">
                                <option  value='0'>Alquilado</option>
                                <option  value='1'>Propio</option>
                                <option  value='2'>Familiar</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="col-sm-6">
                            <label>Dirección:</label>
                            <textarea cols="3" class="form-control" id="txt_direccion_dir" name="txt_direccion_dir" placeholder="Dirección"></textarea>
                        </div>
                        <div class="col-sm-6">
                            <label>Referencia:</label>
                            <textarea cols="3" class="form-control" id="txt_referencia_dir" name="txt_referencia_dir" placeholder="Referencia"></textarea>
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
