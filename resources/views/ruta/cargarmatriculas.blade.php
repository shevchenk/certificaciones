@extends('layout.master')  

@section('include')
    @parent
    {{ HTML::style('lib/daterangepicker/css/daterangepicker-bs3.css') }}
    {{ HTML::style('lib/bootstrap-multiselect/dist/css/bootstrap-multiselect.css') }}
    {{ HTML::script('lib/daterangepicker/js/daterangepicker.js') }}
    {{ HTML::script('lib/bootstrap-multiselect/dist/js/bootstrap-multiselect.js') }}

    @include( 'ruta.js.cargarmatriculas_ajax' )
    @include( 'ruta.js.cargarmatriculas' )

@stop

@section('content')
<section class="content-header">
    <h1>Carga de Gastos
        <small></small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Matriculas</a></li>
        <li class="active">Carga de Gastos</li>
    </ol>
</section>

        <!-- Main content -->
        <section class="content">
            <!-- Inicia contenido -->
            <div class="form-group">
                <form id="form_file" name="form_file" action="" enctype="multipart/form-data" method="post">
                    <div class="col-sm-12">
                        <div class="col-sm-4">
                            <label>Archivo TXT</label>
                            <input type="file" class="form-control" id="carga" name="carga" >
                        </div>
                    </div>
                </form>
                <br><br>
                <div class="col-sm-12">
                    <div class="col-sm-4">
                        <button type="button" id="btn_cargar" class="btn btn-primary">Guardar</button>
                    </div>
                </div>
                <hr>
                <br><br>
                <div class="col-sm-12">
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