<script>
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

function save() {
    $(".submit").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");


    var url = $("[name=_method]").val() == "PUT" ? "{{ route('merchant.update', $merchant->id??0) }}" : "{{ route('merchant.store') }}";

    $.ajax({
        url : url,
        type : "POST",
        dataType : "json",
        contentType: false,
        processData: false,
        data : new FormData($("#form")[0]),
        headers : {
            'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
        },
        success:function(res) {
            $(".submit").attr('disabled', false).html('<i class="bi bi-save"></i> Simpan');
            if(res.status == "success") {
                window.location.href="/dashboard/merchant";
            }
        }, 
        error:function(jqXHR){
            $(".submit").attr('disabled', false).html('<i class="bi bi-save"></i> Simpan')
            var json = jqXHR.responseJSON;
            if(typeof json.status !== 'undefined') {
                printError(json.errors);
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }
        }
    });
}
</script>