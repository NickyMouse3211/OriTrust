<div class="p-6">
    <?php if (\Illuminate\Support\Facades\Blade::check('role', 'Developer')): ?>
        <p>Halo Admin 👑</p>
    <?php endif; ?>
    <h1 class="text-2xl font-bold">Dashboard Originality</h1>
    <p class="mt-2">Halo, <?php echo e(auth_api_user()['name']); ?> <?php echo e(auth_api_user()['email']); ?></p>
    <p class="mt-2">User Token: <?php echo e(session('api_token')); ?></p>

    <form method="POST" action="<?php echo e(route('logout')); ?>" class="mt-4">
        <?php echo csrf_field(); ?>
        <button class="bg-red-600 text-white px-4 py-2 rounded">Logout</button>
    </form>
</div>
<?php /**PATH E:\Productivity\wamp64\www\laravel\originality\resources\views/dashboard.blade.php ENDPATH**/ ?>