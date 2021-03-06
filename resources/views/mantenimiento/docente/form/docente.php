<div class="modal" id="ModalDocente" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Docente</h4>
            </div>
            <div class="modal-body">
                <form id="ModalDocenteForm" name="ModalDocenteForm">
                    <div class="form-group">
                        <div class="col-md-12">
                            <label>Persona</label>
                        </div>
                        <div class="input-group margin">              
                            <input type="hidden" class="form-control mant" id="txt_persona_id" name="txt_persona_id" readonly="">
                            <input type="text" class="form-control" id="txt_docente" name="txt_docente" disabled="">

                            <span class="input-group-btn">
                                <button type="button" class="btn btn-info btn-flat" data-toggle="modal" data-target="#ModalListapersona" data-epersona='1' data-filtros="estado:1" data-personaid="ModalDocenteForm #txt_persona_id" data-persona="ModalDocente #txt_docente">Persona</button>
                            </span>

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
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
