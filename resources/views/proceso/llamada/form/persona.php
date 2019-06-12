<div class="modal" id="ModalPersona" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Actualización de Persona</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                <form id="ModalPersonaForm">
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
                        <div class="col-sm-4">
                            <label>Carrera</label>
                            <input type="text" class="form-control" id="txt_carrera" name="txt_carrera" placeholder="Carrera">
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
                </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="ActualizarPersona();" class="btn btn-primary pull-right">Actualizar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
