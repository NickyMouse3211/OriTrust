
<?php $__env->startSection('content'); ?>
<div class="body-wrapper">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Developer')): ?>
                    <p>Halo Admin 👑</p>
                <?php endif; ?>
                <?php echo e(dd(getPermission())); ?>

                <h1 class="text-2xl font-bold">Dashboard Originality</h1>
                <p class="mt-2">Halo, <?php echo e(auth_api_user()['name']); ?> <?php echo e(auth_api_user()['email']); ?></p>
                <p class="mt-2">User Token: <?php echo e(session('api_token')); ?></p>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH E:\Productivity\wamp64\www\laravel\originality\resources\views/dashboard.blade.php ENDPATH**/ ?>