<div class="p-6">
    @role('Developer')
        <p>Halo Admin 👑</p>
    @endrole
    <h1 class="text-2xl font-bold">Dashboard Originality</h1>
    <p class="mt-2">Halo, {{ auth_api_user()['name'] }} {{ auth_api_user()['email'] }}</p>
    <p class="mt-2">User Token: {{ session('api_token') }}</p>

    <form method="POST" action="{{ route('logout') }}" class="mt-4">
        @csrf
        <button class="bg-red-600 text-white px-4 py-2 rounded">Logout</button>
    </form>
</div>
