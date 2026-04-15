<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <ul class="nav">
        <li class="nav-item nav-profile">
            <a href="#" class="nav-link">
                <div class="nav-profile-image">
                    <img src="{{ asset('purple/assets/images/faces/face1.jpg') }}" alt="profile" />
                    <span class="login-status online"></span>
                </div>
                <div class="nav-profile-text d-flex flex-column">
                    <span class="font-weight-bold mb-2">{{ Auth::check() ? Auth::user()->name : 'Guest' }}</span>
                    <span class="text-secondary text-small">{{ Auth::check() ? Auth::user()->email : 'guest@example.com' }}</span>
                </div>
                <i class="mdi mdi-bookmark-check text-success nav-profile-badge"></i>
            </a>
        </li>
        
        <li class="nav-item {{ request()->is('home') ? 'active' : '' }}">
            <a class="nav-link" href="{{ url('/home') }}">
                <span class="menu-title">Dashboard</span>
                <i class="mdi mdi-home menu-icon"></i>
            </a>
        </li>
        
        <li class="nav-item {{ request()->is('kategori*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('kategori.index') }}">
                <span class="menu-title">Kategori</span>
                <i class="mdi mdi-bookmark menu-icon"></i>
            </a>
        </li>
        
        <li class="nav-item {{ request()->is('buku*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('buku.index') }}">
                <span class="menu-title">Buku</span>
                <i class="mdi mdi-book-open-page-variant menu-icon"></i>
            </a>
        </li>
        
        <li class="nav-item {{ request()->is('barang*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('barang.index') }}">
                <span class="menu-title">Barang</span>
                <i class="mdi mdi-package-variant menu-icon"></i>
            </a>
        </li>
        
        <li class="nav-item {{ request()->is('pdf*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('pdf.index') }}">
                <span class="menu-title">Generate PDF</span>
                <i class="mdi mdi-file-pdf menu-icon"></i>
            </a>
        </li>

        <!-- MENU JS & JQUERY (DROPDOWN) -->
        <li class="nav-item {{ request()->is('js-jquery*') ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#js-jquery" 
               aria-expanded="false" aria-controls="js-jquery">
                <span class="menu-title">JS & jQuery</span>
                <i class="mdi mdi-language-javascript menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="js-jquery">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('js-jquery.crud-table') }}">
                            CRUD Table HTML
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('js-jquery.crud-datatables') }}">
                            CRUD DataTables
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('js-jquery.select') }}">
                            Select & Select2
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <!-- MENU MODUL 5: WILAYAH INDONESIA -->
        <li class="nav-item {{ request()->is('wilayah*') ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#ui-wilayah" aria-expanded="false" aria-controls="ui-wilayah">
                <span class="menu-title">Wilayah Indonesia</span>
                <i class="mdi mdi-map-marker menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-wilayah">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('wilayah.ajax') }}">
                            jQuery AJAX
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('wilayah.axios') }}">
                            Axios
                        </a>
                    </li>
                </ul>
            </div>
        </li>
                <!-- MENU MODUL 5:POS -->
        <li class="nav-item {{ request()->is('pos*') ? 'active' : '' }}">
            <a class="nav-link" data-toggle="collapse" href="#ui-pos" aria-expanded="false" aria-controls="ui-pos">
                <span class="menu-title">Point of Sales</span>
                <i class="mdi mdi-cash-register menu-icon"></i>
                <i class="menu-arrow"></i>
            </a>
            <div class="collapse" id="ui-pos">
                <ul class="nav flex-column sub-menu">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pos.ajax') }}">
                            jQuery AJAX
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('pos.axios') }}">
                            Axios
                        </a>
                    </li>
                    <!-- PAYMENT GATEWAY -->
                    <li class="nav-item {{ request()->is('order*') ? 'active' : '' }}">
                        <a class="nav-link" href="{{ route('customer.order') }}">
                            <span class="menu-title">Pesan Makanan</span>
                            <i class="mdi mdi-food menu-icon"></i>
                        </a>
                    </li>
                    
                    @if(Auth::check() && Auth::user()->isVendor())
                    <li class="nav-item {{ request()->is('vendor*') ? 'active' : '' }}">
                        <a class="nav-link" data-toggle="collapse" href="#ui-vendor">
                            <span class="menu-title">Vendor</span>
                            <i class="mdi mdi-store menu-icon"></i>
                            <i class="menu-arrow"></i>
                        </a>
                        <div class="collapse" id="ui-vendor">
                            <ul class="nav flex-column sub-menu">
                                <li><a href="{{ route('vendor.dashboard') }}">Dashboard</a></li>
                                <li><a href="{{ route('vendor.menu.index') }}">Menu</a></li>
                                <li><a href="{{ route('vendor.orders') }}">Pesanan</a></li>
                            </ul>
                        </div>
                    </li>
                    @endif
                </ul>
            </div>
        </li>
    </ul>
</nav>