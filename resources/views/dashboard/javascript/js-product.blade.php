<script src="{{ url('admin/assets/vendor/ckeditor2/ckeditor.js') }}"></script>
<script src="https://unpkg.com/@yaireo/tagify@4.17.7/dist/tagify.min.js"></script>
<script src="https://unpkg.com/@yaireo/tagify@3.1.0/dist/tagify.polyfills.min.js"></script>
<script>

var description = document.getElementById("description");
CKEDITOR.replace(description,{
    language:'en-gb'
});
CKEDITOR.config.allowedContent = true;
CKEDITOR.config.width = "100%";
var input = document.querySelector('input[name=tags]');
new Tagify(input);

function removeImage(path) {
    $.ajax({
        url : `{{ url('dashboard/media') }}`,
        type : 'DELETE',
        dataType : 'JSON',
        headers : {
            'X-CSRF-TOKEN' : $('meta[name="csrf_token"]').attr('content'),
        },
        data : {
            path : path,
        },
        success:function(res) {
            $("#media_"+res.id).remove();
        }, 
        error:function(jqXHR) {
            $('#message').html("<p class='text-danger'>"+jqXHR.responseJSON+"</p>");
        }
    })
}

$('.remove-btn').click(function(){
    removeImage($(this).parent('.wrapper-thumb').find('img').attr('src'));
    $(this).parent('.wrapper-thumb').remove();
});

(function() {
    function Init() {
        var fileSelect = document.getElementById('file-upload'),
            fileDrag = document.getElementById('file-drag'),
            submitButton = document.getElementById('submit-button');

        fileSelect.addEventListener('change', fileSelectHandler, false);
        fileDrag.addEventListener('dragover', fileDragHover, false);
        fileDrag.addEventListener('dragleave', fileDragHover, false);
        fileDrag.addEventListener('drop', fileSelectHandler, false);
    }

    function fileDragHover(e) {
        var fileDrag = document.getElementById('file-drag');
        e.stopPropagation();
        e.preventDefault();
        fileDrag.className = (e.type === 'dragover' ? 'hover' : 'modal-body file-upload');
    }

    function fileSelectHandler(e) {
        // Fetch FileList object
        var files = e.target.files || e.dataTransfer.files;

        // Cancel event and hover styling
        fileDragHover(e);

        // Process all File objects
        for (var i = 0, f; f = files[i]; i++) {
            //parseFile(f);
            uploadFile(f);
        }
    }

    const bytesToSize = function(bytes, decimals = 2) {
        if (bytes === 0) return '0 Bytes';
        var k = 1024;
        var dm = decimals < 0 ? 0 : decimals;
        var sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
        var i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + ' ' + sizes[i];
    }

    function createPreview(path) {
        imgPreview = document.getElementById('img-preview');
        imgPreview.classList.remove('img-thumbs-hidden');
        wrapper = document.createElement('div');
        wrapper.classList.add('wrapper-thumb');
        removeBtn = document.createElement("a");
        nodeRemove= document.createTextNode('x');
        removeBtn.classList.add('remove-btn');
        removeBtn.appendChild(nodeRemove);
        img = document.createElement('img');
        img.src = path;
        img.classList.add('img-preview-thumb');
        wrapper.appendChild(img);
        wrapper.appendChild(removeBtn);
        imgPreview.appendChild(wrapper);
        $('.remove-btn').click(function(){
            removeImage(path);
            $(this).parent('.wrapper-thumb').remove();
        });
    }

    function uploadFile(file) {
        var data = new FormData();
        data.append('file', file);
        data.append('path', 'products');
        data.append('media_type', 'product_image');
        $('.progress').show();
        $('#message').html('');
        $.ajax({
            xhr : function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener('progress', function(e) {
                    var bytesLoaded = e.loaded;
                    var sizeFiles = e.total;
                    var percent = Math.round((bytesLoaded / sizeFiles) * 100);
                    $('#progressBar').attr('aria-valuenow', percent).css('width', percent + '%').text(percent + '%');
                    $('.info').html(bytesToSize(bytesLoaded)+" dari "+bytesToSize(sizeFiles));
                });
                return xhr;
            },
            type : 'POST',
            url : "{{ url('dashboard/media') }}",
            dataType : 'JSON',
            data : data,
            contentType: false,
            processData: false,
            headers : {
                'X-CSRF-TOKEN' : $('meta[name="csrf_token"]').attr('content'),
            },
            success:function(res) {
                $('.progress').hide();
                createPreview(res.data.path);
                var input = "<input id='media_"+res.data.id+"' type='hidden' name='media[]' value='"+res.data.id+"'>";
                $("#outputFile").append(input);
            },
            error:function(jqXHR) {
                $('.progress').hide();
                $('#message').html("<p class='text-danger'>"+jqXHR.responseJSON.message+"</p>");
            }
        });
    }

    if (window.File && window.FileList && window.FileReader) {
        Init();
    } else {
        document.getElementById('file-drag').style.display = 'none';
    }
})();    

