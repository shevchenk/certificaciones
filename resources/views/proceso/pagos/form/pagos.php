<!-- /.modal -->
<div class="modal fade" id="ModalPago" tabindex="-1" role="dialog" aria-hidden="true">
    <!-- <div class="modal fade" id="areaModal" tabindex="-1" role="dialog" aria-hidden="true"> -->
    <div class="modal-dialog modal-xlg">
        <div class="modal-content">
            <div class="modal-header btn-info">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">Pagos</h4>
            </div>
            <div class="modal-body">
                <section class="content">
                    <form id="PagoForm">
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-4">
                                    <label>Datos del Alumno:</label>
                                    <input class="form-control" type="text" id="txt_alumno" name="txt_alumno" value="" disabled>
                                    <input class="form-control" type="hidden" id="txt_matricula_detalle_id" name="txt_matricula_detalle_id" value="">
                                    <input class="form-control" type="hidden" id="txt_matricula_id" name="txt_matricula_id" value="">
                                    <input class="form-control" type="hidden" id="txt_cuota" name="txt_cuota" value="">
                                </div>
                                <div class="col-md-8">
                                    <label>Módulo / Formación Continua / Local:</label>
                                    <input class="form-control" type="text" id="txt_detalle" name="txt_detalle" value="" disabled>
                                </div>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-4">
                                    <label>N° de Boleta/N° de Operación:</label>
                                    <input class="form-control" type="text" id="txt_nro_pago" name="txt_nro_pago" value="">
                                </div>
                                <div class="col-md-4">
                                    <label>Saldo / Monto Pago:</label>
                                    <div class='input-group'>
                                        <div class='input-group-addon'>
                                        <i id="i_monto_saldo">90</i>
                                        </div>
                                        <div id=txt_monto_pago_ico class=has-warning has-feedback>
                                            <input type='text' class='form-control'  id='txt_monto_pago' name='txt_monto_pago'
                                             onkeypress='return masterG.validaDecimal(event, this);' onkeyup='masterG.DecimalMax(this, 2);ValidaDeuda(90,this);'>
                                        </div>
                                    </div>
                                    <div id=i_monto_deuda_ico class=has-warning has-feedback>
                                        <div class='input-group-addon'>
                                        <label>Deuda:</label>
                                        <label id='i_monto_deuda'>90</label>
                                        <span class=glyphicon glyphicon-warning-sign form-control-feedback></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label>Tipo de Operación:</label>
                                    <select class='form-control'  id='slct_tipo_pago' name='slct_tipo_pago'>
                                        <option value='0'>.::Seleccione::.</option>
                                        <option value='1.1'>Transferencia - BCP</option>
                                        <option value='1.2'>Transferencia - Scotiabank</option>
                                        <option value='1.3'>Transferencia - BBVA</option>
                                        <option value='1.4'>Transferencia - Interbank</option>
                                        <option value='2.1'>Depósito - BCP</option>
                                        <option value='2.2'>Depósito - Scotiabank</option>
                                        <option value='2.3'>Depósito - BBVA</option>
                                        <option value='2.4'>Depósito - Interbank</option>
                                        <option value='3.0'>Caja</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="box">
                            <div class="box-body">
                                <div class="col-md-4">
                                    <label>Archivo de Pago:</label>
                                    <input type="text"  readOnly class="form-control input-sm" id="txt_pago_nombre"  name="txt_pago_nombre" value="">
                                    <input type="text" style="display: none;" id="txt_pago_archivo" name="txt_pago_archivo">
                                    <label class="btn btn-default btn-flat margin btn-xs">
                                        <i class="fa fa-file-image-o fa-3x"></i>
                                        <i class="fa fa-file-pdf-o fa-3x"></i>
                                        <i class="fa fa-file-word-o fa-3x"></i>
                                        <input type="file" class="mant" style="display: none;" onchange="masterG.onImagen(event,'#txt_pago_nombre','#txt_pago_archivo','#pago_img');" >
                                    </label>
                                    <div>
                                    <a id="pago_href">
                                    <img id="pago_img" class="img-circle" style="height: 100px;width: 95%;border-radius: 8px;border: 1px solid grey;margin-top: 5px;padding: 8px">
                                    </a>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label>Responsable de Caja:</label>
                                    <input type="hidden" name="txt_persona_caja_id" id="txt_persona_caja_id" class="form-control" readonly="">
                                    <input type="text" class="form-control" id="txt_persona_caja" name="txt_persona_caja" disabled="">
                                </div>
                                <div class="col-md-2">
                                    <label>&nbsp;&nbsp;&nbsp;</label>
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-warning btn-flat" data-toggle="modal" data-target="#ModalListatrabajador" data-filtros="estado:1|rol_id:2" data-personaid="ModalPago #txt_persona_caja_id"  data-persona="ModalPago #txt_persona_caja">Buscar Responsable</button>
                                    </span>
                                    <br><br>
                                    <input type="button" class="btn btn-primary" id="btnPagoCuota" onClick="GuardarPago(0);" value="Guardar Pago">
                                </div>
                            </div>
                        </div>
                        <div class="box">
                                <div class="box-body table-responsive no-padding">
                                    <table id="TablePago" class="table table-bordered table-hover">
                                        <thead style="background-color: #A9D08E;color:black">
                                            <tr>
                                                <th>Precio.</th>
                                                <th>Pago</th>
                                                <th>N° de Boleta/N° de Operación</th>
                                                <th>Saldo</th>
                                                <th>Tipo Operación</th>
                                                <th>Archivo Pago</th>
                                            </tr>
                                        </thead>
                                        <tbody id="tb_pago">
                                        </tbody>
                                    </table>
                                </div><!-- .box-body -->
                        </div>
                    </form><!-- .form -->
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default active pull-left" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- /.modal -->
