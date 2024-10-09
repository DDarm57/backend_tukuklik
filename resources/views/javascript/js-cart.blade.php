<script>
    
    adjustQty = (cartId, type) => {

        var qty = $(".qty").val();
        if(type == "plus") {
            var newQty = parseFloat(qty) + 1;
        } else{
            var newQty = parseFloat(qty) - 1;
        }

        $.ajax({
            url : "{{ url('cart/adjust-qty') }}/"+cartId+"",
            type : 'POST',
            headers : {
                'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
            },
            data : {
                qty : newQty
            },
            success:function(res) {
                $("#subTotal"+res.data.id).html(formatRupiah(res.data.subtotal.toString(), "Rp. "));
                $("#subTotal").html(formatRupiah(res.subtotal.toString(), "Rp. "));
                $("#tax").html(formatRupiah(res.tax_amount.toString(), "Rp. "));
                $("#grandTotal").html(formatRupiah(res.grand_total.toString(), "Rp. "));
                $(".qty").val(newQty);
            },
            error:function(jqXHR) {
                swal(jqXHR.responseJSON.message, {
                    icon: "error",
                });
            }
        })
    }

    deleteCart = (cartId) => {
        swal({
            title: "Apakah anda yakin?",
            text: "Keranjang akan segera dihapus",
            icon: "warning",
            buttons: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
        })
        .then((willDelete) => {
            if (willDelete) {
                $.ajax({
                    url : `{{ url('cart/${cartId}') }}`,
                    type : 'DELETE',
                    dataType: 'JSON',
                    data : { 
                        _token : $("meta[name='csrf_token']").attr('content')
                    },
                    success:function(res) {
                        window.location.reload();
                    },
                    error:function(jqXHR){
                        swal(jqXHR.responseJSON.message, {
                            icon: "error",
                        });
                    }
                })
            }
        });
    }

</script>