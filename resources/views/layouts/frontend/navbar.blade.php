<header class="header header-container sticky-bar">
    <div class="container">
        <div class="main-header">
            <div class="header-left">
                <div class="header-logo">
                    <a href="{{ url('') }}">
                        <img src="{{ Storage::url(Helpers::generalSetting()->logo) }}">
                    </a>
                </div>
                <div class="header-search">
                    <div class="box-header-search ml-20">
                        <form class="form-search" method="GET" action="">
                            <div class="box-category">
                                <select class="select-active select2-hidden-accessible" id="optionSearch">
                                    <option value="all">Semua</option>
                                    <option value="byProduct">Dari Produk</option>
                                    <option value="byCategory">Dari Kategori</option>
                                    <option value="byTag">Dari Tag</option>
                                    <option value="byMerchant">Dari Penjual</option>
                                </select>
                            </div>
                            <div class="box-keysearch">
                                <input id="searchSomething" class="form-control font-xs" type="text" value="" placeholder="Cari Sesuatu...">
                            </div>
                        </form>
                    </div>
                    <div class="card shadow ml-10 mt-5" id="cardSearch" style="z-index:999;position:absolute;width:457px;max-height:auto;display:none">
                        <div class="loading" style="margin:0px auto;margin-bottom:-35px;display:none">
                            <img class='mt-10 mb-10' src='{{ url('images/loading2.gif') }}' style='height:20px;width:20px;'>
                        </div>
                        <div class="card-body" id="cardSearchBody">
                            
                        </div>
                    </div>
                </div>
                <div class="header-nav text-start">
                    <nav class="nav-main-menu d-none d-xl-block">
                        <ul class="main-menu">
                            <li><a class="active" href="{{ url('profile?tab=transaction') }}">Transaksi</a></li>
                            <li><a href="{{ url('cart') }}">Keranjang</a></li>
                        </ul>
                    </nav>
                    <div class="burger-icon burger-icon-white">
                        <span class="burger-icon-top"></span>
                        <span class="burger-icon-mid"></span>
                        <span class="burger-icon-bottom"></span>
                    </div>
                </div>
                <div class="header-shop">
                    @if(!Auth::check())
                    <div class="d-inline-block font-md">
                        <a href="{{ url('login') }}" class="btn btn-cart"><i class="bi bi-box-arrow-right"></i> Login</a>
                    </div>
                    <div class="d-inline-block font-md">
                        <div class="d-inline-block font-md">
                            <a href="{{ url('login') }}" class="btn btn-cart"><i class="bi bi-card-text"></i> Registrasi</a>
                        </div>
                    </div>
                    @else
                    <div class="d-inline-block box-dropdown-cart">
                        @php
                            $unreadNotifications = auth()->user()->unreadNotifications->count();
                            $unSeenMessages = auth()->user()->un_seen_messages;
                        @endphp
                        <span class="font-lg icon-list icon-account">
                            <span>Account</span>
                        </span>
                        <span class="number-item font-xs" id="notifTotal">{{ $unreadNotifications + $unSeenMessages }}</span>
                        <div class="dropdown-account">
                            <ul>
                                <li><a href="{{ url('profile') }}"><i class="bi bi-person-fill mr-5"></i> Akun Saya</a></li>
                                <li><a href="{{ url('dashboard') }}"><i class="bi bi-grid mr-5"></i> Dashboard</a></li>
                                <li><a href="{{ url('chat') }}"><i class="bi bi-chat-dots mr-5"></i> Chat (<a id="countMessage">{{ $unSeenMessages }}</a>)</a></li>
                                <li><a href="{{ url('profile?tab=notification') }}"><i class="bi bi-bell mr-5"></i> Notifikasi (<a id="countNotif"> {{ $unreadNotifications }}</a>)</li>
                                <li><a href="{{ url('dashboard/request-for-quotation') }}"><i class="bi bi-card-list mr-5"></i> Permintaan</a></li>
                                <li><a href="{{ url('dashboard/quotation') }}"><i class="bi bi-minecart mr-5"></i> Penawaran</a></li>
                                <li><a href="{{ url('dashboard/purchase-order') }}"><i class="bi bi-cart-check-fill mr-5"></i> Pesanan</a></li>
                                <li><a href="{{ url('dashboard/invoice') }}"><i class="bi bi-currency-dollar mr-5"></i> Tagihan</a></li>
                                <li><a href="{{ url('logout') }}"><i class="bi bi-box-arrow-left mr-5"></i> Keluar</a></li>
                            </ul>
                        </div>
                    </div>
                    <a class="font-lg icon-list icon-wishlist" href="{{ url('wishlist') }}">
                        <span>Wishlist</span>
                        <span class="number-item font-xs">
                            {{ auth()->user()->wishlist->count() }}
                        </span>
                    </a>
                    <div class="d-inline-block box-dropdown-cart">
                        <span class="font-lg icon-list icon-cart">
                            <span>Cart</span>
                            <span class="number-item font-xs">{{ auth()->user()->cart->count() }}</span>
                        </span>
                        <div class="dropdown-cart">
                            @if(auth()->user()->cart->count() == 0)
                                <span class="item-cart font-xs text-center mt-10 mb-10">Keranjang Kosong</span>
                            @endif
                            @foreach(auth()->user()->cart()->get() as $cart)
                            <div class="item-cart mb-20">
                                <div class="cart-image">
                                    <img src="{{ Helpers::image($cart->productSku->product->thumbnail_image_source) }}">
                                </div>
                                <div class="cart-info">
                                    <a class="font-sm-bold color-brand-3" href="{{ url('product/'.$cart->productSku->product->slug.'') }}">
                                        {{ $cart->productSku->product->product_name ." - ". $cart->productSku->varian }}
                                    </a>
                                    <p>
                                        <span class="color-brand-4 font-sm-bold">Rp. {{ number_format($cart->subtotal,0,'.','.') }}</span>
                                        <span class="text-secondary font-xs">(Qty {{ $cart->qty }})</span>
                                    </p>
                                </div>
                            </div>
                            @endforeach
                            <div class="border-bottom pt-0 mb-15"></div>
                            <div class="cart-total">
                                <div class="row">
                                    <div class="col-6 text-start">
                                        <span class="font-md-bold color-brand-3">Subtotal</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="font-md-bold color-brand-4">
                                            Rp. {{ number_format(auth()->user()->cart()->sum('subtotal'), 0,'.','.') }}
                                        </span>
                                    </div>
                                    <div class="col-6 text-start">
                                        <span class="font-md-bold color-brand-3">PPN</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="font-md-bold color-brand-4">
                                            Rp. {{ number_format(auth()->user()->cart()->sum('tax_amount'), 0,'.','.') }}
                                        </span>
                                    </div>
                                    <div class="col-6 text-start">
                                        <span class="font-md-bold color-brand-3">Grand Total</span>
                                    </div>
                                    <div class="col-6">
                                        <span class="font-md-bold color-brand-4">
                                            Rp. {{ number_format(auth()->user()->cart()->sum('total_price'), 0,'.','.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="row mt-15">
                                    <div class="col-6 text-start">
                                        <a class="btn btn-cart w-auto" href="{{ url('cart') }}">Lihat</a>
                                    </div>
                                    <div class="col-6">
                                        <a class="btn btn-buy w-auto" href="{{ url('dashboard/request-for-quotation-form') }}">
                                            Lanjut RFQ
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="header-bottom">
        <div class="container">
            <div class="dropdown d-inline-block">
                <button class="btn dropdown-toggle btn-category" id="dropdownCategory" type="button" data-bs-toggle="dropdown" aria-expanded="true" data-bs-display="static">
                    <span lass="dropdown-right font-sm-bold color-white">&nbsp;Lihat Kategori</span>
                </button>
                <div class="sidebar-left dropdown-menu dropdown-menu-light" aria-labelledby="dropdownCategory" data-bs-popper="static">
                    <ul class="menu-texts menu-close">
                        @foreach(Helpers::categories() as $cat)
                            @php $subCatCount = $cat->subCategories->count() @endphp
                            <li class="{{ $subCatCount > 0 ? "has-children" : "" }}">
                                <a href="{{ url('products/'.$cat->slug.'/category') }}">{{ $cat->name }}</a>
                                @if($subCatCount > 0)
                                    <ul class="sub-menu">
                                        @foreach($cat->subCategories()->get() as $subCat)
                                            <li>
                                                <a href="{{ url('products/'.$subCat->slug.'/category') }}">{{ $subCat->name }}</a>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
     <div class="mobile-header-active mobile-header-wrapper-style perfect-scrollbar">
        <div class="mobile-header-wrapper-inner">
            <div class="mobile-header-content-area">
                <div class="mobile-logo">
                    <a class="d-flex" href="{{ url('') }}">
                        <img src="{{ Storage::url(Helpers::generalSetting()->logo) }}">
                    </a>
                </div>
                <div class="perfect-scroll">
                    <div class="mobile-menu-wrap mobile-header-border">
                        <nav class="mt-15">
                            <ul class="mobile-menu font-heading">
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mobile-header-active mobile-header-wrapper-style perfect-scrollbar">
        <div class="mobile-header-wrapper-inner">
            <div class="mobile-header-content-area">
                <div class="mobile-logo">
                    <a class="d-flex" href="{{ url('') }}">
                        <img src="{{ Storage::url(Helpers::generalSetting()->logo) }}">
                    </a>
                </div>
                <div class="perfect-scroll">
                    <div class="mobile-menu-wrap mobile-header-border">
                        <nav class="mt-15">
                            <ul class="mobile-menu font-heading">
                                <li class="has-children"><a href="#">Kategori</a>
                                    <ul class="sub-menu">
                                        @foreach(Helpers::categories() as $cat)
                                            @php $subCatCount = $cat->subCategories->count() @endphp
                                            <li class="{{ $subCatCount > 0 ? "has-children" : "" }}">
                                                <a href="{{ $subCatCount > 0 ? "#" : '/products/'.$cat->slug.'/category' }}">{{ $cat->name }}</a>
                                                @if($subCatCount > 0)
                                                    <ul class="sub-menu ml-15">
                                                        @foreach($cat->subCategories()->get() as $subCat)
                                                            <li>
                                                                <a href='/products/{{ $subCat->slug }}/category'>{{ $subCat->name }}</a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                                @if(Auth::check())
                                <li><a href="{{ url('profile') }}">Akun Saya</a></li>
                                <li><a href="{{ url('profile?tab=transaction') }}">Transaksi</a></li>
                                <li><a href="{{ url('cart') }}">Keranjang ({{ count(auth()->user()->cart ?? []) }})</a></li>
                                <li><a href="{{ url('wishlist') }}">Wishlist ({{ count(auth()->user()->wishlist ?? []) }})</a></li>
                                @else 
                                <li><a href="{{ url('login') }}">Login</a></li>
                                <li><a href="{{ url('register') }}">Register</a></li>
                                @endif
                                <li class="has-children"><a href="#">Halaman</a>
                                    <ul class="sub-menu">
                                        <li><a href="{{ url('contact') }}">Kontak Kami</a></li>
                                        @foreach(Helpers::pages() as $page)
                                            <li><a href="{{ url($page->slug) }}">{{ $page->title }}</a></li>
                                        @endforeach
                                    </ul>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    @if(auth()->check())
                    <div class="mobile-account">
                        <div class="mobile-header-top">
                            <div class="user-account"><a href="{{ url('profile') }}">
                                <img src="{{ Helpers::photoProfile(auth()->user()->photo) }}"></a>
                                <div class="content">
                                    <h6 class="user-name"><span class="text-brand">{{ auth()->user()->name }}</span></h6>
                                    <p class="font-xs text-muted">
                                        {{ auth()->user()->email }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <ul class="mobile-menu">
                            <li><a href="{{ url('profile') }}">Akun Saya</a></li>
                            <li><a href="{{ url('dashboard/request-for-quotation') }}">Permintaan</a></li>
                            <li><a href="{{ url('dashboard/quotation') }}">Penawaran</a></li>
                            <li><a href="{{ url('dashboard/purchase-order') }}">Pesanan</a></li>
                            <li><a href="{{ url('dashboard/invoice') }}">Tagihan</a></li>
                            <li><a href="{{ url('logout') }}">Keluar</a></li>
                        </ul>
                    </div>
                    @endif
                    <div class="site-copyright color-brand-3 mt-30 text-center">
                        Copyright {{ date('Y') }} All Rights Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
