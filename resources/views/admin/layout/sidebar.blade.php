<ul class="nav">    
    <li class="nav-item {{ Request::segment(1) === 'admin' && Request::segment(2) === null ? 'active' : null }}  ">
        <a href="{{ route('admin.index') }}" class="nav-link">
            <i class="material-icons">dashboard</i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(2) === 'user' ? 'active' : null }}">
        <a class="nav-link" href="{{ route('admin.user.index')}}">
            <i class="material-icons">content_paste</i>
            <p>Pengguna</p>
        </a>
    </li>
    @role('super admin')
    <li class="nav-item">
        <a class="nav-link" data-toggle="collapse" href="#profile" aria-expanded="true">
            <i class="material-icons">person</i>
            <p> Admin
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse {{ Request::segment(2) === 'manajemen_admin' || Request::segment(2) === 'tambah_admin' ? 'show' : null }}" id="profile">
            <ul class="nav m-0">
                <li class="nav-item {{ Request::segment(2) === 'manajemen_admin' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('admin.manajemen_admin.index')}}">
                        <span class="sidebar-mini"> UP </span>
                        <span class="sidebar-normal"> Daftar Admin </span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    @endrole
</ul>