$("input[name='selling_price']").on('keyup', function() {
    $("#tableVarian").css('display') == 'none' ? '' : $("input[name='harga_varian[]']").val(formatRupiah(this.value)).attr('readonly', true);
});

$("input[name='has_wholesale']").on('change', function() {
    $("#wholesale").css('display', 'none');
    $("#buttonWholesale").css('display', 'none');
    if(this.checked) {
        $("#wholesale").css('display', '');
        $("#buttonWholesale").css('display', '');
    }
    modifyInputVarian();
});

var counter = 1;
$("#addWholesale").on('click', function() {
    counterNext = counter+1;
    var wholesale = '<div class="row attr'+counterNext+'">'+
                        '<div class="form-group col-lg-4">'+
                            '<label for="inputText" class="col-form-label">Jumlah Min.</label>'+
                            '<div class="input-group mb-3">'+
                                '<span class="input-group-text" id="basic-addon2">Pcs</span>'+
                                '<input type="text" name="min_wholesale_qty[]" value="0" class="form-control">'+
                            '</div>'+
                        '</div>'+
                        '<div class="form-group col-lg-6">'+
                            '<label for="inputText" class="col-form-label">Harga</label>'+
                            '<div class="input-group mb-3">'+ 
                                '<span class="input-group-text" id="basic-addon2">Rp</span>'+
                                '<input id="rupiah" type="text" name="wholesale_price[]" value="0" class="form-control">'+ 
                            '</div>'+
                        '</div>'+
                        '<div class="form-group col-lg-2" style="margin-top:35px">'+
                            '<a class="btn btn-outline-danger" onclick="deleteWholesale('+counterNext+')"><i class="bi bi-patch-minus"></i></a>'+
                        '</div>'+
                    '</div>';
    counter++;
    $("#wholesale").append(wholesale);
    setRupiah();
});

deleteWholesale = (i) => {
    $(".attr"+i).remove();
}

$("input[name='tags']").on('keyup', function(e){
   $("#tag").val($(this).val());
});

$(".cat").on('change', function() {
    if($(this).attr('id') == "catFirst"){
        $("#catSecond").html('');
        $("#catThird").html('');
    }
    else if($(this).attr('id') == "catSecond"){
        $("#catThird").html('');
    }
    $.ajax({
        url : `{{ url('dashboard/category/child') }}/${$(this).val()}`,
        type : 'GET',
        dataType : 'JSON',
        data : {
            _token : $("meta[name='csrf_token']").attr('content')
        },
        success:function(res) {
            var option = '<option></option>';
            res.forEach((item, index) => {
                option += '<option value="'+item.id+'">'+item.name+'</option>';
            });
            if(res.length > 0){
                res[0].depth_level == 2 ? $("#catSecond").html(option) : $("#catThird").html(option);
            }
        }
    });
});

