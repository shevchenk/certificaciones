@extends('layout.master')  

@section('include')
@parent

{{ Html::style('lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}
{{ Html::script('lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
{{ Html::script('lib/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js') }}

@include( 'reporte.llamada.js.llamada' )
@stop

@section('content')
<style>
    .modal { overflow: auto !important; }
    </style>
    <section class="content-header">
        <h1>Indice Matrticulación
            <small>Reporte</small>
        </h1>
        <ol class="breadcrumb">
            <li><i class="fa fa-sitemap"></i> Reporte</li>
            <li class="active">Indice Matrticulación</li>
        </ol>
    </section>

    <section class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="box">
                    <form id="IndiceMatForm">
                        <div class="box-body no-padding">
                            <div class="col-sm-12">
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Inicial</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_ini" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_ini" name="txt_fecha_ini" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-2 text-center">
                                    <label class="control-label">Fecha Final</label>
                                    <div class="input-group">
                                      <span id="spn_fecha_fin" class="input-group-addon" style="cursor: pointer;"><span class="glyphicon glyphicon-calendar" aria-hidden="true"></span></span>
                                      <input type="text" class="form-control fecha" placeholder="AAAA-MM-DD" id="txt_fecha_fin" name="txt_fecha_fin" readonly/>
                                    </div>
                                </div>
                                <div class="col-sm-1" style="padding:24px">
                                    <a class='btn btn-success btn-md' id="btnexport" name="btnexport" href='' target="_blank"><i class="glyphicon glyphicon-download-alt"></i> Export</i></a>
                                </div>
                            </div>
                        </div><!-- .box-body -->
                    </form><!-- .form -->
                    </div><!-- .box -->
                </div><!-- .col -->
            </div><!-- .row -->
        </section><!-- .content -->
        @stop

