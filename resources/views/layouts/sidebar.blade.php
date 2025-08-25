<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('dashboard') }}">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><sup>Perpustakaan</sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item {{ request()->is('dashboard') ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider">

    @if(auth()->user()->role === 'resident')
    <!-- Heading -->
    <div class="sidebar-heading">
        Manajemen Data User
    </div>

    <!-- Nav Item - Dropdown User dan Petugas -->
    <li class="nav-item {{ request()->is('resident*') || request()->is('petugas*') ? 'active' : '' }}">
        <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#collapseUserPetugas"
            aria-expanded="false" aria-controls="collapseUserPetugas">
            <i class="fas fa-fw fa-users"></i>
            <span>Data Admin & Petugas</span>
        </a>
        <div id="collapseUserPetugas" class="collapse {{ request()->is('resident*') || request()->is('petugas*') ? 'show' : '' }}" data-bs-parent="#accordionSidebar">
            <div class="bg-white py-2 collapse-inner rounded">
                <a class="collapse-item {{ request()->is('resident*') ? 'active' : '' }}" href="{{ route('resident.index') }}">
                    <i class="fas fa-fw fa-user"></i> Data Admin
                </a>
                <a class="collapse-item {{ request()->is('petugas*') ? 'active' : '' }}" href="{{ route('petugas.index') }}">
                    <i class="fas fa-fw fa-user-shield"></i> Data Petugas
                </a>
            </div>
        </div>
    </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider">

    <!-- Heading -->
    <div class="sidebar-heading">
        Manajemen Buku
    </div>


    
    <!-- Nav Item - Books -->

<li class="nav-item {{ request()->is('books*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('books.index') }}">
        <i class="fas fa-fw fa-book"></i>
        <span>Daftar Buku</span>
    </a>
</li>



 @if(!auth()->user()->role || !in_array(auth()->user()->role, ['member']))
   <!-- Nav Item - Peminjaman -->
   <li class="nav-item {{ request()->is('peminjaman*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('peminjaman.index') }}">
        <i class="fas fa-fw fa-book"></i>
        <span>Pengajuan peminjaman</span>
    </a>
</li>
@endif

 @if(!auth()->user()->role || !in_array(auth()->user()->role, ['member']))
<li class="nav-item {{ request()->is('pengembalian*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('pengembalian.index') }}">
        <i class="fas fa-fw fa-book"></i>
        <span>Pengembalian Buku</span>
    </a>
</li>
@endif


<li class="nav-item {{ request()->is('riwayat*') ? 'active' : '' }}">
    <a class="nav-link" href="{{ route('riwayat.index') }}">
        <i class="fas fa-fw fa-book"></i>
        <span>Riwayat</span>
    </a>
</li>





    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
