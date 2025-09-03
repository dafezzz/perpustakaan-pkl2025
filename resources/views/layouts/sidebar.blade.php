<ul class="navbar-nav sidebar sidebar-dark accordion shadow-sm" id="accordionSidebar"
    style="width: 220px; background: linear-gradient(180deg, #1e3c72 0%, #2a5298 100%);">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center py-3" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink fa-lg text-white"></i>
        </div>
        <div class="sidebar-brand-text mx-2 fw-bold fs-6 text-white">
            Perpustakaan Digital
        </div>
    </a>

    <hr class="sidebar-divider my-1 border-light">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider border-light">

    @if(auth()->user()->role === 'resident')
        <div class="sidebar-heading text-uppercase fw-bold text-light small">
            Manajemen User
        </div>

        <li class="nav-item {{ request()->is('resident*') || request()->is('petugas*') ? 'active' : '' }}">
            <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUserPetugas"
               aria-expanded="false" aria-controls="collapseUserPetugas">
                <i class="fas fa-fw fa-users"></i>
                <span>Data Admin & Petugas</span>
            </a>
            <div id="collapseUserPetugas"
                 class="collapse {{ request()->is('resident*') || request()->is('petugas*') ? 'show' : '' }}"
                 data-bs-parent="#accordionSidebar">
                <div class="bg-light py-2 collapse-inner rounded shadow-sm">
                    <a class="collapse-item {{ request()->is('resident*') ? 'active' : '' }}"
                       href="{{ route('resident.index') }}">
                        <i class="fas fa-fw fa-user"></i> Data Admin
                    </a>
                    <a class="collapse-item {{ request()->is('petugas*') ? 'active' : '' }}"
                       href="{{ route('petugas.index') }}">
                        <i class="fas fa-fw fa-user-shield"></i> Data Petugas
                    </a>
                </div>
            </div>
        </li>

        <hr class="sidebar-divider border-light">
    @endif

    <div class="sidebar-heading text-uppercase fw-bold text-light small">
        Manajemen Buku
    </div>

    <li class="nav-item {{ request()->is('books*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('books.index') }}">
            <i class="fas fa-fw fa-book"></i>
            <span>Daftar Buku</span>
        </a>
    </li>

    @if(!auth()->user()->role || !in_array(auth()->user()->role, ['member']))
        <li class="nav-item {{ request()->is('peminjaman*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('peminjaman.index') }}">
                <i class="fas fa-fw fa-book-reader"></i>
                <span>Pengajuan Peminjaman</span>
            </a>
        </li>

        <li class="nav-item {{ request()->is('pengembalian*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pengembalian.index') }}">
                <i class="fas fa-fw fa-undo-alt"></i>
                <span>Pengembalian Buku</span>
            </a>
        </li>

          <li class="nav-item {{ request()->is('rak*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('rak.index') }}">
                <i class="fas fa-fw fa-undo-alt"></i>
                <span>Rak Buku</span>
            </a>
        </li>
    @endif
<div class="sidebar-heading text-uppercase fw-bold text-light small">
            Logs
        </div>
    <li class="nav-item {{ request()->is('riwayat*') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('riwayat.index') }}">
            <i class="fas fa-fw fa-history"></i>
            <span>Riwayat</span>
        </a>
    </li>

    <hr class="sidebar-divider d-none d-md-block border-light">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>

<style>
/* Sidebar nuansa biru modern */
.sidebar .nav-item .nav-link {
    transition: all 0.25s ease;
    color: rgba(255, 255, 255, 0.85);
    font-size: 0.85rem;
    padding: 0.55rem 1rem;
}
.sidebar .nav-item .nav-link:hover,
.sidebar .nav-item.active .nav-link {
    background-color: rgba(255, 255, 255, 0.12);
    border-radius: 0.4rem;
    color: #fff;
}
.collapse-inner .collapse-item {
    transition: all 0.25s ease;
    color: #333;
    font-size: 0.82rem;
}
.collapse-inner .collapse-item:hover,
.collapse-inner .collapse-item.active {
    background-color: rgba(66, 133, 244, 0.12);
    color: #1a73e8;
    border-radius: 0.4rem;
}
.sidebar-heading {
    letter-spacing: 0.05em;
    font-size: 0.75rem;
    padding-left: 1rem;
    margin-top: 0.8rem;
}
</style>
