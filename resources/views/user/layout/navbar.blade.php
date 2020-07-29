<nav class="navbar navbar-expand-lg pb-0" color-on-scroll="500">
    <div class=" container-fluid">
        <a class="navbar-brand" href=""> @yield('title-page') </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
            <span class="navbar-toggler-icon icon-bar"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-end">
            <form class="navbar-form">
            </form>
            <ul class="navbar-nav">
                @role('company')
                <li class="nav-item dropdown">
                    <a class="nav-link" href="" id="navbarDropdownBusiness" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="material-icons">business</i>
                        <span class="text-dark" style="margin-bottom: 0px;">{{$getBusiness->business_name}} (AKTIF)</span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownBusiness">
                        @foreach ($business as $item)
                            <a class="dropdown-item" value="{{ $item->id }}" href="{{route('setBusiness', $item->id)}}">{{ $item->business_name }} {{ $item->id == $session ? "(AKTIF)" : '' }}</a>
                        @endforeach
                    </div>
                </li>
                @endrole
                @role('employee')
                <li class="nav-item dropdown">
                    <a class="nav-link" href="" id="navbarDropdownBusiness" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        <i class="material-icons">business</i>
                        <span style="margin-bottom: 0px;">{{$getBusiness->business->business_name}}(AKTIF)</span>
                    </a>
                </li>
                @endrole
                <li class="nav-item dropdown">
                    
                    <a class="nav-link" href="" id="navbarDropdownProfile" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false">
                        <i class="material-icons">person</i>
                        <p class="d-lg-none d-md-block">
                            Pengaturan
                        </p>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownProfile">
                        <a class="dropdown-item" href="{{ route('profile.index') }}">Profil</a>
                        <a class="dropdown-item" href="{{ route('ganti_password.index') }}">Ganti Kata Sandi</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Log out
                        </a>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
