@extends('layouts.frontend.app', ['title' => 'Kontak'])

@push('meta')
<meta name="title" content="Kontak Kami">
<meta name="description" content="Jangan Ragu Untuk Menghubungi Kami!">
@endpush

@section('content')
<main class="main">
    <div class="section-box">
        <div class="breadcrumbs-div mb-0">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a class="font-xs color-gray-1000" href="{{ url('') }}">Homepage</a></li>
                    <li><a class="font-xs color-gray-500" href="{{ url('contact') }}">Kontak</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section class="section-box shop-template mt-0">
        <div class="container">
            <div>
                <div class="row">
                    <div class="col-lg-6">
                        <div class="contact-form">
                            <h3 class="color-brand-3 mt-60">Kontak Kami</h3>
                            <p class="font-sm color-gray-700 mb-30">Jangan ragu untuk hubungi kami!</p>
                            <div class="row">
                                <form action="{{ url('contact') }}" method="POST">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <input class="form-control @error('full_name') is-invalid @enderror" name="full_name" type="text" placeholder="Nama Lengkap">
                                            @error('full_name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input class="form-control @error('email') is-invalid @enderror" name="email"  type="email" placeholder="Email">
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input class="form-control @error('subject') is-invalid @enderror" name="subject" type="tel" placeholder="Judul/Subject">
                                            @error('subject')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <textarea name="message" class="form-control @error('message') is-invalid @enderror" placeholder="Message" rows="5"></textarea>
                                            @error('message')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="form-group">
                                            <input class="btn btn-buy w-auto" type="submit" value="Kirim Pesan">
                                        </div>
                                    </div>
                                    @csrf
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="map">
                            {!! Helpers::generalSetting()->maps_iframe !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box-contact-support pt-80 pb-50 background-gray-50">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 mb-30 text-center text-lg-start">
                        <h3 class="mb-5">Informasi Kontak Perusahaan</h3>
                        <p class="font-sm color-gray-700">Jangan Ragu Untuk Hubungi Kami!</p>
                    </div>
                    <div class="col-lg-3 text-center mb-30">
                        <div class="box-image mb-20">
                            <img src="{{ url('frontend/assets/imgs/page/contact/chat.svg') }}" alt="Ecom">
                        </div>
                        <h4 class="mb-5">Chat to sales</h4>
                        <p class="font-sm color-gray-700 mb-5">Speak to our team.</p>
                        <a class="font-sm color-gray-900" href="mailto:{{ Helpers::generalSetting()->company_email }}">{{ Helpers::generalSetting()->company_email ?? 'Email Belum Diatur' }}</a>
                    </div>
                    <div class="col-lg-3 text-center mb-30">
                        <div class="box-image mb-20">
                            <img src="{{ url('frontend/assets/imgs/page/contact/call.svg') }}" alt="Ecom">
                        </div>
                        <h4 class="mb-5">Call us</h4>
                        <p class="font-sm color-gray-700 mb-5">Mon-Fri from 8am to 5pm</p>
                        <a class="font-sm color-gray-900" href="tel:{{ Helpers::generalSetting()->company_phone }}">
                            {{ Helpers::generalSetting()->company_phone ?? 'No. Telepon Belum Diatur' }}
                        </a>
                    </div>
                    <div class="col-lg-3 text-center mb-30">
                        <div class="box-image mb-20">
                            <img src="{{ url('frontend/assets/imgs/page/contact/map.svg') }}" alt="Ecom">
                        </div>
                        <h4 class="mb-5">Visit us</h4>
                        <p class="font-sm color-gray-700 mb-5">Visit our office</p>
                        <span class="font-sm color-gray-900">
                            @if(Helpers::generalSetting()->company_address != "")
                                {{ Helpers::generalSetting()->company_address }},
                                {{ " ". Helpers::generalSetting()->province }},
                                {{ " ". Helpers::generalSetting()->city }},
                                {{ " ". Helpers::generalSetting()->district }},
                                {{ " ". Helpers::generalSetting()->subdistrict }},
                                {{ " ". Helpers::generalSetting()->postcode }}
                            @else 
                                Alamat Belum Diatur
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
@endsection