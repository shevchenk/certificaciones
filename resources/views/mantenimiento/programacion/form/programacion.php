<div class="modal" id="ModalProgramacion" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Producto</h4>
            </div>
            <div class="modal-body">
                <form id="ModalProgramacionForm">
<!--                    <div class="col-md-12">
                        <label>Docente</label>
                    </div>
                    
                        <div class="input-group margin">
                            <input type="hidden" class="form-control mant" id="txt_producto_id" name="txt_producto_id" readOnly="">
                            <input type="text" class="form-control" id="txt_producto" name="txt_producto"  disabled="">
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#ModalListaproducto" data-filtros="estado:1" data-productoid="ModalProductosucursalForm #txt_producto_id" data-producto="ModalProductosucursalForm #txt_producto">Buscar</button>
                            </span>
                        </div>-->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Docente</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_docente_id" name="slct_docente_id">
                                <option value="0">.::Seleccione::.</option>
                            </select>
                            <input type="hidden" name="txt_persona_id" id="txt_persona_id" class="form-control mant" readonly="">
                        </div> 
                    </div>                 
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Sucursal</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_sucursal_id" name="slct_sucursal_id">
                                <option value="0">.::Seleccione::.</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Curso</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_curso_id" name="slct_curso_id">
                                <option value="0">.::Seleccione::.</option>
                            </select>
                        </div> 
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <label>Aula</label>
                            <input type="text" onkeyup="masterG.DecimalMax(this, 2);" onkeypress="return masterG.validaDecimal(event, this);" class="form-control" id="txt_aula" name="txt_aula">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha de Inicio</label>
                            <input type="text" class="form-control fechas" id="txt_fecha_inicio" name="txt_fecha_inicio" readonly="" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Fecha Final</label>
                            <input type="text" class="form-control fechas" id="txt_fecha_final" name="txt_fecha_final" readonly="" >
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
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
