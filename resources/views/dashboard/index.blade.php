@extends('layouts.app')
@section('content')
    <div class="body-wrapper">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    @role('Developer')
                        <p>Halo Admin 👑</p>
                    @endrole
                    <h1 class="text-2xl font-bold">Dashboard Originality</h1>
                    {{ json_encode(getPermission()) }}
                    <p class="mt-2">Halo, {{ auth_api_user()['name'] }} {{ auth_api_user()['email'] }}</p>
                    <p class="mt-2">User Token: {{ session('api_token') }}</p>
                </div>
            </div>
        </div>
    </div>
@endsection
