<!-- ---------------------------------- -->
<!-- Start Vertical Layout Sidebar -->
<!-- ---------------------------------- -->
@include("partials/logo-sidebar")

<nav class="sidebar-nav scroll-sidebar" data-simplebar>
  <ul id="sidebarnav">
    <!-- ---------------------------------- -->
    <!-- Home -->
    <!-- ---------------------------------- -->
    <li class="nav-small-cap">
      <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
      <span class="hide-menu">Home</span>
    </li>
    <!-- ---------------------------------- -->
    <!-- Dashboard -->
    <!-- ---------------------------------- -->
    <li class="sidebar-item">
      <a class="sidebar-link {{ request()->is('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}" id="get-url" aria-expanded="false">
        <span>
          <i class="ti ti-aperture"></i>
        </span>
        <span class="hide-menu">Dashboard</span>
      </a>
    </li>
    <!-- <li class="sidebar-item">
      <a class="sidebar-link" href="{{ route('page.404') }}" aria-expanded="false">
        <span>
          <i class="ti ti-shopping-cart"></i>
        </span>
        <span class="hide-menu">Page 404</span>
      </a>
    </li> -->
    <!-- ---------------------------------- -->
    <!-- User Management -->
    <!-- ---------------------------------- -->
    @if (in_array(getPermission(), ['permissions-read', 'roles-read', 'users-read']))
      <li class="nav-small-cap">
        <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
        <span class="hide-menu">User Management</span>
      </li>
      <li class="sidebar-item">
        <a class="sidebar-link has-arrow {{ request()->is('user-management/*') ? 'active' : '' }}" href="javascript:void(0)" aria-expanded="false">
          <span>
            <i class="ti ti-shield-lock"></i>
          </span>
          <span class="hide-menu">User Management</span>
        </a>
        <ul aria-expanded="false" class="collapse first-level {{ request()->is('user-management/*') ? 'in' : '' }}">
          @can('permissions-read')
            <li class="sidebar-item">
              <a class="sidebar-link {{ request()->is('user-management/permissions*') ? 'active' : '' }}" href="{{ route('user_management.permissions.index') }}">
                <span><i class="ti ti-lock"></i></span>
                <span class="hide-menu">Permission</span>
              </a>
            </li>
          @endcan
          @can('roles-read')
            <li class="sidebar-item">
              <a class="sidebar-link {{ request()->is('user-management/roles*') ? 'active' : '' }}" href="{{ route('user_management.roles.index') }}">
                <span><i class="ti ti-key"></i></span>
                <span class="hide-menu">Roles</span>
              </a>
            </li>
          @endcan
          @can('users-read')
            <li class="sidebar-item">
              <a class="sidebar-link {{ request()->is('user-management/users*') ? 'active' : '' }}" href="{{ route('user_management.users.index') }}">
                <span><i class="ti ti-users"></i></span>
                <span class="hide-menu">User</span>
              </a>
            </li>
          @endcan
        </ul>
      </li>    
    @endif
    
  </ul>
</nav>

<div class="fixed-profile p-3 mx-4 mb-2 bg-secondary-subtle rounded mt-3">
  <div class="hstack gap-3">
    <div class="john-img">
      <img src="{{ asset('assets') }}/images/profile/user-1.jpg" class="rounded-circle" width="40" height="40" alt="modernize-img" />
    </div>
    <div class="john-title">
      <h6 class="mb-0 fs-4 fw-semibold">{{ auth_api_user()['name'] }}</h6>
      <span class="fs-2">Designer</span>
    </div>
    <form action="{{ route('logout') }}" method="POST" class="ms-auto">
      @csrf
      <button class="border-0 bg-transparent text-primary" tabindex="0" type="submit" aria-label="logout"
      data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="logout">
      <i class="ti ti-power fs-6"></i>
      </button>
    </form>
  </div>
</div>

<!-- ---------------------------------- -->
<!-- Start Vertical Layout Sidebar -->
<!-- ---------------------------------- -->