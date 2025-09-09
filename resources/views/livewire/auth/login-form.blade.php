<div class="max-w-sm mx-auto mt-20 p-6 bg-white shadow-lg rounded-xl">
    <h1 class="text-2xl font-bold mb-4">Login Originality</h1>

    @if($errorMessage)
        <div class="mb-4 p-2 bg-red-100 text-red-700 rounded">
            {{ $errorMessage }}
        </div>
    @endif

    <form wire:submit.prevent="login">
        <div class="mb-4">
            <label class="block text-gray-700">Email</label>
            <input type="email" wire:model="email"
                class="w-full border rounded px-3 py-2 mt-1" required>
            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="mb-4">
            <label class="block text-gray-700">Password</label>
            <input type="password" wire:model="password"
                class="w-full border rounded px-3 py-2 mt-1" required>
            @error('password') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
        </div>

        <button type="submit"
            class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700">
            Login
        </button>
    </form>
</div>
