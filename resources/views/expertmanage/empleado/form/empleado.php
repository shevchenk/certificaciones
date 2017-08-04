<div class="modal" id="ModalEmpleado" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Empleado</h4>
            </div>
            <div class="modal-body">
                <form id="ModalEmpleadoForm">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Persona</label>
                            <input type="hidden" class="form-control mant" id="txt_persona_id" name="txt_persona_id" >
                            <input type="text" class="form-control" id="txt_persona" name="txt_persona" disabled="">
                            <a class="btn btn-primary btn-sm" data-toggle="modal" data-target="#ModalListapersona" data-filtros="estado:1" data-personaid="ModalEmpleado #txt_persona_id" data-persona="ModalEmpleado #txt_persona">Buscar</a>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Cargo</label>
                            <select class="form-control selectpicker show-menu-arrow" data-live-search="true" name="slct_cargo" id="slct_cargo">
                                <option value='0'>.::Seleccione::.</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Sucursal</label>
                            <select class="form-control selectpicker show-menu-arrow" data-live-search="true" name="slct_sucursal" id="slct_sucursal">
                                <option value='0'>.::Seleccione::.</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha Inicio</label>
                            <input type="text" class="form-control fechas" id="txt_fecha_inicio" name="txt_fecha_inicio" placeholder="0000-00-00">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha Fin</label>
                            <input type="text" class="form-control fechas" id="txt_fecha_final" name="txt_fecha_final" placeholder="0000-00-00">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Estado</label>
                            <select class="form-control selectpicker show-menu-arrow" data-live-search="true" name="slct_estado" id="slct_estado">
                                <option value='0'>Inactivo</option>
                                <option value='1' selected>Activo</option>
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
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
