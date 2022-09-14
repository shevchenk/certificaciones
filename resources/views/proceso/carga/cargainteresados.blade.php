@extends('layout.master')  

@section('include')
    @parent
    {{ Html::script('lib/bootstrap-filestyle/src/bootstrap-filestyle.min.js') }}

    @include( 'proceso.carga.js.cargarinteresado_ajax' )
    @include( 'proceso.carga.js.cargarinteresado' )

@stop

@section('content')
<section class="content-header">
    <h1>Interesados
        <small>Carga de Datos</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Interesados</a></li>
        <li class="active">Carga de Datos</li>
    </ol>
</section>

        <!-- Main content -->
        <section class="content">
            <!-- Inicia contenido -->
            <div class="form-group">
                <form id="form_file" accept-charset="utf-8" name="form_file" action="" enctype="multipart/form-data" method="post">
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <!-- <label>Archivo TXT</label> -->
                            <input type="file" class="filestyle" data-buttonText="&nbsp;Seleccione Archivo .csv" id="carga" name="carga" data-buttonName="btn-primary">
                        </div>
                        <div class="col-sm-2" style="display: none;">
                            <label>Filtrar Región</label>
                            <select id="slct_region" name="slct_region" class="form-control">
                                <option value=''>.::Seleccione::.</option>
                                <option value='HUÁNUCO'>HUÁNUCO</option>
                                <option value='LA_LIBERTAD'>LA_LIBERTAD</option>
                                <option value='ÁNCASH'>ÁNCASH</option>
                                <option value='LIMA'>LIMA</option>
                                <option value='CUSCO'>CUSCO</option>
                                <option value='PIURA'>PIURA</option>
                                <option value='AREQUIPA'>AREQUIPA</option>
                                <option value='JUNIN'>JUNIN</option>
                                <option value='LORETO'>LORETO</option>
                                <option value='AYACUCHO'>AYACUCHO</option>
                                <option value='SAN_MARTÌN'>SAN_MARTÌN</option>
                                <option value='CAJAMARCA'>CAJAMARCA</option>
                                <option value='LAMBAYEQUE'>LAMBAYEQUE</option>
                                <option value='PUNO'>PUNO</option>
                                <option value='APURIMAC'>APURIMAC</option>
                                <option value='PASCO'>PASCO</option>
                                <option value='ICA'>ICA</option>
                                <option value='AMAZONAS'>AMAZONAS</option>
                                <option value='TACNA'>TACNA</option>
                                <option value='HUANCAVELICA'>HUANCAVELICA</option>
                                <option value='MOQUEGUA'>MOQUEGUA</option>
                                <option value='MADRE_DE_DIOS'>MADRE_DE_DIOS</option>
                                <option value='UCAYALI'>UCAYALI</option>
                                <option value='TUMBES_'>TUMBES_</option>
                            </select>
                        </div>
                        <div class="col-sm-2 ">
                            <button type="button" id="btn_cargar" class="btn btn-primary">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar
                            </button>
                        </div>
                        <div class="col-sm-2 text-center">
                            <a href="csv/interesados.csv?{{date('His')}}" class="btn btn-success">
                                <span class="glyphicon glyphicon-download-alt" aria-hidden="true"></span> Plantilla
                            </a>
                        </div>
                    </div>
                </form>
                <!-- 
                <br><br>
                <div class="col-sm-12">
                    <div class="col-sm-4">
                        <button type="button" id="btn_cargar" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
                -->
                <hr>
                <br><br>
                <div class="row">
                <div class="col-sm-12">
                    <div class="col-sm-8">
                    &nbsp;
                    </div>
                    <div class="col-sm-8">
                        <table class="table table-bordered">
                            <thead>
                                <th style="text-align:center">No pasaron</th>
                            </head>
                            <tbody id="resultado">
                                <tr>
                                <td>&nbsp;</td>
                                </tr>
                            </body>
                        </table>
                        <table class="table table-bordered table-striped">
                            <thead>
                            <tr>
                                <th> # Posición </th>
                                <th> DNI </th>
                                <th> EMAIL </th>
                                <th> FECHA_REGISTRO </th>
                            </tr>
                            </head>
                            <tbody id="resultado2">
                            </body>
                        </table>
                    </div>
                </div>
                </div>
            </div><!-- /.box -->
            <!-- Finaliza contenido -->
        </div>
    </section><!-- /.content -->
@stop
