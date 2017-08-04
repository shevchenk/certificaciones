@extends('layout.master')  

@section('include')
    @parent
    {{ Html::style('lib/datatables/dataTables.bootstrap.css') }}
    {{ Html::script('lib/datatables/jquery.dataTables.min.js') }}
    {{ Html::script('lib/datatables/dataTables.bootstrap.min.js') }}

    {{ Html::style('lib/bootstrap-select/dist/css/bootstrap-select.min.css') }}
    {{ Html::script('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}
    {{ Html::script('lib/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}

    {{ Html::style('lib/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css') }}
    {{ Html::script('lib/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js') }}
    {{ Html::script('lib/bootstrap-datetimepicker/js/locales/bootstrap-datetimepicker.es.js') }}

    @include( 'basicmanage.producto.js.productosucursal_ajax' )
    @include( 'basicmanage.producto.js.productosucursal' )
    @include( 'basicmanage.producto.js.producto_ajax' )
    @include( 'basicmanage.producto.js.producto' )
    @include( 'basicmanage.producto.js.categoria_ajax' )
    @include( 'basicmanage.producto.js.categoria' )
    @include( 'basicmanage.producto.js.articulo_ajax' )
    @include( 'basicmanage.producto.js.articulo' )
    @include( 'basicmanage.producto.js.listaproducto' )
    @include( 'basicmanage.producto.js.listaproducto_ajax' )
    
@stop

@section('content')
<section class="content-header">
    <h1>Productos
        <small>Mantenimiento</small>
    </h1>
    <ol class="breadcrumb">
        <li><i class="fa fa-sitemap"></i> Mantenimiento</a></li>
        <li class="active">Productos</li>
    </ol>
</section>

<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box"> 
               <div class="box-body  no-padding"> <!-- table-responsive-->
                <form id="ProductosucursalForm">
                   <div class="box-body table-responsive no-padding">
                        <table id="TableProductosucursal" class="table table-bordered table-hover">
                        <thead>
                            <tr class="cabecera">
                                  <th>Img</th>
                                  <th>Producto</th>
                                  <th>Sucursal</th>
                                  <th>Precio Venta</th>
                                  <th>Precio Compra</th>
                                  <th>Stock</th>
                                  <th>Stock Mínimo</th>
                                  <th>Estado</th>
                                  <th>[-]</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot>
                            <tr class="cabecera">
                                  <th>Img</th>
                                  <th>Producto</th>
                                  <th>Sucursal</th>
                                  <th>Precio Venta</th>
                                  <th>Precio Compra</th>
                                  <th>Stock</th>
                                  <th>Stock Mínimo</th>
                                  <th>Estado</th>
                                  <th>[-]</th>
                            </tr>
                        </tfoot>
                        </table>
                        <div class='btn btn-primary btn-sm' class="btn btn-primary" onClick="AgregarEditar(1)" >
                            <i class="fa fa-plus fa-lg"></i>&nbsp;Nuevo</a>
                        </div>&nbsp;
                        <div class='btn btn-primary btn-sm' class="btn btn-primary" onClick="Cargar(1)" >
                            <i class="fa fa-plus fa-lg"></i>&nbsp;Mostrar Artículos</a>
                        </div>&nbsp;
                        <div class='btn btn-primary btn-sm' class="btn btn-primary" onClick="Cargar(2)" >
                            <i class="fa fa-plus fa-lg"></i>&nbsp;Mostrar Categorias</a>
                        </div>&nbsp;
                        <div class='btn btn-primary btn-sm' class="btn btn-primary" onClick="Cargar(3)" >
                            <i class="fa fa-plus fa-lg"></i>&nbsp;Mostrar Productos</a>
                        </div>
                   </div><!-- .box-body -->
                </form><!-- .form -->
                <hr>
                <form id="CategoriaForm" style="display: none">
                    <div class="box-body table-responsive no-padding">
                        <table id="TableCategoria" class="table table-bordered table-hover">
                            <thead>
                                <tr class="cabecera">
                                  <th>Categoria</th>
                                  <th>Estado</th>
                                  <th>[-]</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                  <th>Categoria</th>
                                  <th>Estado</th>
                                  <th>[-]</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class='btn btn-primary btn-sm' class="btn btn-primary" onClick="AgregarEditar1(1)" >
                            <i class="fa fa-plus fa-lg"></i>&nbsp;Nuevo</a>
                        </div>
                    </div><!-- .box-body -->
                </form><!-- .form --> 
                <form id="ArticuloForm" style="display: none">
                    <div class="box-body table-responsive no-padding">
                        <table id="TableArticulo" class="table table-bordered table-hover">
                            <thead>
                                <tr class="cabecera">
                                  <th>Articulo</th>
                                  <th>Categoria</th>
                                  <th>Estado</th>
                                  <th>[-]</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                  <th>Articulo</th>
                                  <th>Categoria</th>
                                  <th>Estado</th>
                                  <th>[-]</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class='btn btn-primary btn-sm' class="btn btn-primary" onClick="AgregarEditar2(1)" >
                            <i class="fa fa-plus fa-lg"></i>&nbsp;Nuevo</a>
                        </div>
                    </div><!-- .box-body -->
                </form><!-- .form --> 
                <form id="ProductoForm" style="display: none">
                    <div class="box-body table-responsive no-padding">
                        <table id="TableProducto" class="table table-bordered table-hover">
                            <thead>
                                <tr class="cabecera">
                                  <th>Img</th>
                                  <th>Producto</th>
                                  <th>Artículo</th>
                                  <th>Estado</th>
                                  <th>[-]</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                            <tfoot>
                                <tr class="cabecera">
                                  <th>Img</th>
                                  <th>Producto</th>
                                  <th>Artículo</th>
                                  <th>Estado</th>
                                  <th>[-]</th>
                                </tr>
                            </tfoot>
                        </table>
                        <div class='btn btn-primary btn-sm' class="btn btn-primary" onClick="AgregarEditar3(1)" >
                            <i class="fa fa-plus fa-lg"></i>&nbsp;Nuevo</a>
                        </div>
                    </div><!-- .box-body -->
                </form><!-- .form --> 
               </div><!-- .box-body -->
            </div><!-- .box -->
        </div><!-- .col -->
    </div><!-- .row -->
</section><!-- .content -->
@stop

@section('form')
     @include( 'basicmanage.producto.form.productosucursal' )
     @include( 'basicmanage.producto.form.categoria' )
     @include( 'basicmanage.producto.form.articulo' )
     @include( 'basicmanage.producto.form.listaproducto' )
     @include( 'basicmanage.producto.form.producto' )
@stop
