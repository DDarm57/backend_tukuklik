<div class="rating">
    @for($i=1; $i<=$star; $i++)
        <img src="{{ url('frontend/assets/imgs/template/icons/star.svg') }}" alt="Ecom">
    @endfor
    @for($i=5; $i>= $star+1; $i--)
        <img src="{{ url('frontend/assets/imgs/template/icons/star-gray.svg') }}" alt="Ecom">
    @endfor
    <span class="font-xs color-gray-500"> ({{ $review }})</span>
</div>