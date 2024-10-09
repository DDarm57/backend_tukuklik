<script>
    $("#searchSomething").keyup(function() {
        if($(this).val().length >= 2){

            advancedSearch($(this).val());
            var value = $(this).val();
            var srcProduct = "{{ url('products') }}?search="+value+"";
            var srcCategory = "{{ url('products') }}/"+value+"/category";
            var srcTag = "{{ url('products') }}/"+value+"/tag";

            if($("#optionSearch").val() == "all"){
                $(".form-search").attr('action', srcProduct);
            }
            else if($("#optionSearch").val() == "byProduct"){
                $(".form-search").attr('action', srcProduct);
            }
            else if($("#optionSearch").val() == "byCategory"){
                $(".form-search").attr('action', srcCategory);
            }
            else if($("#optionSearch").val() == "byTag"){
                $(".form-search").attr('action', srcTag);
            }
        }else {
            $("#cardSearch").hide();
        }
    });

    $("#optionSearch").change(function() {
        if($("#searchSomething").val().length >= 2) {
            advancedSearch($("#searchSomething").val());
        }
    })

    function advancedSearch(value){
        $("#cardSearchBody").html("");
        $("#cardSearch").show();
        $("#cardSearch .loading").show();
        setTimeout(() => {
            var option = $("#optionSearch").val();
            $.ajax({
                url : "{{ url('search') }}?option=" + option + "&keyword=" + value,
                type : 'GET',
                dataType : 'json',
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name=csrf_token]").attr('content')
                },
                success:function(res) {
                    console.log(res);
                    $("#cardSearch .loading").hide();
                    var htmlProduct = "";
                    var htmlCategory = "";
                    var htmlTag = "";
                    var htmlMerchant = "";

                    htmlProduct +=  '<h6 class="mt-10 color-brand-4">Hasil Pencarian Dari Produk</h6>';
                    htmlCategory += '<h6 class="mt-10 color-brand-4">Hasil Pencarian Dari Kategori</h6>';
                    htmlTag += '<h6 class="mt-10 color-brand-4">Hasil Pencarian Dari Tag</h6>';
                    htmlMerchant += '<h6 class="mt-10 color-brand-4">Hasil Pencarian Dari Penjual</h6>';
                    
                    if(typeof res.product != "undefined") {
                        res.product.forEach((item, index) => {
                        console.log(item);
                        htmlProduct +=   '<div class="row mt-10">'+
                                                '<div class="col-lg-2">'+
                                                    '<div class="image-box">'+
                                                        '<a href="{{ url("product") }}/'+item.slug+'">'+
                                                            '<img style="height:50px;" src="'+item.thumbnail+'">'+
                                                        '</a>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-lg-10 mt-1">'+
                                                    '<a href="{{ url("product") }}/'+item.slug+'">'+
                                                    '<span class="font-xs color-gray-500">'+item.merchant.name+'</span>'+
                                                    '<br>'+
                                                    '<span class="color-brand-3 font-xs-bold">'+
                                                        item.product_name+
                                                    '</span>'+
                                                    '</a>'+
                                                '</div>'+
                                            '</div>';
                        });
                        if(res.product.length == 0){
                            htmlProduct += "<p class='mt-5'>Data tidak ditemukan</p>";
                        }
                    }

                    if(typeof res.category != "undefined") {
                        res.category.forEach((item, index) => {
                        htmlCategory +=   '<div class="row">'+
                                                '<div class="col-lg-12 mt-10">'+
                                                    '<a href="{{ url("products") }}/'+item.slug+'/category">'+
                                                    '<span class="font-xs color-gray-500">'+item.products_count+' Produk Ditemukan</span>'+
                                                    '<br>'+
                                                    '<span class="color-brand-3 font-xs-bold">'+
                                                        item.name+
                                                    '</span>'+
                                                    '</a>'+
                                                '</div>'+
                                            '</div>';
                        });
                        if(res.category.length == 0){
                            htmlCategory += "<p class='mt-5'>Data tidak ditemukan</p>";
                        }
                    }

                    if(typeof res.tag != "undefined") {
                        res.tag.forEach((item, index) => {
                            htmlTag +=  '<a class="btn btn-border mr-5 mt-10" href="{{ url("products") }}/'+item.name+'/tag">'+item.name+'</a>';
                        });
                        if(res.tag.length == 0){
                            htmlTag += "<p class='mt-5'>Data tidak ditemukan</p>";
                        }
                    }

                    if(typeof res.merchant != "undefined") {
                        res.merchant.forEach((item, index) => {
                        var pkp = item.is_pkp == "Y" ? "Penjual Kena Pajak" : "Penjual Tidak Kena Pajak";
                        htmlMerchant +=   '<div class="row mt-15">'+
                                                '<div class="col-lg-2">'+
                                                    '<div class="image-box">'+
                                                        '<a href="{{ url("merchant") }}/'+item.id+'">'+
                                                            '<img style="height:50px;" src="{{ url("storage") }}/'+item.photo+'">'+
                                                        '</a>'+
                                                    '</div>'+
                                                '</div>'+
                                                '<div class="col-lg-10 mt-1">'+
                                                    '<a href="{{ url("merchant") }}/'+item.id+'">'+
                                                    '<span class="font-xs color-gray-500">'+pkp+'</span>'+
                                                    '<br>'+
                                                    '<span class="color-brand-3 font-xs-bold">'+
                                                        item.name+
                                                    '</span>'+
                                                    '</a>'+
                                                '</div>'+
                                            '</div>';
                        });
                        if(res.merchant.length == 0){
                            htmlMerchant += "<p class='mt-5'>Data tidak ditemukan</p>";
                        }
                    }

                    var srcProduct = "{{ url('products') }}?search="+value+"";
                    var srcCategory = "{{ url('products') }}/"+value+"/category";
                    var srcTag = "{{ url('products') }}/"+value+"/tag";

                    if($("#optionSearch").val() == "all"){
                        $("#cardSearchBody").html(htmlProduct+htmlCategory+htmlTag+htmlMerchant);
                        $(".form-search").attr('action', srcProduct);
                    }
                    else if($("#optionSearch").val() == "byProduct"){
                        $("#cardSearchBody").html(htmlProduct);
                        $(".form-search").attr('action', srcProduct);
                    }
                    else if($("#optionSearch").val() == "byCategory"){
                        $("#cardSearchBody").html(htmlCategory);
                        $(".form-search").attr('action', srcCategory);
                    }
                    else if($("#optionSearch").val() == "byTag"){
                        $("#cardSearchBody").html(htmlTag);
                        $(".form-search").attr('action', srcTag);
                    }
                    else if($("#optionSearch").val() == "byMerchant"){
                        $("#cardSearchBody").html(htmlMerchant);
                    }
                }
            })
        }, 1000);

        $(".form-search").on('submit', function(e) {
            e.preventDefault();
            window.location.href = $(this).attr("action");
        })
    }
</script>