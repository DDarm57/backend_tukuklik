@extends('layouts.frontend.app', ['title' => 'Kontak'])

@push('meta')
<meta name="title" content="{{ $page->meta_title }}">
<meta name="description" content="{{ $page->meta_description }}">
<meta name="keywords" content="{{ $page->meta_keywords }}">
@endpush

@section('content')
<main class="main">
    <div class="section-box">
        <div class="breadcrumbs-div mb-0">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a class="font-xs color-gray-1000" href="{{ url('') }}">Homepage</a></li>
                    <li><a class="font-xs color-gray-500" href="{{ url($page->slug) }}">{{ $page->title }}</a></li>
                </ul>
            </div>
        </div>
    </div>
    <section class="section-box shop-template mt-30">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 mx-auto page-content">
                    <h2 class="text-center mb-20">{{ $page->title }}</h2>
                    {!! $page->content !!}
                </div>
            </div>
        </div>
    </section>
</main>
@endsection