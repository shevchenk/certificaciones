@extends('layout.master')  

@section('include')
    @parent
    {{ Html::script('lib/bootstrap-filestyle/src/bootstrap-filestyle.min.js') }}

    @include( 'proceso.carga.js.cargarpersona_ajax' )
    @include( 'proceso.carga.js.cargarpersona' )

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
                <form id="form_file" name="form_file" action="" enctype="multipart/form-data" method="post">
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <!-- <label>Archivo TXT</label> -->
                            <input type="file" class="filestyle" data-buttonText="&nbsp;Seleccione Archivo .TXT" id="carga" name="carga" data-buttonName="btn-primary">
                        </div>
                        <div class="col-sm-2 ">
                            <button type="button" id="btn_cargar" class="btn btn-primary">
                                <span class="glyphicon glyphicon-floppy-disk" aria-hidden="true"></span> Guardar
                            </button>
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
                <div class="col-sm-12" style="display: none;">
                    <div class="col-sm-4">
                    &nbsp;
                    </div>
                    <div class="col-sm-4">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <th style="text-align:center">No pasaron</th>
                            </head>
                            <tbody id="resultado">
                                <tr>
                                <td>&nbsp;</td>
                                </tr>
                            </body>
                        </table>
                    </div>
                </div>
            </div><!-- /.box -->
            <!-- Finaliza contenido -->
        </div>
    </section><!-- /.content -->
@stop
