<div class="modal" id="ModalEmpresa" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="false" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Empresa</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                <form id="ModalEmpresaForm">
                    <div class="col-md-12">
                        <div class="col-md-7 col-xs-7">
                            <label>Empresa:</label>
                            <input type="text" class="form-control" id="txt_empresa" name="txt_empresa">
                        </div>
                        <div class="col-md-5 col-xs-5">
                            <label>Nota Mínima Aprobatoria:</label>
                            <input type="text" class="form-control" id="txt_nota_minima" name="txt_nota_minima" onkeypress="return masterG.validaDecimal(event,this);" onkeyup="masterG.DecimalMax(this,2);">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-7 col-xs-7">
                            <label>Tiene Proyecto Final:</label>
                            <select onchange="validaProyectoFinal(this);" class="form-control selectpicker" id="slct_trabajo_final" name="slct_trabajo_final">
                                <option value="1">Si</option>
                                <option value="0" selected>No</option>
                            </select>
                        </div>
                        <div class="col-md-5 col-xs-5">
                            <label>Indique su peso:</label>
                            <select class="form-control selectpicker" id="slct_peso_trabajo_final" name="slct_peso_trabajo_final">
                                <option value="0" selected>0</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br>
                        <div class="col-md-7">
                            <label>Tipos de Evaluaciones:</label>
                            <select  class="form-control selectpicker show-menu-arrow" data-live-search="true" id="slct_tipo_evaluacion">
                                <option value="">.::Seleccione::.</option>
                            </select>
                        </div>
                        <div class="col-md-5">
                            <br>
                            <a onclick="ValidarTipoEvaluacion();" class="btn btn-info btn-flat">Agregar Tipo de Evaluación<i class="fa fa-plus fa-lg"></i></a>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <br><br>
                        <table class="table table-bordered table-hover">
                            <thead class="bg-info">
                                <tr>
                                    <th style="text-align: center;">Orden</th>
                                    <th style="text-align: center;">Tipo Evaluación</th>
                                    <th style="text-align: center;">Peso Evaluación</th>
                                    <th style="text-align: center;">Eliminar</th>
                                </tr>
                            </thead>
                            <tbody id='tb_te'>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-12">
                      <div class="col-md-8">
                        <label>Logo de la empresa:</label>
                        <input type="text"  readOnly class="form-control input-sm" id="txt_logo_nombre"  name="txt_logo_nombre" value="">
                        <input type="text" style="display: none;" id="txt_logo_archivo" name="txt_logo_archivo">
                        <label class="btn btn-default btn-flat margin btn-xs">
                            <i class="fa fa-file-image-o fa-3x"></i>
                            <i class="fa fa-file-pdf-o fa-3x"></i>
                            <i class="fa fa-file-word-o fa-3x"></i>
                            <input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,'#txt_logo_nombre','#txt_logo_archivo','#logo_img');" >
                        </label>
                      </div>
                      <div class="col-md-4">
                        <a id="logo_href">
                        <img id="logo_img" class="img-circle" style="height: 142px;width: 100%;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">
                        </a>
                      </div>
                    </div>
                    <div class="col-md-12">
                        <div class="col-md-12">
                            <label>Contenido Ficha:</label>
                            <textarea rows="6" class="form-control" id="txt_contenido_ficha" name='txt_contenido_ficha'></textarea>
                        </div>
                    </div>
                </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary pull-right">Guardar</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div>
