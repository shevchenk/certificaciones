<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        @section('author')
        <meta name="author" content="Jorge Salcedo (Shevchenko)">
        @show

        <link rel="shortcut icon" href="favicon.ico">

        @section('description')
        <meta name="description" content="Software Eficiencia de Entrega de Certificados ">
        @show
        <title>
            @section('title')
                Formación Contínua
            @show
        </title>

        @section('include')
            {{ Html::style('lib/bootstrap/css/bootstrap.min.css') }}
            {{ Html::style('lib/font-awesome/css/font-awesome.min.css') }}
            {{ Html::style('lib/ionicons/css/ionicons.min.css') }}
            {{ Html::style('css/AdminLTE.min.css') }}
            {{ Html::style('css/skins/_all-skins.min.css') }}
            {{ Html::style('lib/sweetalert-master/dist/sweetalert.css') }}

            {{ Html::script('lib/jQuery/jquery-2.2.3.min.js') }}
            {{ Html::script('lib/bootstrap/js/bootstrap.min.js') }}
            {{ Html::script('lib/fastclick/fastclick.js') }}
            {{ Html::script('lib/slimScroll/jquery.slimscroll.min.js') }}
            {{ Html::script('js/app.min.js') }}
            {{ Html::script('lib/sweetalert-master/dist/sweetalert.min.js') }}

            {{ Html::style('lib/bootstrap-select/dist/css/bootstrap-select.min.css') }}
            {{ Html::script('lib/bootstrap-select/dist/js/bootstrap-select.min.js') }}
            {{ Html::script('lib/bootstrap-select/dist/js/i18n/defaults-es_ES.min.js') }}

        @show
        @include( 'include.css.master' )
        @include( 'include.js.master' )
        @include( 'secureaccess.js.valida' )
        @include( 'secureaccess.js.valida_ajax' )
    </head>

    <body class="skin-blue sidebar-mini sidebar-collapse">
        <div class="wrapper">
            <header class="main-header">
                <a href="secureaccess.inicio" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini"><b>FC</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg"><b>Formación Contínua</span>
                </a>
                <nav class="navbar navbar-static-top">
                    <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                        <span class="sr-only">Toggle navigation</span>
                    </a>
                    <div class="navbar-custom-menu">
                        <ul class="nav navbar-nav">
                        </ul>
                    </div>
                </nav>
            </header>

            <aside class="main-sidebar">
                <section class="sidebar">

                </section>
            </aside>

            <div class="content-wrapper">
                <div class="msjG" style="display: none;"> </div>
                <section class="content-header">
                    <h1>Validación de datos personales
                        <small>- Valide su información personal; esta información será usada para la elaboración de sus certificados</small>
                    </h1>
                    <ol class="breadcrumb">
                        <li><i class="fa fa-sitemap"></i> Datos Personales</a></li>
                    </ol>
                </section>

                <section class="content">
                    <div class="row">
                        <div class="col-xs-4">
                            <div class="box">
                                <form id="MyselfForm">
                                    <div class="box-body">
                                        <div class="form-group">
                                            <label>Key Asignado:</label>
                                            <input type="text" class="form-control" id="txt_key" name="txt_key" placeholder="Key Asignado" value="{{$key}}" disabled>
                                            <input type="hidden" name="txt_persona_id" value="{{$persona}}">
                                            <input type="hidden" name="txt_matricula_id" value="{{$matricula}}">
                                        </div>
                                        <div class="form-group">
                                            <label>Paterno:</label>
                                            <input type="text" class="form-control" id="txt_paterno" name="txt_paterno" placeholder="Paterno" value="{{$paterno}}">
                                        </div>
                                        <div class="form-group">
                                            <label>Materno:</label>
                                            <input type="text" class="form-control" id="txt_materno" name="txt_materno" placeholder="Materno" value="{{$materno}}">
                                        </div>
                                        <div class="form-group">
                                            <label>Nombre:</label>
                                            <input type="text" class="form-control" id="txt_nombre" name="txt_nombre" placeholder="Nombre" value="{{$nombre}}">
                                        </div>
                                        <div class="form-group">
                                            <label>DNI:</label>
                                            <input type="text" class="form-control" id="txt_dni" name="txt_dni" placeholder="DNI" value="{{$dni}}">
                                        </div>
                                        <div class="form-group">
                                            <label>Sexo</label>
                                            <select class="form-control selectpicker show-menu-arrow" id="slct_sexo" name="slct_sexo">
                                                <option value="">.::Seleccione::.</option>
                                                <option data-icon="fa fa-female" <?php if($sexo=="F"){ echo "selected"; } ?> value="F">Femenino</option>
                                                <option data-icon="fa fa-male" <?php if($sexo=="M"){ echo "selected"; } ?> value="M">Masculino</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Email:</label>
                                            <input type="text" class="form-control" id="txt_email" name="txt_email" placeholder="Email" value="{{$email}}">
                                        </div>
                                        <div class="form-group">
                                            <label>Celular:</label>
                                            <textarea class="form-control" id="txt_celular" name="txt_celular" placeholder="Celular">{{$celular}}</textarea>
                                        </div>
                                        <div class="form-group">
                                            <label>Contraseña Nueva:</label>
                                            <input type="password" class="form-control" id="txt_password" name="txt_password" placeholder="Contraseña Nueva">
                                        </div>
                                        <div class="form-group">
                                            <label>Confirmar Contraseña Nueva:</label>
                                            <input type="password" class="form-control" id="txt_password_confirm" name="txt_password_confirm" placeholder="Confirmar Contraseña Nueva">
                                        </div>
                                        <div class='btn btn-primary btn-sm' class="btn btn-primary" onClick="EditarAjax()" >
                                            <i class="fa fa-edit fa-lg"></i>&nbsp;Guardar</a>
                                        </div>
                                    </div><!-- .box-body -->
                                </form><!-- .form -->
                            </div><!-- .box -->
                        </div><!-- .col -->
                    </div><!-- .row -->
                </section><!-- .content -->
            </div>

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                  <b>Version</b> 1.0
                </div>
                <strong>Copyright &copy; 2019 Formación Contínua - Dirección de Extensión Universitaria y Responsabilidad Social</strong>
            </footer>
        </div><!-- ./wrapper -->

        @yield('form')
        @include( 'include.form.mensaje' )
    </body>
</html>
