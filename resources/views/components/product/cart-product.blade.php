<div class="card-grid-style-3 hover-show">
    <div class="card-grid-inner shadow-sm">
        <div class="tools">
            <a class="btn btn-wishlist btn-tooltip mb-10" href="shop-wishlist.html" aria-label="Tambah Wishlist"></a>
        </div>
        <div class="image-box">
            <span class="label bg-color-danger">{{ $stockType }}</span>
            <a href="{{ url('product/'.$slug.'') }}">
                <img src="{{ Helpers::image($thumbnail) }}" alt="{{ $productName }}">
            </a>
        </div>
        <div class="info-right">
            <span class="font-xs color-danger">Min Order {{ $minimum. " ". $unit }}</span>
            <br>
            <a class="color-brand-3 font-md-bold" href="{{ url('product/'.$slug.'') }}">{{ Helpers::productTitle($productName) }}</a>
            <x-product::rating 
                star="{{ $rating }}"
                review="{{ $review }}"
            />
            <div class="price-info">
                <strong class="font-md-bold color-brand-3 price-main">{{ "Rp. ". number_format($priceAfterDisc,0,'.','.') }}</strong>
                @if($discPercentage > 0)
                <br/>
                <span class="label-danger">{{ $discPercentage }}%</span>
                <span class="color-gray-500 price-line">{{ "Rp. ". number_format($sellingPrice,0,'.','.') }}</span>
                @endif
                <br/>
                <div class="pt-5"></div>
                <span class="color-gray-500 font-xs">{{ $merchant }} - Terjual {{ $countSold }}</span>
            </div>
        </div>
    </div>
</div>