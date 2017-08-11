<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

        <?php $__env->startSection('author'); ?>
        <meta name="author" content="Jorge Salcedo (Shevchenko)">
        <?php echo $__env->yieldSection(); ?>

        <link rel="shortcut icon" href="favicon.ico">

        <?php $__env->startSection('description'); ?>
        <meta name="description" content="Software de Negocios">
        <?php echo $__env->yieldSection(); ?>
        <title>
            <?php $__env->startSection('title'); ?>
                Software de Negocios (JS)
            <?php echo $__env->yieldSection(); ?>
        </title>

        <?php $__env->startSection('include'); ?>
            <?php echo e(Html::style('lib/bootstrap/css/bootstrap.min.css')); ?>

            <?php echo e(Html::style('lib/font-awesome/css/font-awesome.min.css')); ?>

            <?php echo e(Html::style('lib/ionicons/css/ionicons.min.css')); ?>

            <?php echo e(Html::style('css/AdminLTE.min.css')); ?>

            <?php echo e(Html::style('css/skins/_all-skins.min.css')); ?>


            <?php echo e(Html::script('lib/jQuery/jquery-2.2.3.min.js')); ?>

            <?php echo e(Html::script('lib/bootstrap/js/bootstrap.min.js')); ?>

            <?php echo e(Html::script('lib/fastclick/fastclick.js')); ?>

            <?php echo e(Html::script('lib/slimScroll/jquery.slimscroll.min.js')); ?>

            <?php echo e(Html::script('js/app.min.js')); ?>

        <?php echo $__env->yieldSection(); ?>
        <?php echo $__env->make( 'include.css.master' , array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make( 'include.js.master' , array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </head>

    <body class="skin-blue sidebar-mini sidebar-collapse">
        <div class="wrapper">
            <header class="main-header">
                <?php echo $__env->make( 'layout.admin_head' , array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
            </header>

            <aside class="main-sidebar">
                <section class="sidebar">
                <?php echo $__env->make( 'layout.admin_left' , array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
                </section>
            </aside>

            <div class="content-wrapper">
                <div class="msjG" style="display: none;"> </div>
                <?php echo $__env->yieldContent('content'); ?>
            </div>

            <footer class="main-footer">
                <div class="pull-right hidden-xs">
                  <b>Version</b> 1.0
                </div>
                <strong>Copyright &copy; 2017-2018 <a href="http://jssoluciones.com">JS</a>.</strong> All rights
                reserved.
            </footer>
        </div><!-- ./wrapper -->

        <?php echo $__env->yieldContent('form'); ?>
        <?php echo $__env->make( 'include.form.imagen' , array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
        <?php echo $__env->make( 'include.form.mensaje' , array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>
    </body>
</html>
