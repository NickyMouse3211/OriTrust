<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">
    <head>
    @include("partials/head")
    <title>Modernize Bootstrap Admin</title>

    </head>

    <body class="link-sidebar">
        <!-- Preloader -->
        <div class="preloader">
            <img src="{{ asset('assets') }}/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />
        </div>
        <div id="main-wrapper">
            <!-- Sidebar Start -->
            <aside class="left-sidebar with-vertical">
                <div>@include("partials/sidebar")</div>
            </aside>
            <!--  Sidebar End -->
            <div class="page-wrapper">
                <!--  Header Start -->
                <header class="topbar">
                    <div class="with-vertical">
                        @include("partials/header")
                    </div>
                    <div class="app-header with-horizontal">
                        @include("partials/horizontal-header")
                    </div>
                </header>
                <!--  Header End -->

                <aside class="left-sidebar with-horizontal">
                    @include("partials/horizontal-sidebar")
                </aside>

                @yield("content")
                
                @include("partials/customizer")
            </div>

        </div>
        
        <div class="dark-transparent sidebartoggler"></div>
        @include("partials/scripts")
        @stack('js')
        <script>
            toastr.options = {
                "positionClass": "toast-top-right",
                "timeOut": "5000",
                "closeButton": true,
            };
        
            @foreach (['success','error','warning','info'] as $msg)
                @if(session($msg))
                    toastr.{{ $msg }}("{!! session($msg) !!}", "{{ Str::ucfirst($msg) }}");
                @endif
            @endforeach

            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    toastr.error("{{ $error }}", "Error");
                @endforeach
            @endif

        </script>
    </body>
</html>