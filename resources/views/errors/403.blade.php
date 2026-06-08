<!DOCTYPE html>
<html lang="en" dir="ltr" data-bs-theme="light" data-color-theme="Blue_Theme" data-layout="vertical">

<head>
  @include("partials/head")
  <title>Modernize Bootstrap Admin</title>
</head>

<body>
  <!-- Preloader -->
  <div class="preloader">
    <img src="{{ asset('assets') }}/images/logos/favicon.png" alt="loader" class="lds-ripple img-fluid" />
  </div>
  <div id="main-wrapper">
    <div class="position-relative overflow-hidden min-vh-100 w-100 d-flex align-items-center justify-content-center">
        <div class="d-flex align-items-center justify-content-center w-100">
            <div class="row justify-content-center w-100">
                <div class="col-lg-4">
                    <div class="text-center">
                        <img src="{{ asset('assets/images/backgrounds/403.svg') }}" alt="modernize-img" class="img-fluid" width="500">
                        <h1 class="fw-semibold mb-7 fs-9">Opps!!!</h1>
                        <h4 class="fw-semibold mb-7">User does not have the right permissions.</h4>

                        {{-- Debug: Show session only in local or development --}}
                        @if(in_array(env('APP_ENV'), ['local', 'development']))
                            <p>
                                <a class="btn btn-secondary mb-3" data-bs-toggle="collapse" href="#sessionCollapse" role="button" aria-expanded="false" aria-controls="sessionCollapse">
                                    Show Session Data
                                </a>
                            </p>
                            <div class="collapse" id="sessionCollapse">
                                <div class="card card-body text-start">
                                    <pre>{{ print_r(session('user'), true) }}</pre>
                                </div>
                            </div>
                        @endif

                        <a class="btn btn-primary" href="{{ url('/') }}" role="button">Go Back to Home</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </div>
  <div class="dark-transparent sidebartoggler"></div>
  @include("partials/scripts2")
</body>

</html>