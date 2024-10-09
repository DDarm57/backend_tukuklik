<?php

namespace App\View\Components\Product;

use Illuminate\View\Component;

class CartProduct extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct
    (
        public $productName,
        public $discPercentage,
        public $thumbnail,
        public $minimum,
        public $unit,
        public $priceAfterDisc,
        public $sellingPrice,
        public $merchant,
        public $slug,
        public $rating,
        public $review,
        public $stockType,
        public $countSold
    )
    {}

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.product.cart-product');
    }
}
