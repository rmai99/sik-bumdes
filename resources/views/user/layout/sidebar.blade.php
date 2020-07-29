<ul class="nav">    
    <li class="nav-item {{ Request::segment(1) === 'main' ? 'active' : null }}  ">
        <a href="{{route('main.index')}}" class="nav-link">
            <i class="material-icons">dashboard</i>
            <p>Dashboard</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'profile' ? 'active' : null }}">
        <a class="nav-link" data-toggle="collapse" href="#profile" aria-expanded="true">
            <i class="material-icons">person</i>
            <p> Kelola Unit Usaha
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse {{ Request::segment(1) === 'profile' || Request::segment(1) === 'karyawan' || Request::segment(1) === 'bisnis' || Request::segment(1) === 'akun_anggaran' || Request::segment(1) === 'rencana_anggaran' || Request::segment(1) === 'realisasi_anggaran' ? 'show' : null }}" id="profile">
            <ul class="nav m-0">
                <li class="nav-item {{ Request::segment(1) === 'profile' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('profile.index') }}">
                        <span class="sidebar-mini"> UP </span>
                        <span class="sidebar-normal"> Profil </span>
                    </a>
                </li>
                @role('company')
                <li class="nav-item {{ Request::segment(1) === 'karyawan' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('karyawan.index') }}">
                        <span class="sidebar-mini"> MK </span>
                        <span class="sidebar-normal">Manajemen Karyawan </span>
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(1) === 'bisnis' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('bisnis.index') }}">
                        <span class="sidebar-mini"> MB </span>
                        <span class="sidebar-normal"> Manajemen Bisnis </span>
                    </a>
                </li>
                @endrole
                <li class="nav-item {{ Request::segment(1) === 'akun_anggaran' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('akun_anggaran.index') }}">
                        <span class="sidebar-mini"> A </span>
                        <span class="sidebar-normal"> Anggaran </span>
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(1) === 'rencana_anggaran' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('rencana_anggaran.index') }}">
                        <span class="sidebar-mini"> RAB </span>
                        <span class="sidebar-normal"> Rencana Anggaran Bisnis </span>
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(1) === 'realisasi_anggaran' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('realisasi.show') }}">
                        <span class="sidebar-mini"> LRA </span>
                        <span class="sidebar-normal">Laporan Rencana Anggaran</span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'akun' ? 'active' : null }}">
        <a class="nav-link" href="{{ route('akun.index') }}">
            <i class="material-icons">content_paste</i>
            <p>Akun</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'neraca_awal' ? 'active' : null }}">
        <a class="nav-link" href="{{ route('neraca_awal.index') }}">
            <i class="material-icons">view_list</i>
            <p>Neraca Awal</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'jurnal_umum' ? 'active' : null }}">
        <a class="nav-link" href="{{ route('jurnal_umum.index') }}">
            <i class="material-icons">list</i>
            <p>Jurnal Umum</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'buku_besar' ? 'active' : null }}">
        <a class="nav-link" href="{{ route('buku_besar.index') }}">
            <i class="material-icons">library_books</i>
            <p>Buku Besar</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'neraca_saldo' ? 'active' : null }}">
        <a class="nav-link" href="{{ route('neraca_saldo.index') }}">
            <i class="material-icons">graphic_eq</i>
            <p>Neraca Saldo</p>
        </a>
    </li>
    <li class="nav-item {{ Request::segment(1) === 'laporan' ? 'active' : null }}">
        <a class="nav-link" data-toggle="collapse" href="#laporanKeuangan" aria-expanded="true">
            <i class="material-icons">notifications</i>
            <p> Laporan Keuangan
                <b class="caret"></b>
            </p>
        </a>
        <div class="collapse {{ Request::segment(1) === 'laporan_laba_rugi' || Request::segment(1) === 'perubahan_ekuitas' || Request::segment(1) === 'neraca' ? 'show' : null }}" id="laporanKeuangan">
            <ul class="nav m-0">
                <li class="nav-item {{ Request::segment(1) === 'laporan_laba_rugi' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('laporan_laba_rugi') }}">
                        <span class="sidebar-mini"> LLR </span>
                        <span class="sidebar-normal"> Laporan Laba Rugi </span>
                    </a>
            </li>
                <li class="nav-item {{ Request::segment(1) === 'perubahan_ekuitas' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('perubahan_ekuitas') }}">
                        <span class="sidebar-mini"> LPE </span>
                        <span class="sidebar-normal"> Laporan Perubahan Ekuitas </span>
                    </a>
                </li>
                <li class="nav-item {{ Request::segment(1) === 'neraca' ? 'active' : null }}">
                    <a class="nav-link" href="{{ route('neraca') }}">
                        <span class="sidebar-mini"> N </span>
                        <span class="sidebar-normal"> Neraca </span>
                    </a>
                </li>
            </ul>
        </div>
    </li>
    @role('company')
    @if (DB::table('companies')->where('id_user','=',Auth::user()->id)->first()->is_actived == 0)
    <li class="nav-item {{ Request::segment(1) === 'upgrade' ? 'active' : null }}">
        <a class="nav-link" href="{{ route('upgrade') }}">
            <i class="material-icons">unarchive</i>
            <p>Upgrade to PRO</p>
        </a>
    </li>
    @endif
    @endrole
</ul>