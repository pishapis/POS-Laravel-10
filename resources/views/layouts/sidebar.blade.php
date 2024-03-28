<div class="main-sidebar sidebar-style-2 print:hidden">
    <aside id="sidebar-wrapper">
      <div class="sidebar-brand">
        <a href="index.html">Bintang Elektronik</a>
      </div>
      <div class="sidebar-brand sidebar-brand-sm">
        <a href="index.html">LP</a>
      </div>
      <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        <li class="{{ (Request::url() === url('/')
            || Request::url() === url('/admin')) ? 'active' : '' }}">
        <a href="{{ route('home') }}" class="nav-link"><i class="ion-android-home"></i> <span>Dashboard</span></a>
      </li>
      
      
        <li class="menu-header">Data Barang</li>
        <li class="{{ (Request::url() === route('product.index')
            || Request::url() === route('product-category.index')) ? 'dropdown active' : 'dropdown' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <i class="ion-cube"></i> <span>Produk</span>
          </a>
          <ul class="dropdown-menu">

          <li class="{{ (Request::url() === route('pembelian.index')) ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('pembelian.index') }}">
                <i class="fa fa-shopping-cart"></i> <span>Pembelian Produk</span></a>
            </li>

            <li class="{{ (Request::url() === route('product.index')) ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('product.index') }}">
                <i class="ion-android-list"></i> <span>Daftar Produk</span></a>
            </li>

            <li class="{{ (Request::url() === route('product-category.index')) ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('product-category.index') }}">
                <i class="ion-pricetags"></i> <span>Produk Kategori</span>
              </a>
            </li>

          </ul>

          @if (Auth::user()->roles == 'admin')
            <li class="menu-header">Data User & Pelanggan</li>
            <li class="{{ (Request::url() === route('user.index')) ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('user.index') }}"><i class="ion-android-person"></i> <span>Users</span></a>
          </li>
            <li class="{{ (Request::url() === route('customer.index')) ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('customer.index') }}"><i class="ion-person-stalker"></i> <span>Pelanggan</span></a>
            </li>

            <li class="{{ (Request::url() === route('coupon.index')) ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('coupon.index') }}"><i class="ion-cash"></i> <span>Kupon</span></a>
            </li>

            <li class="{{ (Request::url() === route('companyProfile.index')) ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('companyProfile.index') }}"><i class="ion-android-settings"></i> <span>Company Profile</span></a>
            </li>

          </li>
        </li>

      @endif

      <li class="menu-header">Penjualan</li>
        <li class="{{ (Request::is('transaction/index') || Request::is('transaction/report') || Request::is('transaction/create/*')) ? 'dropdown active' : 'dropdown' }}">
          <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
            <i class="ion-ios-cart"></i> <span>Transaksi</span>
          </a>
          <ul class="dropdown-menu">

            <li class="{{ Request::is('transaction/create/*') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('transaction.create', AppHelper::transaction_code()) }}">
                <i class="ion-bag"></i> <span>Transaksi Baru</span></a>
            </li>
            
            <li class="{{ Request::url() === route('transaction.index') ? 'active' : '' }}">
              <a class="nav-link" href="{{ route('transaction.index') }}">
                <i class="ion-ios-list"></i> <span>Daftar Transaksi</span></a>
            </li>

            <!-- <li class="{{ Request::url() === route('transaction.report') ? 'active' : '' }}">
              <a class="nav-link" href="#" data-toggle="modal" data-target="#transactionModal">
                <i class="ion-clipboard"></i> <span>Laporan Transaksi</span></a>
            </li> -->

          </ul>
        </li>
      </li>
      @if (Auth::user()->roles == 'admin')
        <li class="menu-header">Daftar Retur</li>
        <li class="{{ Request::url() === route('retur.index') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('retur.index') }}"><i class="fa fa-undo"></i> <span>Retur</span></a>
          </li>
        @endif
      </ul>   
    </aside>
</div>
