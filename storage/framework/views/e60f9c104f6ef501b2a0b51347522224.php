<div class="max-w-sm mx-auto mt-20 p-6 bg-white shadow-lg rounded-xl">
    <h1 class="text-2xl font-bold mb-4">Login Originality</h1>

    <!--[if BLOCK]><![endif]--><?php if($errorMessage): ?>
        <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
            <?php echo e($errorMessage); ?>

        </div>
    <?php endif; ?><!--[if ENDBLOCK]><![endif]-->

    <form wire:submit.prevent="login">
        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" wire:model="email"
                class="w-full border rounded px-3 py-2 mt-1" required>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input type="password" wire:model="password"
                class="w-full border rounded px-3 py-2 mt-1" required>
            <!--[if BLOCK]><![endif]--><?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <span class="text-red-600 text-sm"><?php echo e($message); ?></span> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?><!--[if ENDBLOCK]><![endif]-->
        </div>

        <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Login
        </button>
    </form>
</div>
<?php /**PATH E:\Productivity\wamp64\www\laravel\originality\resources\views/livewire/auth/login-form.blade.php ENDPATH**/ ?>