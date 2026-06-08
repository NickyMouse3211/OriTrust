 <div id="main-wrapper" class="auth-customizer-none">
    <div class="position-relative overflow-hidden radial-gradient min-vh-100 w-100">
      <div class="position-relative z-index-5">
        <div class="row">
          <div class="col-xl-7 col-xxl-8">
            <a href="index.php" class="text-nowrap logo-img d-block px-4 py-9 w-100">
              <img src="{{ asset('assets') }}/images/logos/dark-logo.svg" class="dark-logo" alt="Logo-Dark" />
              <img src="{{ asset('assets') }}/images/logos/light-logo.svg" class="light-logo" alt="Logo-light" />
            </a>
            <div class="d-none d-xl-flex align-items-center justify-content-center h-n80">
              <img src="{{ asset('assets') }}/images/backgrounds/login-security.svg" alt="modernize-img" class="img-fluid"
                width="500">
            </div>
          </div>
          <div class="col-xl-5 col-xxl-4">
            <div class="authentication-login min-vh-100 bg-body row justify-content-center align-items-center p-4">
              <div class="auth-max-width col-sm-8 col-md-6 col-xl-7 px-4">
                <h2 class="mb-1 fs-7 fw-bolder">Activate The Application</h2>
                <p class="mb-7">{{config('app.instance_id')}}</p>
                
                <!-- Session Status -->
                 @error('activation') 
                    <div class="alert alert-danger mb-3 text-center small">
                        {{ $message }}
                    </div> 
                @enderror
                <form wire:submit="activation">
                  <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Key</label>
                    <input type="text" wire:model="key" placeholder="Key" class="form-control" id="exampleInputKey" aria-describedby="keyHelp">
                  </div>
                  <button type="submit" class="btn btn-primary w-100 py-8 mb-4 rounded-2">Activate</button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>