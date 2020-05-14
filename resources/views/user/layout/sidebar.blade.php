<ul class="nav">    
    <li class="nav-item {{ Request::segment(1) === 'dashboard' ? 'active' : null }}  ">
        <a href="/dashboard" class="nav-link">
            <i class="material-icons">dashboard</i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'profile' ? 'active' : null }}">
        <a class="nav-link" data-toggle="collapse" href="#profile" aria-expanded="true">
            <i class="material-icons">person</i>
            <p> Kelola Perusahaan
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse" id="profile">
            <ul class="nav m-0">
                <li class="nav-item {{ Request::segment(1) === 'profile' ? 'active' : null }}">
                    <a class="nav-link" href="/profile">
                        <span class="sidebar-mini"> UP </span>
                        <span class="sidebar-normal"> Profil </span>
                    </a>
                </li>
                @role('company')
                <li class="nav-item {{ Request::segment(1) === 'karyawan' ? 'active' : null }}">
                    <a class="nav-link" href="/karyawan">
                        <span class="sidebar-mini"> UM </span>
                        <span class="sidebar-normal">Manajemen Karyawan </span>
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(1) === 'bisnis' ? 'active' : null }}">
                    <a class="nav-link" href="/bisnis">
                        <span class="sidebar-mini"> UM </span>
                        <span class="sidebar-normal"> Manajemen Bisnis </span>
                    </a>
                </li>
                @endrole
            </ul>
        </div>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'akun' ? 'active' : null }}">
        <a class="nav-link" href="/akun">
            <i class="material-icons">content_paste</i>
            <p>Akun</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'neraca_awal' ? 'active' : null }}">
        <a class="nav-link" href="/neraca_awal">
            <i class="material-icons">library_books</i>
            <p>Neraca Awal</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'jurnal_umum' ? 'active' : null }}">
        <a class="nav-link" href="/jurnal_umum">
            <i class="material-icons">bubble_chart</i>
            <p>Jurnal Umum</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'buku_besar' ? 'active' : null }}">
        <a class="nav-link" href="/buku_besar">
            <i class="material-icons">location_ons</i>
            <p>Buku Besar</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'neraca_saldo' ? 'active' : null }}">
        <a class="nav-link" href="/neraca_saldo">
            <i class="material-icons">notifications</i>
            <p>Neraca Saldo</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'laporan' ? 'active' : null }}">
        <a class="nav-link" data-toggle="collapse" href="#laporanKeuangan" aria-expanded="true">
            <i class="material-icons">language</i>
            <p> Laporan Keuangan
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse" id="laporanKeuangan">
            <ul class="nav m-0">
                <li class="nav-item {{ Request::segment(1) === 'laporan_laba_rugi' ? 'active' : null }}">
                    <a class="nav-link" href="/laporan_laba_rugi">
                        <span class="sidebar-mini"> P </span>
                        <span class="sidebar-normal"> Laporan Laba Rugi </span>
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(1) === 'perubahan_ekuitas' ? 'active' : null }}">
                    <a class="nav-link" href="/perubahan_ekuitas">
                        <span class="sidebar-mini"> RS </span>
                        <span class="sidebar-normal"> Laporan Perubahan Ekuitas </span>
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(1) === 'neraca' ? 'active' : null }}">
                    <a class="nav-link" href="/neraca">
                        <span class="sidebar-mini"> T </span>
                        <span class="sidebar-normal"> Neraca </span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    @role('company')
    @if (DB::table('companies')->where('id_user','=',Auth::user()->id)->first()->is_actived == 0)
    <li class="nav-item ">
        <a class="nav-link" href="/home">
            <i class="material-icons">unarchive</i>
            <p>Upgrade to PRO</p>
        </a>
    </li>
    @endif
    @endrole
</ul>