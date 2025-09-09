<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Originality</title>

    
    <link rel="stylesheet" href="<?php echo e(asset('assets/css/app.css')); ?>">
    <script src="<?php echo e(asset('assets/js/app.js')); ?>" defer></script>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::styles(); ?>

</head>
<body class="antialiased bg-gray-100">
    <div class="min-h-screen">
        <?php echo e($slot); ?>

    </div>

    <?php echo \Livewire\Mechanisms\FrontendAssets\FrontendAssets::scripts(); ?>

</body>
</html>
<?php /**PATH E:\Productivity\wamp64\www\laravel\originality\resources\views/components/layouts/app.blade.php ENDPATH**/ ?>