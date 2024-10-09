<footer class="footer bg-footer-homepage5">
    <div class="footer-1">
        <div class="container">
            <div class="row">
                <div class="col-lg-3 width-25 mb-30">
                    <h4 class="mb-30 color-gray-1000">Kontak</h4>
                    <div class="font-md mb-20 color-gray-900">
                        <strong class="font-md-bold">Alamat :</strong> 
                        {{ Helpers::generalSetting()->company_address }},
                        {{ " ". Helpers::generalSetting()->province }},
                        {{ " ". Helpers::generalSetting()->city }},
                        {{ " ". Helpers::generalSetting()->district }},
                        {{ " ". Helpers::generalSetting()->subdistrict }},
                        {{ " ". Helpers::generalSetting()->postcode }}
                    </div>
                    <div class="font-md mb-20 color-gray-900">
                        <strong class="font-md-bold">No. Telp :</strong>
                        {{ Helpers::generalSetting()->company_phone }}
                    </div>
                    <div class="font-md mb-20 color-gray-900">
                        <strong class="font-md-bold">E-mail :</strong>
                        {{ Helpers::generalSetting()->company_email }}
                    </div>
                    <div class="mt-30">
                        <a class="icon-socials icon-facebook" href="{{ Helpers::generalSetting()->facebook_url }}" target="_blank"></a>
                        <a class="icon-socials icon-instagram" href="{{ Helpers::generalSetting()->instagram_url }}" target="_blank"></a>
                        <a class="icon-socials icon-twitter" href="{{ Helpers::generalSetting()->twitter_url }}" target="_blank"></a>
                    </div>
                </div>
                <div class="col-lg-3 width-25 mb-30">
                    <h4 class="mb-30 color-gray-1000">Halaman</h4>
                    <ul class="menu-footer">
                        <li><a href="{{ url('contact') }}">Kontak Kami</a></li>
                        @foreach(Helpers::pages() as $page)
                        <li><a href="{{ url($page->slug) }}">{{ $page->title }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div class="col-lg-3 width-20 mb-30">
                    <h4 class="mb-30 color-gray-1000">Akun Saya</h4>
                    <ul class="menu-footer">
                        <li><a href="{{ url('profile') }}">Profil</a></li>
                        <li><a href="{{ url('cart') }}">Keranjang</a></li>
                        <li><a href="{{ url('wishlist') }}">Wishlist</a></li>
                        <li><a href="{{ url('chat') }}">Chatting</a></li>
                        <li><a href="{{ url('profile?tab=transaction') }}">Transaksi</a></li>
                    </ul>
                </div>
                <div class="col-lg-3 width-30">
                    <h4 class="mb-30 color-gray-1000">Aplikasi & Pembayaran</h4>
                    <div>
                        <p class="font-md color-gray-900">Download Aplikasi {{ Helpers::generalSetting()->system_name }} Sekarang Juga !
                        </p>
                        <div class="mt-20">
                            <a class="mr-10" href="#">
                                <img src="{{ url('frontend/assets/imgs/template/appstore.png') }}">
                            </a> 
                            <a href="#">
                                <img src="{{ url('frontend/assets/imgs/template/google-play.png') }}">
                            </a>
                        </div>
                        <p class="font-md color-gray-900 mt-20 mb-10">
                            {{ Helpers::generalSetting()->system_name }} Mendukung Fitur Pembayaran :
                        </p>
                        @foreach(Helpers::payments() as $paymentMethod)
                            <img class="pr-10" style="height:40px;width:60px;" src="{{ Storage::url($paymentMethod->logo) }}">
                        @endforeach
                        <h6>Powered By <img class="mt-15 ml-10" style="height:25px;width:100px;" src="https://collections.midtrans.com/assets/images/midtrans-logo.png"></h6>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-2">
        <div class="container">
            <div class="footer-bottom text-center">
                <span class="color-gray-900 font-sm text-center">
                    Copyright &copy; {{ date('Y') }} 
                    {{ Helpers::generalSetting()->system_name }}. All rights reserved.
                    <br/>Made by Kalo Berani
                </span>
            </div>
        </div>
    </div>
</footer>