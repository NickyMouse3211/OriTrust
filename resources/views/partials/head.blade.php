<!-- Required meta tags -->
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />

<!-- Favicon icon-->
<link
  rel="shortcut icon"
  type="image/png"
  href="{{ asset('assets') }}/images/logos/favicon.png"
/>

<title>
  @if (isset($title))
    {{ $title }}
  @else
    {{ config('app.name', 'Brillo Asset Management') }}
  @endif
</title>

<!-- Core Css -->
<link rel="stylesheet" href="{{ asset('assets') }}/css/styles.css" />

<link rel="stylesheet" href="{{ asset('assets') }}/libs/datatables.net-bs5/css/dataTables.bootstrap5.min.css" />
<link rel="stylesheet" href="{{ asset('assets') }}/libs/sweetalert2/dist/sweetalert2.min.css">
<link rel="stylesheet" href="{{ asset('assets') }}/libs/select2/dist/css/select2.min.css">
<style>
  @media (prefers-color-scheme: dark) {
      .light-logo {
          display: none;
      }
  }
  @media (prefers-color-scheme: light) {
      .dark-logo {
          display: none;
      }
  }
</style>