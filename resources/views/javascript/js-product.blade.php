<script>
    function rating(star, review) {
        var starYellow = "";
        var starGray = "";
        for(i = 1; i<=star; i++){
            starYellow += '<img src="{{ url("frontend/assets/imgs/template/icons/star.svg") }}" alt="Ecom">';
        }
        for(x = 5; x>=star+1; x--){
            starYellow += '<img src="{{ url("frontend/assets/imgs/template/icons/star-gray.svg") }}" alt="Ecom">';
        }
        var html = '<div class="rating">'+
                        starYellow+
                        starGray+
                        `<span class="font-xs color-gray-500"> (${review})</span>`+
                    '</div>';
        return html
    }

    var setProduct = false;

    callProducts = (page = 1) => {

        $('.loading').css('display','block');  
        setProduct = true;
        $(".seeMore").css('display','none');

        setTimeout(() => {

            var filtering = {};
            filtering.min_price = $(".min-value").val();
            filtering.max_price = $(".max-value").val();
            filtering.search = "{{ request()->get('search') ?? "" }}";
            filtering.slug = "{{ $slug ?? "" }}";
            filtering.type = "{{ $type ?? "" }}";
            var stockType = document.querySelectorAll('[type=checkbox]');
            if(typeof stockType != "undefined") {
                var stockArr = [];
                var taxStatusArr = [];
                stockType.forEach((item, index) => {
                    if(item.name == "stockType" && item.checked == true) {
                        stockArr.push(item.value);
                        filtering.stock_type = stockArr;
                    }
                    if(item.name == "taxStatus" && item.checked == true) {
                        taxStatusArr.push(item.value);
                        filtering.tax_status = taxStatusArr;
                    }
                })
            }

            console.log(filtering);

            $.ajax({
                url : "{{ url('products_all') }}?page="+page + "&" + $.param(filtering),
                method : 'GET',
                headers: {
                    'X-CSRF-TOKEN' : $("meta[name=csrf_token]").attr('content')
                },
                success:function(res) {
                    var data = res.data;
                    var html = "";

                    $(".page-product").html(`Menampilkan Hasil 1 - ${res.meta.to} Produk Dari Total ${res.meta.total} Produk`);
                    
                    if(data.length == 0 && page == 1){
                        $("#products").html('<h6 class="text-center">Produk Tidak Ditemukan</h6>');
                    }

                    data.forEach((item, index) => {

                        var priceBeforeDisc =  item.disc_percentage > 0 ?
                                                '<span class="color-gray-500 font-md price-line">Rp. '+item.price_before_disc+'</span>'
                                                :
                                                '';
                        
                        var showDiscPercent =  item.disc_percentage > 0 ? 
                                                '<span class="label bg-brand-2">-'+item.disc_percentage+'%</span>'
                                                :
                                                '';
                        var divTop = $("#modul").val() == "products" ? '<div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">' : '';
                        var divBot = $("#modul").val() == "products" ? '</div>' : '';

                        html += divTop +
                                '<div class="card-grid-style-3 hover-show">'+
                                    '<div class="card-grid-inner shadow-sm">'+
                                        '<div class="tools">'+
                                            '<a class="btn btn-wishlist btn-tooltip mb-10" href="shop-wishlist.html" aria-label="Tambah Wishlist"></a>'+
                                        '</div>'+
                                        '<div class="image-box">'+
                                            '<span class="label bg-color-danger">'+item.stock_type+'</span>'+
                                            '<a href="{{ url("product") }}/'+item.slug+'">'+
                                                '<img src="'+item.thumbnail+'" alt="Ecom">'+
                                            '</a>'+
                                        '</div>'+
                                        '<div class="info-right">'+
                                            '<span class="font-xs color-gray-500">Min Order '+item.minimum_order_qty+' '+item.unit+'</span>'+
                                            '<br>'+
                                            '<a class="color-brand-3 font-md-bold" href="{{ url("product") }}/'+item.slug+'">'+item.product_name+'</a>'+
                                            rating(item.rating, item.reviews_count) +
                                            '<div class="price-info">'+
                                                '<strong class="font-md-bold color-brand-3 price-main">Rp. '+item.price_after_disc+'</strong>'+
                                                priceBeforeDisc +
                                                '<br/>'+
                                                '<span class="color-gray-500 font-xs">'+item.merchant.name+'  - Terjual '+item.count_sold+'</span>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>'+
                                '</div>'+
                                divBot;
                    });

                    $("#products").append(html);
                    $('.loading').css('display','none');
                    $(".seeMore").css('display','');
                    setProduct = false;

                }
            });
        }, 2000);
    }

    callProducts();

    var page = 2;

    $(".seeMore").click(function() {
        callProducts(page);
        page++;
    });

    $(window).on('scroll', function(e) {
        var scrollTop = $(this).scrollTop();
        var windowHeight = $(document).height() - $(window).height();
        var calculate = scrollTop - windowHeight;
        console.log(windowHeight);
        if(
            calculate >= -650 && 
            calculate <= -600 &&
            $("#modul").val() != "products"
        ){
            callProducts(page);
            page++;
        }
        e.preventDefault();
    });
</script>