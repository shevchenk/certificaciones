<div class="modal" id="ModalTarea" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content"> 
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Tarea</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                <form id="ModalTareaForm">
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Tarea</label>
                                <input type="text" class="form-control" id="txt_rol" name="txt_rol">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Rol</label>
                                <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_rol_id" name="slct_rol_id">
                                    <option value="0">.::Seleccione::.</option>
                                </select>
                            </div> 
                        </div>
                        <div class="col-md-12" style="display: none;">
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
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
