<header class="c-header c-header-light c-header-fixed c-header-with-subheader">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <button class="c-header-toggler c-class-toggler d-lg-none mr-auto" type="button" data-target="#sidebar" data-class="c-sidebar-show">
        <span class="c-header-toggler-icon"></span>
    </button>
    <button class="c-header-toggler c-class-toggler ml-3 d-md-down-none" type="button" data-target="#sidebar" data-class="c-sidebar-lg-show" responsive="true"><span class="c-header-toggler-icon"></span></button>
    {{-- <ul class="c-header-nav d-md-down-none">
        <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="#">Dashboard</a></li>
        <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="#">Users</a></li>
        <li class="c-header-nav-item px-3"><a class="c-header-nav-link" href="#">Settings</a></li>
    </ul> --}}
    <ul class="c-header-nav mfs-auto">
        <li class="c-header-nav-item px-3">
            <button class="c-class-toggler c-header-nav-btn" type="button" id="header-tooltip" data-target="body" data-class="c-dark-theme" data-toggle="c-tooltip" data-placement="bottom" title="" data-original-title="Toggle Light/Dark Mode">
            <i class="c-icon fas fa-adjust"></i>
            </button>
        </li>
    </ul>
    
    <ul class="c-header-nav ">
        {{-- <li class="c-header-nav-item d-md-down-none mx-2"><a class="c-header-nav-link" href="#"><<i class="far fa-bell"></i></a></li>
        <li class="c-header-nav-item d-md-down-none mx-2"><a class="c-header-nav-link" href="#"><i class="far fa-bell"></i></a></li>
        <li class="c-header-nav-item d-md-down-none mx-2"><a class="c-header-nav-link" href="#"><i class="fas fa-list-ul"></i></a></li> --}}
        <li class="c-header-nav-item dropdown">
              <a class="c-header-nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                  {{-- <div class="c-avatar"><img class="c-avatar-img" src="back-end/image/ex.png" alt=""></div> --}}
                  <div class="c-avatar">{{ Auth::user()->name }}</div>
              </a>
            <div class="dropdown-menu dropdown-menu-right pt-0">
                <div class="dropdown-header bg-light py-2"><strong>Account</strong></div>
                {{-- <div class="dropdown-divider"></div> --}}
                <a class="dropdown-item" href="{{ url('/logout') }}"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </li>
        <li class="c-header-nav-item d-md-down-none mx-2"></li>
    </ul>
</header>