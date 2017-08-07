<ul class="sidebar-menu">
        <?php if(isset($menu)): ?>
            <?php $__currentLoopData = $menu; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $val): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li class="treeview">
                    <a href="#">
                        <i class="<?php echo e($val->icono); ?>"></i>
                        <span><?php echo e($val->menu); ?></span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <?php  $opciones=explode('||',$val->opciones);  ?>
                        <?php $__currentLoopData = $opciones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $op): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php  $dop=explode('|',$op);  ?>
                        <li><a href="<?php echo e($dop[1]); ?>"><i class="<?php echo e($dop[2]); ?>"></i> <?php echo e($dop[0]); ?></a></li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php endif; ?>
    <li class="treeview">
        <a href="#">
            <i class="fa fa-user-secret"></i> <span>Mis datos</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
        </a>
        <ul class="treeview-menu">
            <li><a href="secureaccess.myself"><i class="fa fa-lock"></i> Datos Personales </a></li>
        </ul>
    </li>
</ul>
