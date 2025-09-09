<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Originality</title>

    {{-- Import manual asset --}}
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}">
    <script src="{{ asset('assets/js/app.js') }}" defer></script>

    @livewireStyles
</head>
<body class="antialiased bg-gray-100">
    <div class="min-h-screen">
        {{ $slot }}
    </div>

    @livewireScripts
</body>
</html>