var varian = [];
var counterVarian = 0;
var allVarian = [];
var varianOne = [];
var varianTwo = [];
var varianThree = [];
var varianFour = [];

$("#tipeVarian").change(function() {
    if(varian.includes($(this).val())) {
        swal({
            icon: 'error',
            title: 'Oops...',
            text: 'Varian sudah anda pilih!',
        })
        return false;
    }
    var counterNext = counterVarian + 1;
    varian.push($(this).val());
    var intVarian = $(this).val();
    $.ajax({
        url : `{{ url('dashboard/attribute/values') }}/${$(this).val()}`,
        type : 'GET',
        dataType : 'JSON',
        headers : {
            'X-CSRF-TOKEN' : $("meta[name='csrf_token']").val()
        },
        success:function(res) {
            var html = '<div class="row rowVarian'+counterNext+'">'+
                            '<div class="form-group col-lg-4">'+
                                '<label for="inputText" class="col-form-label">Tipe</label><br>'+
                                '<input type="text" class="form-control" value="'+res[0].attribute.name+'" readonly>'+
                            '</div>'+
                            '<div class="form-group col-lg-6">'+
                                '<label for="inputText" class="col-form-label">Attribute</label><br>'+
                                '<select multiple class="selects form-control" id="attributeVarian'+counterNext+'" name="attribute[]">'+
                                    '<option></option>'+
                                '</select>'+
                            '</div>'+
                            '<div class="form-group col-lg-2" style="margin-top:35px">'+
                                '<a class="btn btn-outline-danger" onclick="deleteVarian('+counterNext+','+intVarian+')"><i class="bi bi-patch-minus"></i></a>'+
                            '</div>'+
                        '</div>';
            $("#selectVarian").append(html);
            var optionVarian = '';
            res.forEach((item, index) => {
                optionVarian += `<option>${item.value}</option>`
            });
            if(!allVarian.includes(res[0].attribute.name)) {
                allVarian.push({
                    tipe : res[0].attribute.name,
                    data : []
                });
            }
            $("#attributeVarian"+counterNext).html(optionVarian);
            $("#selectVarian").css('display','');
            $("#attributeVarian"+counterNext).select2({
                placeholder: 'Select an option',
                theme: "bootstrap"
            });;
            $("#attributeVarian"+counterNext).change(function() {
                var thead = "";
                allVarian.forEach((item, index) => {
                    $("#"+index).remove();
                    thead += `<th id="${index}">${item.tipe}</th>`;
                });
                $("#tableVarian table thead tr").prepend(thead);
                var val = $(this).val();
                console.log(counterNext);
                allVarian[counterNext-1].data = val;
                $("#tableVarian").css('display', '');
                $("#tableVarian table tbody").html('');
                switch(counterNext-1) {
                    case 0 : 
                        varianOne = allVarian[0].data;
                    break;
                    case 1 : 
                        varianTwo = allVarian[1].data;
                    break;
                    case 2 : 
                        varianThree = allVarian[2].data;
                    break;
                    case 3 : 
                        varianFour = allVarian[3].data;
                    break;
                }
                var options = [];
                for(var i=0;i<varianOne.length;i++)
                {
                    if(varianTwo.length == 0){
                        var item = []
                        item.push({name:varianOne[i]});
                        options.push({options:item});
                    } else {
                        for(var j = 0;j<varianTwo.length;j++)
                        {
                            if(varianThree.length == 0){
                                var item = [];
                                item.push({name:varianOne[i]},{name:varianTwo[j]});
                                options.push({options:item});   
                            } else {
                                for(var x = 0; x < varianThree.length; x++){
                                    if(varianFour.length == 0){
                                        var item = [];
                                        item.push({name:varianOne[i]},{name:varianTwo[j]}, {name:varianThree[x]});
                                        options.push({options:item});   
                                    } else {
                                        for(y = 0; y < varianFour.length; y++) {
                                            var item = [];
                                            item.push({name:varianOne[i]},{name:varianTwo[j]},{name:varianThree[x]}, {name:varianFour[y]});
                                            options.push({options:item});   
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                var tbody = "";
                options.forEach((item, index) => {
                    var td = "";
                    var tdArr = [];
                    item.options.forEach((item2, index2) => {
                        td += "<td>"+item2.name+"</td>"
                        tdArr.push(item2.name);
                    });
                    td += "<td><input name='sku_varian[]' class='form-control' type='text'></td>";
                    // td += "<td><input name='gambarVarian[]' class='form-control' type='file'></td>";
                    td += "<td><input name='stok_varian[]' class='form-control' type='text' value='0'></td>";
                    td += "<td><input name='harga_varian[]' id='rupiah' class='form-control' type='text' value='0'></td>";
                    td += "<td style='display:none'><input name='varian_attribute[]' type='hidden' value='"+tdArr.join(',')+"'></td>";
                    var td = "<tr>"+td+"</tr>";
                    tbody += td;
                })
                console.log(tbody);
                $("#tableVarian table tbody").html('');
                $("#tableVarian table tbody").html(tbody);
                setRupiah();
            });
        }
    });
    counterVarian++;
    modifyInputVarian();
});

deleteVarian = (i, varianId) => {
    $(".rowVarian"+i).remove();
    allVarian.splice(i-1, 1);
    $(`th[id=${i}]`).remove();
    $("#tableVarian").css('display', 'none');
    counterVarian = 0;
    modifyInputVarian();
    var indexVarian = varian.indexOf(varianId.toString());
    varian.splice(indexVarian, 1);
}

function modifyInputVarian(){
    $("#infoVarianWholesale").css('display', 'none');
    $("input[name='selling_price']").attr('readonly', false);
    $("input[name='product_stock']").attr('readonly', false);
    $("input[name='product_sku']").attr('readonly', false);
    var oldProdType = $("input[name='product_type']").val();
    $("input[name='product_type']").val('product');
    if(varian.length > 0 ||  oldProdType == "varian") {
        $("input[name='selling_price']").attr('readonly', true).val(0);
        $("input[name='product_stock']").attr('readonly', true).val(0);
        $("input[name='product_sku']").attr('readonly', true).val('');
        $("input[name='product_type']").val('varian');
    }
    if($("#tableVarian").css('display') != 'none') {
        $("input[name='selling_price']").attr('readonly', true);
        if($("input[name='has_wholesale']").is(':checked')) {
            $("input[name='selling_price']").attr('readonly', false);
            $("input[name='harga_varian[]']").val(formatRupiah($("input[name='selling_price']").val())).attr('readonly', true);
            $("#infoVarianWholesale").css('display', '');
        }
    }
}

function saveForm(id = null) {
    $("[name=description]").val(CKEDITOR.instances['description'].getData());
    var form = $('#my-form')[0];
    var data = new FormData(form);
    $(".submit").attr('disabled', true).html("<img src='{{ url('images/loading2.gif') }}' style='height:20px'> Loading...");
    var url, method;
    if(id != null){
        url = `{{ url('dashboard/product') }}/${id}`;
    }else {
        url = "{{ url('dashboard/product') }}";
    }
    setTimeout(() => {
        $.ajax({
            url : url,
            method : 'POST',
            dataType : 'JSON',
            contentType: false,
            processData: false,
            data : data,
            headers : {
                'X-CSRF-TOKEN' : $("meta[name='csrf_token']").attr('content')
            },
            success:function(res) {
                $(".submit").attr('disabled', false).html('Save');
                if(res.status == "success") {
                    window.location.href="/dashboard/product";
                }
            }, 
            error:function(jqXHR){
                $(".submit").attr('disabled', false).html('Save')
                var json = jqXHR.responseJSON;
                if(typeof json.status !== 'undefined') {
                    printError(json.errors);
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                }
            }
        })
    }, 1000);
}

</script>