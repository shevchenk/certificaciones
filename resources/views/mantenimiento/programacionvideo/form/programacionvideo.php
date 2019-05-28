<div class="modal" id="ModalArchivo" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Seminario Programado</h4>
            </div>
            <div class="modal-body">
                <form id="ModalArchivoForm">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Docente:</label>
                            <input class="form-control" type="text" id="txt_docente" name="txt_docente" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Teléfono/Celular:</label>
                            <textarea class="form-control" id="txt_celular" name="txt_celular" value="" disabled></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Email:</label>
                            <input class="form-control" type="text" id="txt_email" name="txt_email" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Seminario:</label>
                            <input type="hidden" class="mant" name="txt_id" id="txt_id" value="">
                            <input class="form-control" type="text" id="txt_seminario" name="txt_seminario" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label>Fecha de Seminario:</label>
                            <input class="form-control" type="text" id="txt_fecha_seminario" name="txt_fecha_seminario" value="" disabled>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                      <div class="col-md-6">
                        <label>Mi CV:</label>
                        <input type="text"  readOnly class="form-control input-sm" id="txt_cv_nombre"  name="txt_cv_nombre" value="">
                        <input type="text" style="display: none;" id="txt_cv_archivo">
                      </div>
                      <div class="col-md-4 col-sm-6">
                        <a id="cv_href">
                        <img id="cv_img" class="img-circle" style="height: 142px;width: 100%;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">
                        </a>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                      <div class="col-md-6">
                        <label>Mi Temario:</label>
                        <input type="text"  readOnly class="form-control input-sm" id="txt_temario_nombre"  name="txt_temario_nombre" value="">
                        <input type="text" style="display: none;" id="txt_temario_archivo">
                      </div>
                      <div class="col-md-4 col-sm-6" >
                        <a id="temario_href">
                        <img id="temario_img" class="img-circle" style="height: 142px;width: 100%;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">
                        </a>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                      <div class="col-md-6">
                        <label>Mi Presentación:</label>
                        <input type="text"  readOnly class="form-control input-sm" id="txt_diapo_nombre"  name="txt_diapo_nombre" value="">
                        <input type="text" style="display: none;" id="txt_diapo_archivo">
                      </div>
                      <div class="col-md-4 col-sm-6" >
                        <a id="diapo_href">
                        <img id="diapo_img" class="img-circle" style="height: 142px;width: 100%;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">
                        </a>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                      <div class="col-md-6">
                        <label>Presentación Editado:</label>
                        <input type="text"  readOnly class="form-control input-sm" id="txt_diapoedit_nombre"  name="txt_diapoedit_nombre" value="">
                        <input type="text" style="display: none;" id="txt_diapoedit_archivo" name="txt_diapoedit_archivo">
                        <label class="btn btn-default btn-flat margin btn-xs">
                            <i class="fa fa-file-image-o fa-3x"></i>
                            <i class="fa fa-file-pdf-o fa-3x"></i>
                            <i class="fa fa-file-word-o fa-3x"></i>
                            <input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,'#txt_diapoedit_nombre','#txt_diapoedit_archivo','#diapoedit_img');" >
                        </label>
                      </div>
                      <div class="col-md-4 col-sm-6">
                        <a id="diapoedit_href">
                        <img id="diapoedit_img" class="img-circle" style="height: 142px;width: 100%;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">
                        </a>
                      </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label>Se Grabó?:</label>
                        <div class="radio">
                          <label><input type="radio" class="slct_grabo" value="0" name="slct_grabo">No</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" class="slct_grabo" value="1" name="slct_grabo">Si</label>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6">
                        <label>Se Publicó?:</label>
                        <div class="radio">
                          <label><input type="radio" class="slct_publico" value="0" name="slct_publico">No</label>
                        </div>
                        <div class="radio">
                          <label><input type="radio" class="slct_publico" value="1" name="slct_publico">Si</label>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label>Características del Expositor:</label>
                        <textarea rows="5" class="form-control" id="txt_expositor" name="txt_expositor"></textarea>
                    </div>
                    <div class="col-md-6">
                        <label>Situaciones Ocurridas:</label>
                        <textarea rows="5" class="form-control" id="txt_situaciones" name="txt_situaciones"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <div class="col-md-12">
                &nbsp;
                </div>
                <div class="col-md-12">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" onclick="RegistrarArchivo();" class="btn btn-primary pull-right">Confirmar</button>
                </div>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
