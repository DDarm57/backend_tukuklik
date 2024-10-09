<script>

    var quantity = document.querySelectorAll("#quantity");
    var basePrice = document.querySelectorAll(".basePrice");
    var subTotalTemp = document.querySelectorAll("#subTotalTemp");
    var subTotal = document.querySelectorAll("#subTotal");
    var inputPph = document.querySelectorAll("#inputpph");
    var inputPpn = document.querySelectorAll("#inputPpn");
    var pph = document.querySelectorAll("#pph");
    var ppn = document.querySelectorAll("#taxAmount");

    inputPph.forEach((item, index) => {
        inputPph[index].addEventListener('keyup', function() {
            var valPph =  subTotalTemp[index].value * ((this.value/100) * -1);
            var showPph = valPph * -1;
            pph[index].innerHTML = "(" + formatRupiah(showPph.toFixed(2).replace(/\./g,',').toString(), "Rp") + ")";
            calculateGrandTotal();
        });
    });

    inputPpn.forEach((item, index) => {
        inputPpn[index].addEventListener('keyup', function() {
            var valPpn = subTotalTemp[index].value * (this.value/100);
            ppn[index].innerHTML = formatRupiah(valPpn.toFixed(2).replace(/\./g,',').toString(), "Rp");
            calculateGrandTotal();
        });
    });

    basePrice.forEach((item, index) => {
        basePrice[index].addEventListener('keyup', function() {
            
            var valSubTotal = quantity[index].innerText * this.value.replace(/\./g,'');
            subTotalTemp[index].value = valSubTotal; 
            subTotal[index].innerHTML = formatRupiah(valSubTotal.toString(), "Rp");
            
            var valPpn = subTotalTemp[index].value * (inputPpn[index].value/100);
            ppn[index].innerHTML = formatRupiah(valPpn.toFixed(2).replace(/\./g,',').toString(), "Rp");

            var valPph =  subTotalTemp[index].value * (inputPph[index].value/100);
            pph[index].innerHTML = "(" + formatRupiah(valPph.toFixed(2).replace(/\./g,',').toString(), "Rp") + ")";
            calculateGrandTotal();
            
        });
    });

    $("[name=shipping_fee]").keyup(function() {
        $("#shippingAmount").html(formatRupiah(this.value.replace(/\./g,''),'Rp'));
        calculateGrandTotal();
    });

    function calculateGrandTotal(){
        var subTotals = 0;
        var totalPph = 0;
        var totalPpn = 0;
        inputPph.forEach((item, index) => {
            totalPph =  parseFloat(totalPph) + parseFloat(subTotalTemp[index].value) * ((item.value/100) * -1);
        });
        inputPpn.forEach((item, index) => {
            totalPpn = parseFloat(totalPpn) + parseFloat(subTotalTemp[index].value) * parseFloat((item.value/100));
        });
        subTotalTemp.forEach((item, index) => {
            subTotals  = parseFloat(subTotals) + parseFloat(item.value);
        });
        var shippingAmount = document.querySelector("[name=shipping_fee]").value.replace(/\./g,'');
        var formatPph =  "(" + formatRupiah(totalPph.toFixed(2).replace(/\./g,',').toString(), "Rp") + ")";
        var grandTotal = parseFloat(subTotals) + parseFloat(totalPpn) - parseFloat((totalPph * -1)) + parseFloat(shippingAmount);
        $("#totalPph").html(formatPph);
        $("#totalPpn").html(formatRupiah(totalPpn.toFixed(2).replace(/\./g,',').toString(), 'Rp'));
        $("#totalSubTotal").html(formatRupiah(subTotals.toFixed(2).replace(/\./g,',').toString(), 'Rp'));
        $("#grandTotal").html(formatRupiah(grandTotal.toFixed(2).replace(/\./g,',').toString(), 'Rp'));
    }

    $("#saveRFQ").on('click', function() {
        $(".submit").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
        $("[name=notes_for_merchant]").val(CKEDITOR.instances['notesMerchant'].getData());
        setTimeout(() => {
              $.ajax({
                url : "{{ url('dashboard/request-for-quotation-form') }}",
                type : 'POST',
                dataType : 'json',
                contentType: false,
                processData: false,
                data : new FormData($("form#formRFQ")[0]),
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                success:function(res) {
                    $(".submit").attr('disabled', false).html('Save');
                    if(res.status == "success") {
                        window.location.href="/dashboard/request-for-quotation";
                    }
                }, 
                error:function(jqXHR){
                    $(".submit").attr('disabled', false).html('Save')
                    var json = jqXHR.responseJSON;
                    if(typeof json.errors !== 'undefined') {
                        printError(json.errors);
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    } else {
                        swal(json.message, {
                            icon: "error",
                        });
                    }
                }
            })
        }, 1000);
    });

    $("[name=payment_type]").change(function() {
        $("#termin").css('display', 'none');
        if($(this).val() == 'Term Of Payment') {
            $("#termin").css('display', '');
        }
    });

    var changeAddress = document.querySelectorAll("#changeAddress");
    changeAddress.forEach((item, index) => {
        changeAddress[index].addEventListener('click', function() {
            $("#changeAddressModal .modal-body .row").html("");
            var type = this.getAttribute('data-value');
            $.ajax({
                url : "{{ url('dashboard/address') }}",
                type : 'GET',
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                success:function(res) {
                    var html = "";
                    res.data.forEach((item, index) => {
                        var checked = $("[name=customer_address_id]").val() == item.id ? 'checked' : '';
                        var isDefault = item.is_default == 1 ? '(Alamat Utama)' : '';
                        html +=     '<div class="col-lg-12">'+
                                        '<div class="card">'+
                                            '<div class="card-body">'+
                                                '<div class="row mb-1">'+
                                                    '<div class="col-sm-12">'+
                                                        '<label for="inputText" class="label-text col-sm-6 col-form-label mt-2">'+item.address_name+' </label>'+
                                                        '<input name="address" value="'+item.id+'" type="radio" '+checked+'>'+
                                                        '<input id="address'+item.id+'" value="'+item.full_addr+'" type="hidden">'+
                                                        '<label class="col-form-label">'+item.full_addr+'</label>'+
                                                        '<span class="text-secondary text-sm">'+isDefault+'</span>'+
                                                    '</div>'+
                                                '</div>'+
                                            '</div>'+
                                        '</div>'+
                                    '</div>';
                    });
                    $("#changeAddressModal .modal-body .row").html(html);
                    $("#changeAddressModal").modal('show');
                    $("#saveChangeAddress").attr('data-value', type);
                }
            })
        });
    });

    $("#saveChangeAddress").click(function() {
        var type = $(this).attr('data-value');
        var addressId = $("[name=address]:checked").val();
        if(type == 'shippingAddress') {
            $("[name=shipping_address]").val($("#address"+addressId).val());
            $("[name=customer_address_id]").val(addressId);
        }else {
            $("[name=billing_address]").val($("#address"+addressId).val());
            $("[name=billing_address_id]").val(addressId);
        }
        $("#changeAddressModal").modal('hide');
    });

    function addAddress() {
        var addAddress = document.querySelectorAll("#addAddress");
        addAddress.forEach((item, index) => {
            addAddress[index].addEventListener('click', function() {
                var type = this.getAttribute('data-value');
                $("#province").html('<option></option>');
                $("#city").html('<option></option>');
                $("#district").html('<option></option>');
                $("#subdistrict").html('<option></option>');
                $.ajax({
                    url : "{{ url('dashboard/province') }}",
                    type : 'GET',
                    headers : {
                        'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                    },
                    success:function(res) {
                        var province = "<option></option>";
                        res.data.forEach((item, index) => {
                            province += `<option value='${item.prov_id}'>${item.prov_name}</option>`;
                        });
                        $("#province").html(province);
                    }
                });
                $("#addAddressModal").modal('show');
                $("#saveAddAddress").attr('data-value', type);
            });
        });

        $("#province").on('change', function() {
            $("#district").html("<option></option>");
            $("#subdistrict").html("<option></option>");
            $.ajax({
                url : `{{ url('dashboard/city') }}/${$(this).val()}`,
                type : 'GET',
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                success:function(res) {
                    var city = "<option></option>";
                    res.data.forEach((item, index) => {
                        city += `<option value='${item.city_id}'>${item.city_name}</option>`;
                    });
                    $("#city").html(city);
                }
            });
        });

        $("#city").on('change', function() {
            $("#subdistrict").html("<option></option>");
            $.ajax({
                url : `{{ url('dashboard/district') }}/${$(this).val()}`,
                type : 'GET',
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                success:function(res) {
                    var district = "<option></option>";
                    res.data.forEach((item, index) => {
                        district += `<option value='${item.dis_id}'>${item.dis_name}</option>`;
                    });
                    $("#district").html(district);
                }
            });
        });

        $("#district").on('change', function() {
            $.ajax({
                url : `{{ url('dashboard/subdistrict') }}/${$(this).val()}`,
                type : 'GET',
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                success:function(res) {
                    var subdistrict = "<option></option>";
                    res.data.forEach((item, index) => {
                        subdistrict += `<option value='${item.subdis_id}'>${item.subdis_name}</option>`;
                    });
                    $("#subdistrict").html(subdistrict);
                }
            });
        });

        $("#saveAddAddress").click(function() {
            $("#saveAddAddress").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
            var type = $("#saveAddAddress").attr('data-value');
            $.ajax({
                url : "{{ url('dashboard/address') }}",
                type : 'POST',
                dataType : 'JSON',
                contentType: false,
                processData: false,
                headers : {
                    'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
                },
                data : new FormData($("#saveAddrForm")[0]),
                success:function(res) {
                    if(type == 'shippingAddress') {
                        $("[name=shipping_address]").val(res.data.full_address);
                        $("[name=customer_address_id]").val(res.data.id);
                    }else {
                        $("[name=billing_address]").val(res.data.full_address);
                        $("[name=billing_address_id]").val(res.data.id);
                    }
                    $("#addAddressModal").modal('hide');
                    addAddress.forEach((item, index) => {
                        addAddress[index].style.display = 'none';
                        changeAddress[index].style.display = '';
                    });
                },
                error:function(jqXHR){
                    $("#saveAddAddress").attr('disabled', false).html('<i class="bi bi-save"></i> Simpan')
                    var json = jqXHR.responseJSON;
                    //if(typeof json.status !== 'undefined') {
                    printError(json.errors);
                    //}
                }
            })
        });

        $(".selects2").select2({
            placeholder: 'Select an option',
            theme: "bootstrap",
            dropdownParent: $("#addAddressModal"),
            width : '100%',
        });
    }

    addAddress();

    $("#addAnotherAddress").click(function() {
        var type = $("#saveChangeAddress").attr('data-value');
        $("button#addAddress").attr('data-value', type).trigger('click');
        $("#changeAddressModal").modal('hide');
    });

</script>