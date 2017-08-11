<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=no">       
        <meta name="author" content="Jorge Salcedo (Shevchenko)">
        
        <link rel="shortcut icon" href="favicon.ico">
        <meta name="description" content="">
        <title>         
                Software de Negocios (JS)
        </title>
            <?php echo e(Html::style('lib/bootstrap/css/bootstrap.min.css')); ?>

            <?php echo e(Html::style('lib/font-awesome/css/font-awesome.min.css')); ?>

            <?php echo e(Html::style('lib/ionicons/css/ionicons.min.css')); ?>


            <?php echo e(Html::script('lib/jQuery/jquery-2.2.3.min.js')); ?>

            <?php echo e(Html::script('lib/bootstrap/js/bootstrap.min.js')); ?>

            
            <?php echo $__env->make( 'secureaccess.css.login' , array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php echo $__env->make( 'secureaccess/js/login' , array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            <?php echo $__env->make( 'secureaccess/js/login_ajax' , array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </head>

<body  bgcolor="#FFF" onkeyup="return masterG.enterGlobal(event,'btnIniciar');">
<div id="mainWrap">
    <div class="content">
    <div id="loggit">
        <h1><i class="fa fa-2x fa-lock"></i></h1>
            <h3 id="mensaje_msj"  class="label-success">
            </h3>
            
            <h3 id="mensaje_error" style="display:none" class="label-danger">           
            </h3>

            <h3 id="mensaje_inicio">Por Favor <strong>Logeate</strong></h3>            
        

        <form id="logForm" method="post" class="form-horizontal">
    <div class="box">
            <div class="msjG" style="display: none;"> </div>
            <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                            <input type="text" name="dni" id="dni" class="form-control input-lg" placeholder="DNI" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-xs-12">
                        <div class="input-group">
                            <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                            <input type="password" name="password" id="password" class="form-control input-lg" placeholder="Password" autocomplete="off" required>
                        </div>
                    </div>
                </div>
                <div class="form-group formSubmit">
                    <div class="col-sm-7">
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" name="remember" checked autocomplete="off"> Mantener activa la session
                            </label>
                        </div>
                    </div>
                    <div class="col-sm-5 submitWrap">
                        <button type="button" id="btnIniciar" class="btn btn-primary btn-lg">Iniciar</button>
                    </div>
                </div>                  
                <div class="load" align="center" style="display:none"><i class="fa fa-spinner fa-spin fa-3x"></i></div> 
            </div>
            </div>  
        </form>
    </div>
    </div>
    </div>
</div>
</body>
