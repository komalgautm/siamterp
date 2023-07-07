var fullUrl = window.location.origin;

var FULLURL = "quotation";

function setcal(isCallables)
{
   console.log("setcal:"+isCallables);
    $("#isCallables").val(isCallables);
}

   function myFunction(th)
   {       

        
        $(".buttom_vl").text(" ")
        $("#sorted_table .unit_price").val("")
        $("#sorted_table .profit").val("")
        $('#unitcount'+th).prop('selectedIndex',0);

       
        setcal(0);
    }

///public_html/mobileandwebsitedevelopment.com/TERP/public/back-end/build
function sudhirComma(x) {
    return x.toString().replace(/\B(?<!\.\d*)(?=(\d{3})+(?!\d))/g, ",");
}

$('form').submit(function() {

    $(this).children('button[type=submit]').prop('disabled', true);

});



$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

$(document).ready(function() {

    $('.select2').select2();

});



$('.cancel').on('click', function() {

    const id = $(this).data('id');

    Swal.fire({

        title: "Cancel",
        text: "Do you want to cancel the Quotation ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        showLoaderOnConfirm: true,

        preConfirm: () => {

            return fetch(fullUrl + '/quotation/cancel?id=' + id)

                .then(response => response.json())

                .then(data => location.reload())

                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`)
                })

        }

    });

})



$('.approve').on('click', function() {

    const id = $(this).data('id');

    Swal.fire({

        title: "Approve",
        text: "Do you want to approve the Quotation?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#5ae87d",
        showLoaderOnConfirm: true,

        preConfirm: () => {

            return fetch(fullUrl + '/quotation/approve?id=' + id)

                .then(response => response.json())

                .then(data => location.reload())

                .catch(error => {
                    Swal.showValidationMessage(`Request failed: ${error}`)
                })

        }

    });

})



var itf = $.ajax({
    url: FULLURL + '/getITF',
    method: 'get',
    async: false,
    success: function(rs) {
        itf = rs
    }
}).responseText;

var unit = $.ajax({
    url: FULLURL + '/getUnit',
    method: 'get',
    async: false,
    success: function(data) {
        unit = data
    }
}).responseText;

$(document).on('click', '.add-row', function() {
    setcal(0)

    const currency = $('#currency').children("option:selected").text()

    var row = $('#sorted_table').find('tbody tr').last().data('id');

    var markup = '<tr id="row' + (row + 1) + '" data-id="' + (row + 1) + '"><td data-label="ITF"><select name="itf[]" id="itf' + (row + 1) + '" class="form-control select_itf select2" style="width:100%">' + itf + '</select></td><td data-label="Quantity"><input type="number" class="form-control qty" name="quantity[]" step="0.01" min="0" placeholder="Enter quantity" onkeydown="myFunction(' + (row + 1) + ')" value="" id="tqty' + (row + 1) + '"><input type="hidden" class="ean_id" name="ean_id[]" placeholder="ean_id"><input type="hidden" class="ean_qty" name="ean_qty[]" placeholder="ean_qty"><input type="hidden" class="net_weight" name="net_weight[]" placeholder="net_weight"><input type="hidden" class="new_weight" name="new_weight[]" placeholder="new_weight"><input type="hidden" class="maxcbm" name="maxcbm[]" placeholder="maxcbm"><input type="hidden" class="maxpallet" name="maxpallet[]" placeholder="maxpallet"> <input type="hidden" class="net_weight2" name="net_weight2[]" placeholder="net_weight2"><input type="hidden" class="ean_ppITF" name="ean_ppITF[]" placeholder="ean_ppITF"><input type="hidden" class="itfQty" name="itfQty[]" placeholder="itfQty"><input type="hidden" class="net_weightNew" name="net_weightNew[]" placeholder="net_weightNew"><input type="hidden" class="hpl_avg_weight" name="hpl_avg_weight[]" ></td><td data-label="Unit"><select name="unitcount[]" id="unitcount' + (row + 1) + '" class="form-control unitcount" style="width:100%">' + unit + '</select></td><td data-label="Number of Box"><input type="text" class="form-control number_box" name="number_box[]" value="" placeholder="" readonly></td><td data-label="NW"><input type="text" class="form-control nw" name="nw[]" value="" placeholder="" readonly><input type="hidden" class="form-control gw_weight" name="gw_weight[]" placeholder="gw_weight"><input type="hidden" class="form-control cbm" name="cbm[]" placeholder="cbm"><input type="hidden" class="form-control pallet" name="pallet[]" placeholder="pallet"><input type="hidden" class="form-control price_allocation" name="price_allocation[]" placeholder="price_allocation"><input type="hidden" class="form-control price_pallet_unit" name="price_pallet_unit[]" placeholder="price_pallet_unit"><input type="hidden" class="form-control itf_pallet_price" name="itf_pallet_price[]" placeholder="itf_pallet_price"><input type="hidden" class="form-control itf_clearance_price" name="itf_clearance_price[]" placeholder="itf_clearance_price"><input type="hidden" class="form-control itf_transport_price" name="itf_transport_price[]" placeholder="itf_transport_price"><input type="hidden" class="form-control itf_cost_price" name="itf_cost_price[]" placeholder="itf_cost_price"><input type="hidden" class="form-control itf_freight_rate" name="itf_freight_rate[]" placeholder="itf_freight_rate"><input type="hidden" class="form-control total_itf_cost" name="total_itf_cost[]" placeholder="total_itf_cost">  <input type="hidden" class="form-control itf_cal_selling" name="itf_cal_selling[]" placeholder="itf_cal_selling"><input type="hidden" class="form-control itf_cost" name="itf_cost[]" placeholder="itf_cost"> <input type="hidden" class="form-control fixPrice" name="fixPrice[]" placeholder="fixPrice"><input type="hidden" class="form-control profitP" name="profitP[]" placeholder="profitP"><input type="hidden" class="form-control profit2" name="profit2[]" placeholder="profit2"><input type="hidden" class="form-control itf_GW" name="itf_GW[]" placeholder="itf_GW" value="0">  <input type="hidden" class="form-control itf_CBM" name="itf_CBM[]" placeholder="itf_CBM"><input type="hidden" class="form-control itf_freight" name="itf_freight[]" placeholder="itf_freight"><input type="hidden" class="form-control itf_fob" name="itf_fob[]" placeholder="itf_fob"><input type="hidden" class="form-control itf_rebate" name="itf_rebate[]" placeholder="itf_rebate"><input type="hidden" class="form-control itf_price" name="itf_price[]" placeholder="itf_price"><input type="hidden" class="form-control ean_cost" name="ean_cost[]" placeholder="ean_cost"><input type="hidden" class="form-control pack_cost" name="pack_cost[]" placeholder="pack_cost"><input type="hidden" class="form-control total_cost" name="total_cost[]" placeholder="total_cost"><input type="hidden" class="form-control itf_fx_price" name="itf_fx_price[]" placeholder="itf_fx_price"> <input type="hidden" class="form-control of_pallats" name="of_pallats[]" placeholder="of_pallats"><input type="hidden" class="form-control box_pallet" name="box_pallet[]" placeholder="box_pallet"><input type="hidden" class="form-control box_weight" name="box_weight[]" value="0" placeholder="box_weight"><input type="hidden" class="form-control total_pallat_weight" name="total_pallat_weight[]" placeholder="total pallat_weight orange"><input type="hidden" class="form-control total_weight" name="total_weight[]" placeholder="total_weight"><input type="hidden" class="form-control iclearance" name="iclearance[]" placeholder="iclearance"><input type="hidden" class="form-control ichamber" name="ichamber[]" placeholder="ichamber"><input type="hidden" class="form-control itruck" name="itruck[]" placeholder="itruck"><input type="hidden" class="form-control ipallets" name="ipallets[]" placeholder="ipallets"><input type="hidden" class="form-control ifreight" name="ifreight[]" placeholder="ifreight"><input type="hidden" class="form-control itotal_cost" name="itotal_cost[]" placeholder="itotal_cost"><input type="hidden" class="com_pallets" name="com_pallets[]"><input type="hidden" class="form-control itf_GW1" name="itf_GW1[]" placeholder="itf_GW1 for row"></td><td data-label="Unit Price"><div class="input-group"><input class="form-control unit_price" type="text" name="unit_price[]" placeholder=""><div class="input-group-append"><span class="input-group-text currency_text">' + currency + '</span></div></div></td><td data-label="Profit"><input class="form-control profit" type="text" name="profit[]" placeholder="" readonly><input class="form-control fob" type="hidden" name="fob[]" placeholder="" readonly></td><td data-label="Action"><a href="javascript:" class="btn btn-danger delete-row" data-id="' + (row + 1) + '" title="Delete"><i class="far fa-trash-alt"></i></a></td></tr>';

    $("#sorted_table").append(markup);

    $('.select2').select2();

});



$(document).on('click', '.delete-row', function() {

    const timing = $(this).data('timing');

    Swal.fire({

        title: "Delete",
        text: "Do you want to delete row ?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        showLoaderOnConfirm: true,

        preConfirm: () => {

            if (typeof undefined == typeof timing)

            {

                $(this).parent().parent().remove();

            } else {

                const id = $(this).data('id');

                $.ajax({

                    url: FULLURL + '/destroyITF',

                    method: 'get',

                    data: {
                        id: id
                    },

                });

                $(this).parent().parent().remove();

            }

            const select_palletized = $('#select_pallet').val();

            const select_clearance = $('#select_clearance').val();

            let total_box = 0;

            let total_nw = 0;

       

            let total_cbm = 0;

            let palletized = 0;

            $.each($('input.number_box'), function() {

                const box = parseFloat($(this).val());

                total_box += box;

            })

            $('#span_box').html(total_box);

            $('#total_box').val(total_box);



            $.each($('input.nw'), function() {

                const nw = parseFloat($(this).val());

                total_nw += nw;

            })

            $('#span_nw').html(total_nw);

            $('#total_nw').val(total_nw);

            if (select_palletized == 'yes') {

                $('#hid_palletizad').attr('style', 'display:inline');

                if ($('#total_box').val() != '') {

                    $.each($('input.pallet'), function() {

                        const pallet = parseFloat($(this).val());

                        palletized += pallet;

                        const one = pallet / palletized;

                        $('#palletized').val(Math.ceil(palletized));

                        $('#span_palletized').html(Math.ceil(palletized));

                        const total_pallet = parseFloat($('#palletized').val());

                        const price_pallet = parseFloat($('#price_pallet').val());

                        const two = total_pallet * price_pallet;

                        const price_allocation = parseFloat(one) * parseFloat(two);

                        $(this).parent().parent().find('td:nth-child(5) input.price_allocation').val(Math.ceil(price_allocation));

                        const qty = $(this).parent().parent().find('td:nth-child(2) input.qty').val();

                        const allocation = $(this).parent().parent().find('td:nth-child(5) input.price_allocation').val();

                        const price_pallet_unit = parseFloat(allocation) / parseFloat(qty);

                        $(this).parent().parent().find('td:nth-child(5) input.price_pallet_unit').val(price_pallet_unit.toFixed(4));



                        const palletized_price = (price_pallet * total_pallet) / palletized;

                        $('#palletized_price').val(palletized_price.toFixed(4));

                    })



                    $.each($('input.gw_weight'), function()

                        {

                            const pallet_weight = (parseFloat($('#weight_pallet').val()) * parseFloat($('#palletized').val())) / parseFloat(palletized);

                            const gw = parseFloat($(this).parent().parent().find('td:nth-child(4) input.number_box').val()) * parseFloat($(this).parent().parent().find('td:nth-child(2) input.new_weight').val());

                            const gw_weight = parseFloat(gw) + (parseFloat($(this).parent().find('input.pallet').val()) * parseFloat(pallet_weight))

                            $(this).val(gw_weight.toFixed(2));



                            $(this).parent().parent().find('td:nth-child(5) input.itf_GW').val(gw.toFixed(2));




                        })



                    $.each($('input.itf_pallet_price'), function() {

                        const palletized_price = parseFloat($('#palletized_price').val());

                        const pallet = parseFloat($(this).parent().find('input.pallet').val());

                        const itf_pallet_price = pallet * palletized_price;

                        $(this).val(itf_pallet_price.toFixed(4));

                    })


 total_gw=0;
                    $.each($('input.gw_weight'), function() {

                        const gw_weight = parseFloat($(this).val());

                        total_gw += gw_weight;

                    })



                    $('#span_gw').html(total_gw.toFixed(2));

                    $('#total_gw').val(total_gw.toFixed(2));
                    getFreights(total_gw.toFixed(2));



                    $.each($('input.cbm'), function() {

                        const cbm = parseFloat($(this).val());

                        total_cbm += cbm;

                    })

                    const cbm_pallet = parseFloat($('#cbm_pallet').val());

                    const cal_cbm = parseFloat(total_cbm) + (parseFloat($('#palletized').val()) * parseFloat(cbm_pallet));

                    $('#span_cbm').html(cal_cbm.toFixed(2));

                    $('#total_cbm').val(cal_cbm.toFixed(2));

                }

            } else {

                $('#hid_palletizad').attr('style', 'display:none');

                if ($('#total_box').val() != '') {

                    $.each($('input.gw_weight'), function() {

                        const gw_weight = parseFloat($(this).parent().parent().find('td:nth-child(4) input.number_box').val()) * parseFloat($(this).parent().parent().find('td:nth-child(2) input.new_weight').val());

                        $(this).parent().parent().find('td:nth-child(5) input.gw_weight').val(gw_weight.toFixed(2));

                    })



                    $.each($('input.maxpallet'), function() {

                        $(this).parent().parent().find('td:nth-child(5) input.price_allocation').val(palletized);

                        $(this).parent().parent().find('td:nth-child(5) input.price_pallet_unit').val(palletized);

                        $(this).parent().parent().find('td:nth-child(5) input.itf_pallet_price').val(palletized);

                    })


total_gw=0;
                    $.each($('input.gw_weight'), function() {

                        const gw_weight = parseFloat($(this).val());

                        total_gw += gw_weight;

                    })

                    $('#span_gw').html(total_gw);

                    $('#total_gw').val(total_gw);



                    $.each($('input.cbm'), function() {

                        const cbm = parseFloat($(this).val());

                        total_cbm += cbm;

                    })

                    $('#span_cbm').html(total_cbm.toFixed(2));

                    $('#total_cbm').val(total_cbm.toFixed(2));

                }

            }

        }

    });

});



$(document).on('change', '#client', function() {

    var $this = $(this);

    const id = $this.val();

    $.get(FULLURL + '/getShip?id=' + id)

        .done(function(data) {

            // ////console.log(data)

            $('#shipto').empty();

            $('#shipto').append(data);

        })

    $.get(FULLURL + '/getAir?shipto_id=')

        .done(function(data) {

            // ////console.log(data)

            $('#airport').empty();

            $('#airport').append(data.airport);

            $('#airline').empty();

            $('#airline').append(data.airline);

        })

})



$(document).on('change', '#shipto', function() {

    var $this = $(this);

    const id = $this.val();

    $.get(FULLURL + '/getAir?shipto_id=' + id)

        .done(function(data) {

            // ////console.log(data)

            $('#airport').empty();

            $('#airport').append(data.airport);

            $('#airline').empty();

            $('#airline').append(data.airline);

        })

})



$(document).on('change', '#airport', function() {

    var $this = $(this);

    const id = $this.val();

    if ($this.val() != "")

    {

        $('#shipto').attr('disabled', true);

        $.get(FULLURL + '/getAir?airport_id=' + id)

            .done(function(data) {

                $('#airline').empty();

                $('#airline').append(data.airline);

            })

    } else {

        $('#shipto').attr('disabled', false);

        $.get(FULLURL + '/getAir?airport_id=' + id)

            .done(function(data) {

                $('#airline').empty();

                $('#airline').append(data.airline);

            })

    }

})



$(document).on('change', '#currency', function() {

    if ($(this).val() == 1) {

        $('#ex_rate').val(1);

    } else {

        $('#ex_rate').val(null);

    }

    $('.currency_text').text($(this).children("option:selected").text());

})



$(document).on('change', '#select_pallet', function() {

    var $this = $(this);

    const val = $this.val();

    let total_box = 0;

    let total_nw = 0;

    let total_gw = 0;

    let total_cbm = 0;

    let palletized = 0;

    if (val == 'yes')

    {

        $('#hid_palletizad').attr('style', 'display:inline');

        if ($('#total_box').val() != '') {

            $.each($('input.pallet'), function() {

                const pallet = parseFloat($(this).val());

                palletized += pallet;

                const one = pallet / palletized;

                $('#palletized').val(Math.ceil(palletized));

                $('#span_palletized').html(Math.ceil(palletized));

                const total_pallet = parseFloat($('#palletized').val());

                const price_pallet = parseFloat($('#price_pallet').val());

                const two = total_pallet * price_pallet;

                const price_allocation = parseFloat(one) * parseFloat(two);

                $(this).parent().parent().find('td:nth-child(5) input.price_allocation').val(Math.ceil(price_allocation));

                const qty = $(this).parent().parent().find('td:nth-child(2) input.qty').val();

                const allocation = $(this).parent().parent().find('td:nth-child(5) input.price_allocation').val();

                const price_pallet_unit = parseFloat(allocation) / parseFloat(qty);

                $(this).parent().parent().find('td:nth-child(5) input.price_pallet_unit').val(price_pallet_unit.toFixed(4));



                const palletized_price = (price_pallet * total_pallet) / palletized;

                $('#palletized_price').val(palletized_price.toFixed(4));

            })



            $.each($('input.gw_weight'), function() {

                const pallet_weight = (parseFloat($('#weight_pallet').val()) * parseFloat($('#palletized').val())) / parseFloat(palletized);

                const gw = parseFloat($(this).parent().parent().find('td:nth-child(4) input.number_box').val()) * parseFloat($(this).parent().parent().find('td:nth-child(2) input.new_weight').val());

                const gw_weight = parseFloat(gw) + (parseFloat($(this).parent().find('input.pallet').val()) * parseFloat(pallet_weight))

                $(this).val(gw_weight.toFixed(2));

            })



            $.each($('input.itf_pallet_price'), function()

                {

                    const palletized_price = parseFloat($('#palletized_price').val());

                    const pallet = parseFloat($(this).parent().find('input.pallet').val());

                    const itf_pallet_price = pallet * palletized_price;

                    $(this).val(itf_pallet_price.toFixed(4));

                })

total_gw=0;

            $.each($('input.gw_weight'), function() {

                const gw_weight = parseFloat($(this).val());

                total_gw += gw_weight;

            })

            // const weight_pallet = parseFloat($('#weight_pallet').val());

            // const cal_gw = parseFloat(total_gw)+(parseFloat($('#palletized').val())*parseFloat(weight_pallet));

            $('#span_gw').html(total_gw.toFixed(3));

            $('#total_gw').val(total_gw.toFixed(3));



            $.each($('input.cbm'), function() {

                const cbm = parseFloat($(this).val());

                total_cbm += cbm;

            })

            const cbm_pallet = parseFloat($('#cbm_pallet').val());

            const cal_cbm = parseFloat(total_cbm) + (parseFloat($('#palletized').val()) * parseFloat(cbm_pallet));

            $('#span_cbm').html(cal_cbm.toFixed(3));

            $('#total_cbm').val(cal_cbm.toFixed(3));

        }

    } else {

        $('#hid_palletizad').attr('style', 'display:none');

        if ($('#total_box').val() != '') {

            $.each($('input.gw_weight'), function() {

                const gw_weight = parseFloat($(this).parent().parent().find('td:nth-child(4) input.number_box').val()) * parseFloat($(this).parent().parent().find('td:nth-child(2) input.new_weight').val());

                $(this).parent().parent().find('td:nth-child(5) input.gw_weight').val(gw_weight.toFixed(2));

            })



            $.each($('input.maxpallet'), function() {

                $(this).parent().parent().find('td:nth-child(5) input.price_allocation').val(palletized);

                $(this).parent().parent().find('td:nth-child(5) input.price_pallet_unit').val(palletized);

                $(this).parent().parent().find('td:nth-child(5) input.itf_pallet_price').val(palletized);

            })


total_gw=0;
            $.each($('input.gw_weight'), function() {

                const gw_weight = parseFloat($(this).val());

                total_gw += gw_weight;

            })

            $('#span_gw').html(total_gw);

            $('#total_gw').val(total_gw);



            $.each($('input.cbm'), function() {

                const cbm = parseFloat($(this).val());

                total_cbm += cbm;

            })

            $('#span_cbm').html(total_cbm.toFixed(3));

            $('#total_cbm').val(total_cbm.toFixed(3));

        }

    }

})



$(document).on('change', '#select_clearance', function() {

    var $this = $(this);

    const id = $this.val();

    if ($this.val() != '') {

        $.get(FULLURL + '/getClearance?id=' + id)

            .done(function(data) {

                $('#clearance').val(data.clearance)

                $('#chamber').val(data.chamber)



                $('#clearanceN').val(data.charge)

                $('#chamberN').val(data.chamber)

                $('#certificationN').val(data.certificate)

                $('#extraN').val(data.extras)

                getPalatass();




            })

    }

})



$(document).on('change', '#select_chamber', function() {

    var $this = $(this);

    const val = $this.val();

    if (val == 'yes') {

        const clearance = parseFloat($('#clearance').val());

        const chamber = parseFloat($('#chamber').val());

        const new_clearance = parseFloat(clearance) + parseFloat(chamber);

        $('#clearance').val(new_clearance);

    } else {

        const clearance = parseFloat($('#clearance').val());

        const chamber = parseFloat($('#chamber').val());

        const new_clearance = parseFloat(clearance) - parseFloat(chamber);

        $('#clearance').val(new_clearance);

    }

})



$(document).on('change', '.select_itf', function() {

    var $this = $(this);

    const id = $this.val();

    let count = 0;

    $.each($('.select_itf'), function() {

        const val = $(this).val();

        if (id == val) {

            count = count + 1;

        }

    })

    if (count > 1) {

        alert('มีรายการที่เลือกอยู่แล้ว / The item selected already exists.')

        $this.parent().parent().find('td:nth-child(1) select.select_itf').val(null).trigger('change');

        $this.parent().parent().find('td:nth-child(2) input.ean_id').val(null);

        $this.parent().parent().find('td:nth-child(2) input.ean_qty').val(null);

        $this.parent().parent().find('td:nth-child(2) input.net_weight').val(null);

        $this.parent().parent().find('td:nth-child(2) input.new_weight').val(null);

        $this.parent().parent().find('td:nth-child(2) input.maxcbm').val(null);

        $this.parent().parent().find('td:nth-child(2) input.maxpallet').val(null);

    } else {

        if ($this.val() != '')

            $.get(FULLURL + '/getVal?id=' + id)

            .done(function(data) {

                $this.parent().parent().find('td:nth-child(2) input.qty').attr('data-ean', data.ean_id);

                $this.parent().parent().find('td:nth-child(2) input.ean_id').val(data.ean_id);

                $this.parent().parent().find('td:nth-child(2) input.ean_qty').val(data.qty);

                $this.parent().parent().find('td:nth-child(2) input.net_weight').val(data.net_weight);

                $this.parent().parent().find('td:nth-child(2) input.new_weight').val(data.new_weight);

                $this.parent().parent().find('td:nth-child(2) input.maxcbm').val(data.maxcbm);

                $this.parent().parent().find('td:nth-child(2) input.maxpallet').val(data.maxpallet);

                $this.parent().parent().find('td:nth-child(2) input.ean_ppITF').val(data.ean_ppITF);
                $this.parent().parent().find('td:nth-child(2) input.itfQty').val(data.itfQty);

                $this.parent().parent().find('td:nth-child(5) input.box_pallet').val(data.box_pallet);



                $this.parent().parent().find('td:nth-child(5) input.itf_GW').val(data.new_weight);

                ;
                $this.parent().parent().find('td:nth-child(2) input.hpl_avg_weight').val(data.hpl_avg_weight);


                var net_weightNew = parseFloat(data.hpl_avg_weight / 1000 * data.qty);
                net_weightNew=net_weightNew.toFixed(2);
                var box_weight = (parseFloat(data.new_weight) + (parseFloat(net_weightNew) - parseFloat(data.net_weight)))

                $this.parent().parent().find('td:nth-child(5) input.box_weight').val(box_weight)
                $this.parent().parent().find('td:nth-child(2) input.net_weightNew').val(net_weightNew);


                // alert("EAN AVG Weight from HPL :"+data.hpl_avg_weight+"  , net_weight New field:"+net_weightNew);

                // ////console.log(data)




            })

    }

    $this.parent().parent().find('td:nth-child(2) input.ean_id').val(null);

    $this.parent().parent().find('td:nth-child(2) input.ean_qty').val(null);

    $this.parent().parent().find('td:nth-child(2) input.net_weight').val(null);

    $this.parent().parent().find('td:nth-child(2) input.new_weight').val(null);

    $this.parent().parent().find('td:nth-child(2) input.maxcbm').val(null);

    $this.parent().parent().find('td:nth-child(2) input.maxpallet').val(null);

    $this.parent().parent().find('td:nth-child(2) input.qty').val(null);

    $this.parent().parent().find('td:nth-child(3) select.unitcount').val(null);

    $this.parent().parent().find('td:nth-child(4) input.number_box').val(null);

    $this.parent().parent().find('td:nth-child(5) input.nw').val(null);

    $this.parent().parent().find('td:nth-child(5) input.gw_weight').val(null);

    $this.parent().parent().find('td:nth-child(5) input.cbm').val(null);

    $this.parent().parent().find('td:nth-child(5) input.pallet').val(null);

    $this.parent().parent().find('td:nth-child(5) input.price_allocation').val(null);

    $this.parent().parent().find('td:nth-child(5) input.price_pallet_unit').val(null);

    $this.parent().parent().find('td:nth-child(5) input.itf_pallet_price').val(null);

    $this.parent().parent().find('td:nth-child(5) input.itf_clearance_price').val(null);

    $this.parent().parent().find('td:nth-child(5) input.itf_transport_price').val(null);

    $this.parent().parent().find('td:nth-child(5) input.itf_cost_price').val(null);

    $this.parent().parent().find('td:nth-child(5) input.total_itf_cost').val(null);

    $this.parent().parent().find('td:nth-child(5) input.freight_rate').val(null);

    $this.parent().parent().find('td:nth-child(6) input.unit_price').val(null);

    $this.parent().parent().find('td:nth-child(7) input.profit').val(null);

})



function recalculation() {
    setTimeout(function() {
       // $(".unitcount").change();
        /// alert("ok")
    }, 500)

}






$(document).on('change', '.unitcount', function() {

    if ($('#airport').val()== "" || $('#airline').val()== "") 
    {
          $('.unitcount').prop('selectedIndex',0);
        alert('Please select Airport and Airline');
        return false;
    }
 
    /* start point of calculation */

    var $this = $(this);
    setBlanck($this);
    const qty = $this.parent().parent().find('td:nth-child(2) input.qty').val();

    const itf_id = $this.parent().parent().find('td:nth-child(1) select.select_itf').val();

    const id = $this.val();
//console.log(id+" unt");
    const ean_id = $this.parent().parent().find('td:nth-child(2) input.ean_id').val().split(',');

    const count_ean_id = ean_id.length;

    const ean_qty = $this.parent().parent().find('td:nth-child(2) input.ean_qty').val();

    const net_weight = $this.parent().parent().find('input.net_weight').val();
    const net_weightNew = parseFloat($this.parent().parent().find('input.net_weightNew').val());

    const itf_gw = $this.parent().parent().find('input.net_weight').val();

    const maxcbm = $this.parent().parent().find('input.maxcbm').val();

    const maxpallet = $this.parent().parent().find('input.maxpallet').val();

    const select_palletized = $('#select_pallet').val();

    const ex_rate = parseFloat($('#ex_rate').val());

    const ean_ppITF = $this.parent().parent().find('td:nth-child(2) input.ean_ppITF').val();

    //alert(select_palletized)

    cal_pallet(select_palletized);

    let total_box = 0;

    let total_nw = 0;

    let total_gw = 0;

    let total_cbm = 0;

    let palletized = 0;

    let net_weight2 = 0;

    let box = 0;



    if (id == 5)

    {

        box = (parseFloat(qty)).toFixed(2);


    } else if (id == 2)

    {

        box = (parseFloat(qty) / parseFloat(net_weightNew)).toFixed(2);



    } else if (id == 1)

    {

        box = (parseFloat(qty) / parseFloat(ean_qty)).toFixed(2);


    }
  $this.parent().parent().find('td:nth-child(4) input.number_box').val(box);

    if (box > 0) {
        var res1 = box.toString();
        var res = res1.split(".");
        var dec = parseFloat(res[1]);
        //alert(dec);
        if (dec > 0) {
            var nm = $(".c-avatar").text();
            alert("Dear " + nm + " your number of boxes are " + box + ", please adjust your quantity to complete full box");
            $this.parent().parent().find('td:nth-child(2) input.qty').focus();
        }


    } else {
        if (res) {
            var nm = $(".c-avatar").text();
            alert("Dear " + nm + " your number of boxes are " + box + ", please adjust your quantity to complete full box");
            $this.parent().parent().find('td:nth-child(2) input.qty').focus();
        }

    }




    net_weight2 = parseFloat(box) * parseFloat(net_weightNew);

    $this.parent().parent().find('td:nth-child(2) input.net_weight2').val(parseFloat(net_weight2));




    const nw = net_weight2;

    $this.parent().parent().find('td:nth-child(5) input.nw').val(nw.toFixed(2));



    const cbm = parseFloat(box) * parseFloat(maxcbm);
     let itf_CBM =cbm;



       $this.parent().parent().find('td:nth-child(5) input.itf_CBM').val((itf_CBM).toFixed(3));

    $this.parent().parent().find('td:nth-child(5) input.cbm').val(cbm.toFixed(3));

    if (maxpallet != 0) {

        const pallet = parseFloat(box) / parseFloat(maxpallet);

        $this.parent().parent().find('td:nth-child(5) input.pallet').val(pallet.toFixed(2));

    } else {

        $this.parent().parent().find('td:nth-child(5) input.pallet').val(0);

    }



    let rebate = parseFloat($('#rebate').val())
    let markup_rate = parseFloat($('#markup_rate').val())

    var markup_rateCal = ((0.0198 * (markup_rate * markup_rate)) + (0.7901 * markup_rate) + 1.34) / 100;

    $("#markup_rateCal").val(markup_rateCal.toFixed(4));




    if (id != '' && qty != '') {

        const time = $('#sorted_table tbody tr').length * 1000;
      
         var isCallables= $("#isCallables").val();
        
        if(isCallables==0)
        {
           loading(time); 
        }
        

        let check = {};

        const num_box = $this.parent().parent().find('td:nth-child(4) input.number_box').val();

        if (count_ean_id > 1) {

            $.each(ean_id, function($key, $val) {

                ean = parseFloat(ean_qty) / count_ean_id;

                var pc = parseFloat(num_box) * ean;

                check[$val] = pc;

            })

        } else {

            ean = parseFloat(ean_qty)

            var pc = parseFloat(num_box) * ean;

            check[ean_id] = pc;

        }

        $.each($('.ean_id'), function()

            {

                const count_ean_id2 = $(this).val().split(',').length;

                if ($(this).parent().parent().find('td:nth-child(1) select.select_itf').val() != itf_id) {

                    var id = $(this).val().split(',');

                    var number_box = $(this).parent().parent().find('td:nth-child(4) input.number_box').val();

                    var qty = $(this).parent().parent().find('input.ean_qty').val();

                    if (count_ean_id2 > 1) {

                        $.each($(this).val().split(','), function($key, $val) {

                            var ean2 = parseFloat(qty) / count_ean_id2

                            var pc2 = parseFloat(number_box) * ean2;

                            if ($val in check) {

                                check[$val] += pc2;

                            }

                        })

                    } else {

                        var ean2 = parseFloat(qty);

                        var pc2 = parseFloat(number_box) * ean2;

                        if (id in check) {

                            check[id] += pc2;

                        }

                    }

                    // reCost($(this).parent().parent().find('td:nth-child(1) select.select_itf').val());

                }

            })



        var myJSON = JSON.stringify(check);

        $.ajax({

            url: FULLURL + '/checkpacking',

            method: 'get',

            data: {
                check: myJSON,
                itf_id: itf_id,
                num_box: num_box
            },

            success: function(data) {

                ////console.log(data);

                $.each(data.rs, function(k, v) {

                    if (v == 'true' && data.rs_re[k] == 'true') {

                        ////console.log(data);

                        if (data.count[k] != 0) {

                            $.get(FULLURL + '/getCost?id=' + itf_id + '&num_box=' + num_box)

                                .done(function(data) {

                                    // ////console.log(FULLURL+'/getCost?id='+itf_id+'&num_box='+num_box);

                                    ////console.log(data);

                                    $this.parent().parent().find('td:nth-child(5) input.itf_cost_price').val(data.cost.toFixed(4));

                                    $this.parent().parent().find('td:nth-child(5) input.pack_cost').val(data.pack_cost.toFixed(4));

                                    $this.parent().parent().find('td:nth-child(5) input.ean_cost').val(data.ean_cost.toFixed(4));
                                    //$this.parent().parent().find('td:nth-child(5) input.ean_cost').val(44.76);

                                    $this.parent().parent().find('td:nth-child(5) input.total_cost').val(data.total_cost.toFixed(4));
                                    // $this.parent().parent().find('td:nth-child(5) input.total_cost').val(data.ean_cost.toFixed(4));




                                    let itf_nw = parseFloat($this.parent().parent().find('td:nth-child(5) input.nw').val());;




                                    let unitPrice;

                                    let fixPrice;

                                    let of_pallats = 0;

                                    let total_pallat_weight = 0;

                                    let itfUnit = id;




                                    let box_pallet = $this.parent().parent().find('td:nth-child(5) input.box_pallet').val();



                                    let select_chamber = $('#select_chamber').val();

                                    // alert("select_chamber:"+select_chamber)

                                    if (select_palletized == "yes")

                                    {



                                        of_pallats = parseFloat(box) / parseFloat(box_pallet);

                                        // alert(" of_pallats:"+of_pallats+"   box_pallet:"+box_pallet+" box:"+box) 

                                    }
                                    // of_pallats = box / parseFloat(box_pallet);
                                    $this.parent().parent().find('td:nth-child(5) input.of_pallats').val(parseFloat(of_pallats));

                                    let total_pallet = 0;
                                    let complete_pallet = 0;

                                     

                                    if (select_palletized == 'yes') 
                                    {
                                         $.each($('input.of_pallats'), function() 
                                         {



                                        total_pallet += parseFloat($(this).val());

                                     })

                                       
                                      //   alert(total_pallet)



                                        complete_pallet = Math.round(total_pallet);

                                        if (complete_pallet < total_pallet) {
                                            complete_pallet += 1;
                                        }

                                        if (complete_pallet < 1) 
                                        {
                                            complete_pallet = 1;
                                        }
                    $this.parent().parent().find('td:nth-child(5) input.com_pallets').val(parseFloat(complete_pallet));
                                        // alert("total_pallet: "+total_pallet+" complete_pallet:"+complete_pallet)

                                    }

                                    $("#total_pallet").val(total_pallet);


                                    var price_pallet = parseFloat($("#price_pallet").val());

                                    $("#complete_pallet").val(complete_pallet);




                                    $("#total_pallet_cost").val(complete_pallet * price_pallet);




                                    let total_box = 0;

                                    $.each($('input.number_box'), function() {



                                        total_box += parseFloat($(this).val());

                                    })




                                    total_pallat_weight = (parseFloat($("#weight_pallet").val()) / parseFloat(total_box)) * complete_pallet;

                                    //alert("total_pallat_weight: "+total_pallat_weight+" weight_pallet: "+$("#weight_pallet").val()+" total_box"+total_box)

                                    let box_weight = $this.parent().parent().find('td:nth-child(5) input.box_weight').val();




                                    let total_weight = total_pallat_weight + parseFloat(box_weight);



                                    $this.parent().parent().find('td:nth-child(5) input.total_pallat_weight').val(parseFloat(total_pallat_weight));




                                    $this.parent().parent().find('td:nth-child(5) input.total_weight').val(parseFloat(total_weight).toFixed(3));



                                    let itf_GW1 = parseFloat(box) * parseFloat(total_weight);



                                    $this.parent().parent().find('td:nth-child(5) input.itf_GW1').val(parseFloat(itf_GW1).toFixed(3));

 var titem=0;
                                       
                                             var span_gw=0;
                                $.each($('input.gw_weight'), function() 
                                {


                                if(!isNaN(parseFloat($(this).val())))
                                {
                                    span_gw += parseFloat($(this).val());
                                }
                                titem += 1;
                               // //console.log(" c "+titem+"  itf_GW1"+itf_GW1+"ok "+$(this).val())
                            })


                    var isCallables= $("#isCallables").val();
        
                    if(isCallables==0)
                    {
                        console.log("0");
                        $("#span_gw").text(sudhirComma(span_gw.toFixed(3))); 
                    }

                            ////console.log("box:"+box+"  total_weight:"+total_weight+" itf_GW1:"+itf_GW1);
                      




                    
                  
                     var span_box = 0;

                          $.each($('input.number_box'), function() 
                          {

                            if(!isNaN(parseFloat($(this).val())))
                            {
                              span_box += parseFloat($(this).val());

                            
                            }

                            

                    })


                    $("#span_box").text(sudhirComma(span_box));
                    $("#span_Item").text(sudhirComma(titem));

                    var nww=0;
                    $.each($('input.nw'), function() {

                         if(!isNaN(parseFloat($(this).val())))
                        {
                            nww += parseFloat($(this).val());
                        }

                       



                    })


                    $("#span_nw").text(sudhirComma(nww));


                    

                    var span_cbm = 0;

                    $.each($('input.itf_CBM'), function() {
                         if(!isNaN(parseFloat($(this).val())))
                        {
                            span_cbm += parseFloat($(this).val());
                        }


                        

                    })

                    var k15 = parseFloat($("#cbm_pallet").val());
                    if ($('#select_pallet').val() == 'yes') 
                    {

                         var cmplt = 0;

                        cmplt=$('#complete_pallet').val();


                     $('#span_palletized').html(cmplt);
                                     
                    $("#span_cbm").text(sudhirComma((parseFloat(span_cbm) + parseFloat(k15)*complete_pallet).toFixed(3)));
                    }
                    else
                    {

                     $("#span_cbm").text(sudhirComma(parseFloat(span_cbm).toFixed(3)));  
                    }



              
                                   



                                   

                                    let clearance = $("#clearance").val();

                                    let chamber = $("#chamber").val();

                                    //let clearance=$("#clearance").val();

                                    let iclearance = 0

                                    let totaNW = 0;

                                    $.each($('input.nw'), function() {



                                        totaNW += parseFloat($(this).val());

                                    })




                                    let ITF_NWO = $("#ITF_NWO").val();

                                    if (box > 0)

                                    {




                                        if (ITF_NWO = "actual")

                                        {

                                            iclearance = parseFloat(clearance) / parseFloat(totaNW) * parseFloat(net_weightNew)

                                        } else

                                        {



                                            iclearance = parseFloat(clearance) / parseFloat(ITF_NWO) * parseFloat(net_weightNew);

                                        }




                                    } else

                                    {

                                        iclearance = 0;

                                    }




                                    let ichamber = 0;

                                    if (select_chamber == "yes" && box > 0)

                                    {




                                        if (ITF_NWO = "actual")

                                        {

                                            ichamber = parseFloat(chamber) / parseFloat(totaNW) * parseFloat(net_weightNew)

                                        } else

                                        {



                                            ichamber =parseFloat( chamber) / parseFloat(ITF_NWO) * parseFloat(net_weightNew);

                                        }



                                    }




                                    let tPrice = 0;;




                                    //alert("of_pallats:"+of_pallats)



                                    let itruck = 0; //$this.parent().parent().find('td:nth-child(5) input.itruck').val();




                                    const total_pallet_cost = parseFloat($('#total_pallet_cost').val());



                                    let ipallets = parseFloat(total_pallet_cost) / parseFloat(totaNW) * parseFloat(net_weightNew);

                              


                                    var total_gw = 0;

                                    $.each($('input.gw_weight'), function() {

                                        let gw_weight = parseFloat($(this).val());

                                        total_gw += gw_weight;
                                       
                                        //alert("1 by 1: "+gw_weight);
                                    })





                      
                                    const select_clearance = $('#select_clearance').val();

                                    let transport = 0;



                                    $.ajax({

                                        url: FULLURL + '/getTruck',

                                        method: 'get',

                                        data: {
                                            total_gw: total_gw,
                                            select_clearance: select_clearance,
                                            pallet: ipallets
                                        },

                                        success: function(data) {

                                          //  ////console.log(" gw_weight:"+gw_weight);

                                            $("#transport").val(data)
                                            transport = parseInt(data);

                                            $.ajax({
                                                url: FULLURL + '/getRate',

                                                method: 'get',

                                                data: {
                                                    total_gw: total_gw,
                                                    clear: $('#select_clearance').val(),
                                                    destination: $('#airport').val(),
                                                    airline: $('#airline').val()
                                                },

                                                success: function(data) {
                                                    $('#freights').val(data.rate);






                                                    if (box > 0)

                                                    {




                                                        if (ITF_NWO = "actual")

                                                        {

                                                            itruck = parseFloat(transport) / parseFloat(totaNW) * parseFloat(net_weightNew);

                                                       //     alert("itf_GW1:" + itf_GW1 + "transport price:" + transport + " totaNW:" + totaNW + " net_weight:" + net_weight)

                                                        } else

                                                        {



                                                            itruck = parseFloat(transport) / parseFloat(ITF_NWO) * (parseFloat(net_weightNew) * parseFloat(box));

                                                        }



                                                    }


                                                    itruck=itruck.toFixed(4);

                                            

                                                    let ifreight1 = total_weight * parseFloat($('#freights').val());

                                                    console.log("ifreight1:"+ifreight1+" total_weight:"+total_weight+" $('#freights').val():"+$('#freights').val())
                                                    let ifreight = parseFloat(ifreight1).toFixed(5)



                                                    let total_cost = parseFloat($this.parent().parent().find('td:nth-child(5) input.total_cost').val()).toFixed(5);




                                                    var tcost1 = parseFloat(total_cost) + parseFloat(iclearance) + parseFloat(itruck) + parseFloat(ipallets) + parseFloat(ifreight);

                                                    var tcost = parseFloat(tcost1).toFixed(5);




                                                    let itf_cal_selling = tcost * (1 + (((0.0198 * (markup_rate * markup_rate)) + (0.7901 * markup_rate) + 1.34) / 100));




                                                    $this.parent().parent().find('td:nth-child(5) input.itf_cal_selling').val(itf_cal_selling.toFixed(5));






                                                    fixPrice = parseFloat(itf_cal_selling) / parseFloat(ex_rate);


                                                    if (itfUnit == '5') {

                                                        unitPrice = itf_cal_selling;

                                                    } else if (itfUnit == '2')

                                                    {

                                                        unitPrice = parseFloat(itf_cal_selling) / parseFloat(qty);



                                                        fixPrice = (parseFloat(fixPrice) / parseFloat(net_weightNew));

                                                    } else if (itfUnit == '1')

                                                    {

                                                        unitPrice = parseFloat(itf_cal_selling) / parseFloat(ean_ppITF);



                                                        fixPrice = (parseFloat(fixPrice) / parseFloat(ean_qty));

                                                    } else

                                                    {

                                                        fixPrice = 0;

                                                        unitPrice = 0;

                                                    }






                                                    fixPrice = fixPrice.toFixed(2)
                                                    unitPrice = unitPrice.toFixed(2)

                                                    let itf_fx_price1 = (parseFloat(fixPrice) * parseFloat(qty)) / (parseFloat(box));
                                                    //alert("fixPrice when calcute itf FX price:"+parseFloat(fixPrice)+" qty:"+qty+" box:"+box)

                                                    let itf_freight_ = Math.round(parseFloat(ifreight) * parseFloat(box));
                                                    let itf_fx_price = parseFloat(itf_fx_price1).toFixed(2);


                                                    itf_fob = Math.round(parseFloat((parseFloat(fixPrice) * parseFloat(qty) * parseFloat(ex_rate)) - parseFloat(itf_freight_))).toFixed(5);

                                                  
                                                    let profit2 = (parseFloat(itf_fob) + parseFloat(itf_freight_)) - (parseFloat(tcost) * parseFloat(box));


                                                    $this.parent().parent().find('td:nth-child(5) input.fixPrice').val(parseFloat(fixPrice));


                                                   
                                                    let rebates = parseFloat($('#rebate').val());
                                                    let profitP = ((parseFloat(itf_fx_price) * parseFloat(ex_rate)) * (1 - parseFloat(rebates)) - parseFloat(tcost)) / parseFloat(itf_cal_selling);
                                                   



                                                    $this.parent().parent().find('td:nth-child(5) input.profitP').val(parseInt(profitP));




                                                    $this.parent().parent().find('td:nth-child(5) input.itf_fob').val(parseFloat(itf_fob).toFixed(5));

                                                    $this.parent().parent().find('td:nth-child(5) input.profit2').val(parseFloat(profit2).toFixed(5));

                                                    $this.parent().parent().find('td:nth-child(5) input.itf_fx_price').val(parseFloat(itf_fx_price).toFixed(2));




                                                    $this.parent().parent().find('td:nth-child(5) input.iclearance').val(parseFloat(iclearance).toFixed(5));

                                                    $this.parent().parent().find('td:nth-child(5) input.ichamber').val(parseFloat(ichamber).toFixed(5));

                                                    $this.parent().parent().find('td:nth-child(5) input.ipallets').val(parseFloat(ipallets).toFixed(5));

                                                    $this.parent().parent().find('td:nth-child(5) input.itruck').val(parseFloat(itruck).toFixed(5));

                                                    $this.parent().parent().find('td:nth-child(5) input.ifreight').val(parseFloat(ifreight).toFixed(5));


                                                  

                                                    //////console.log(data);

                                                }

                                            })
                                        }
                                    })



                                    ///  end truck price






                                })



                        }

                    } else

                    {



                        if (data.count[k] == 0 && parseFloat(data.sum[k]) == 0)

                        {

                            alert('ไม่มีสินค้าที่แพ็คแล้ว / No products have been packed.')

                            $this.parent().parent().find('td:nth-child(1) select.select_itf').val(null).trigger('change');

                            $this.parent().parent().find('td:nth-child(2) input.qty').val(null);

                            $this.parent().parent().find('td:nth-child(2) input.ean_id').val(null);

                            $this.parent().parent().find('td:nth-child(2) input.ean_qty').val(null);

                            $this.parent().parent().find('td:nth-child(2) input.net_weight').val(null);

                            $this.parent().parent().find('td:nth-child(2) input.new_weight').val(null);

                            $this.parent().parent().find('td:nth-child(2) input.maxcbm').val(null);

                            $this.parent().parent().find('td:nth-child(2) input.maxpallet').val(null);

                            $this.parent().parent().find('td:nth-child(3) select.unitcount').val(null);

                            $this.parent().parent().find('td:nth-child(4) input.number_box').val(null);

                            $this.parent().parent().find('td:nth-child(5) input.nw').val(null);

                            $this.parent().parent().find('td:nth-child(5) input.gw_weight').val(null);

                            $this.parent().parent().find('td:nth-child(5) input.cbm').val(null);

                            $this.parent().parent().find('td:nth-child(5) input.pallet').val(null);

                            $this.parent().parent().find('td:nth-child(5) input.price_allocation').val(null);

                            $this.parent().parent().find('td:nth-child(5) input.price_pallet_unit').val(null);

                            $this.parent().parent().find('td:nth-child(5) input.itf_pallet_price').val(null);

                            $this.parent().parent().find('td:nth-child(5) input.itf_clearance_price').val(null);

                            $this.parent().parent().find('td:nth-child(5) input.itf_transport_price').val(null);

                            $this.parent().parent().find('td:nth-child(5) input.itf_cost_price').val(null);

                            $this.parent().parent().find('td:nth-child(5) input.freight_rate').val(null);

                            $this.parent().parent().find('td:nth-child(6) input.unit_price').val(null);

                        }



                        $.each(data.count_re, function(key, val) {

                            if (val == 0 && parseFloat(data.sum_re[key]) == 0) {

                                alert('ไม่มีส่วนประกอบ / No product setup.')

                                $this.parent().parent().find('td:nth-child(1) select.select_itf').val(null).trigger('change');

                                $this.parent().parent().find('td:nth-child(2) input.qty').val(null);

                                $this.parent().parent().find('td:nth-child(2) input.ean_id').val(null);

                                $this.parent().parent().find('td:nth-child(2) input.ean_qty').val(null);

                                $this.parent().parent().find('td:nth-child(2) input.net_weight').val(null);

                                $this.parent().parent().find('td:nth-child(2) input.new_weight').val(null);

                                $this.parent().parent().find('td:nth-child(2) input.maxcbm').val(null);

                                $this.parent().parent().find('td:nth-child(2) input.maxpallet').val(null);

                                $this.parent().parent().find('td:nth-child(3) select.unitcount').val(null);

                                $this.parent().parent().find('td:nth-child(4) input.number_box').val(null);

                                $this.parent().parent().find('td:nth-child(5) input.nw').val(null);

                                $this.parent().parent().find('td:nth-child(5) input.gw_weight').val(null);

                                $this.parent().parent().find('td:nth-child(5) input.cbm').val(null);

                                $this.parent().parent().find('td:nth-child(5) input.pallet').val(null);

                                $this.parent().parent().find('td:nth-child(5) input.price_allocation').val(null);

                                $this.parent().parent().find('td:nth-child(5) input.price_pallet_unit').val(null);

                                $this.parent().parent().find('td:nth-child(5) input.itf_pallet_price').val(null);

                                $this.parent().parent().find('td:nth-child(5) input.itf_clearance_price').val(null);

                                $this.parent().parent().find('td:nth-child(5) input.itf_transport_price').val(null);

                                $this.parent().parent().find('td:nth-child(5) input.itf_cost_price').val(null);

                                $this.parent().parent().find('td:nth-child(5) input.freight_rate').val(null);

                                $this.parent().parent().find('td:nth-child(6) input.unit_price').val(null);

                            }



                        })

                    }

                })

            }

        })

        $.each($('input.number_box'), function() {

            const box = parseFloat($(this).val());

            total_box += box;

        })

        // $('#span_box').html(total_box);

        $('#total_box').val(total_box);



        $.each($('input.nw'), function() {

            const nw = parseFloat($(this).val());

            total_nw += nw;

        })

        // $('#span_nw').html(total_nw.toFixed(2));

        $('#total_nw').val(total_nw.toFixed(2));

    }



    if (select_palletized == 'yes') {

        $.each($('input.pallet'), function()

            {

                const pallet = parseFloat($(this).val());

                palletized += pallet;

                const one = pallet / palletized;

                $('#palletized').val(Math.ceil(palletized));

                $('#span_palletized').html(Math.ceil(palletized));

                const total_pallet = parseFloat($('#palletized').val());

                const price_pallet = parseFloat($('#price_pallet').val());

                const two = total_pallet * price_pallet;

                const price_allocation = parseFloat(one) * parseFloat(two);

                $(this).parent().parent().find('td:nth-child(5) input.price_allocation').val(Math.ceil(price_allocation));

                const qty = $(this).parent().parent().find('td:nth-child(2) input.qty').val();

                const allocation = $(this).parent().parent().find('td:nth-child(5) input.price_allocation').val();

                const price_pallet_unit = parseFloat(allocation) / parseFloat(qty);

                $(this).parent().parent().find('td:nth-child(5) input.price_pallet_unit').val(price_pallet_unit.toFixed(4));



                const palletized_price = (price_pallet * total_pallet) / palletized;

                $('#palletized_price').val(palletized_price.toFixed(4));

            })



        $.each($('input.gw_weight'), function() {

            const pallet_weight = (parseFloat($('#weight_pallet').val()) * parseFloat($('#palletized').val())) / parseFloat(palletized);

            const gw = parseFloat($(this).parent().parent().find('td:nth-child(4) input.number_box').val()) * parseFloat($(this).parent().parent().find('td:nth-child(2) input.new_weight').val());

            const gw_weight = parseFloat(gw) + (parseFloat($(this).parent().find('input.pallet').val()) * parseFloat(pallet_weight))

            $(this).val(gw_weight.toFixed(3));

        })



        $.each($('input.itf_pallet_price'), function() {

            const palletized_price = parseFloat($('#palletized_price').val());

            const pallet = parseFloat($(this).parent().find('input.pallet').val());

            const itf_pallet_price = pallet * palletized_price;

            $(this).val(itf_pallet_price.toFixed(4));

        })

 total_gw=0;



        $.each($('input.gw_weight'), function() {

            const gw_weight = parseFloat($(this).val());

            total_gw += gw_weight;


        })

        // const weight_pallet = parseFloat($('#weight_pallet').val());

        // const cal_gw = parseFloat(total_gw)+(parseFloat($('#palletized').val())*parseFloat(weight_pallet));

        //$('#span_gw').html(total_gw.toFixed(2));

        $('#total_gw').val(total_gw.toFixed(2));



        $.each($('input.cbm'), function() {

            const cbm = parseFloat($(this).val());

            total_cbm += cbm;

        })

        const cbm_pallet = parseFloat($('#cbm_pallet').val());

        const cal_cbm = parseFloat(total_cbm) + (parseFloat($('#palletized').val()) * parseFloat(cbm_pallet));

        // $('#span_cbm').html(cal_cbm.toFixed(3));

        $('#total_cbm').val(cal_cbm.toFixed(3));

    } else {

        const gw_weight = parseFloat($this.parent().parent().find('td:nth-child(4) input.number_box').val()) * parseFloat($this.parent().parent().find('td:nth-child(2) input.new_weight').val());

        $this.parent().parent().find('td:nth-child(5) input.gw_weight').val(gw_weight.toFixed(2));



        $.each($('input.maxpallet'), function() {

            $(this).parent().parent().find('td:nth-child(5) input.price_allocation').val(palletized);

            $(this).parent().parent().find('td:nth-child(5) input.price_pallet_unit').val(palletized);

            $(this).parent().parent().find('td:nth-child(5) input.itf_pallet_price').val(palletized);

        })


total_gw=0;
        $.each($('input.gw_weight'), function() {

            const gw_weight = parseFloat($(this).val());

            total_gw += gw_weight;

        })

        // $('#span_gw').html(total_gw.toFixed(2));

        $('#total_gw').val(total_gw.toFixed(2));



        $.each($('input.cbm'), function() {

            const cbm = parseFloat($(this).val());

            total_cbm += cbm;

        })

        //   $('#span_cbm').html(total_cbm.toFixed(3));

        $('#total_cbm').val(total_cbm.toFixed(3));

    }




})
function setBlanck($this)
{
    if(!$this.val())
    {
        $this.parent().parent().find('td:nth-child(5) input.gw_weight').val(0);
        $this.parent().parent().find('td:nth-child(5) input.nw').val(0);
        $this.parent().parent().find('td:nth-child(5) input.itf_GW1').val(0);
    }
 
}


function cal()
{
 setcal(1);
    //alert();

setTimeout(function()
{
$.each($('.unitcount'), function()
    {

    $("#"+$(this).attr("id")).change();
    })
},500)
loading(9000);
setTimeout(function()
{
    



    const time = $('#sorted_table tbody tr').length * 1000;

    //loading(time);

    const select_clearance = $('#select_clearance').val();



    let total_nw = $('#total_nw').val();

    let total_gw = $('#total_gw').val();

    let total_cbm = $('#total_cbm').val();

    let palletized = $('#palletized').val();

    let fob = 0;

    let total_all_cost = 0;

    let profit_before_rebate = 0;

    let profit_after_rebate = 0;



    if (select_clearance != '')

    {

        const clearance = parseFloat($('#clearance').val());

        const clearance_price = clearance / total_nw;

        $('#clearance_price').val(clearance_price);

        $.each($('input.itf_clearance_price'), function() {

            const nw = $(this).parent().find('input.nw').val();

            const itf_clearance_price = parseFloat($('#clearance_price').val()) * parseFloat(nw);

            $(this).val(itf_clearance_price.toFixed(5))

        })



        $.ajax({

            url: FULLURL + '/compareTransport',

            method: 'get',

            data: {
                total_nw: total_nw,
                total_cbm: total_cbm,
                select_clearance: select_clearance,
                pallet: palletized
            },

            success: function(data) {



                $('#transport').val(data);
                //alert("transport:" + data)
                const transport = parseFloat($('#transport').val());

                const transport_price = transport / total_nw;

                $('#transport_price').val(transport_price);

                $.each($('input.itf_transport_price'), function() {

                    const itf_transport_price = parseFloat($('#transport_price').val()) * parseFloat($(this).parent().find('input.nw').val());

                    $(this).val(itf_transport_price.toFixed(4))

                    // $(this).val(transport.toFixed(4))

                })




                if ($('#airport').val() != "" && $('#airline').val() != "") {


                    $.ajax({

                        url: FULLURL + '/getRate',

                        method: 'get',

                        data: {
                            total_gw: total_gw,
                            clear: $('#select_clearance').val(),
                            destination: $('#airport').val(),
                            airline: $('#airline').val()
                        },

                        success: function(data) {

                            //////console.log(data);

                            //alert(data.rate);

                            $('#freights').val(data.rate);
                         //   alert("when get  freights is: " + $('#freights').val())

                            $.each($('.itf_freight_rate'), function() {

                                var $this = $(this);

                                const gw = $this.parent().find('input.gw_weight').val();

                                const itf_freight_rate = parseFloat(data.rate) * parseFloat(gw);

                                $this.val(itf_freight_rate.toFixed(4));



                                const total_freight_rate = parseFloat(data.rate) * parseFloat(total_gw);

                                //alert(total_freight_rate);

                                $('#span_freight').text(sudhirComma(total_freight_rate.toFixed(5)));

                                // $('#freights').val(data.rate);

                                $('#total_freight').val(total_freight_rate.toFixed(5));



                                //alert(data.rate)

                            })

                        }

                    })

                } else {

                    alert('กรุณาเลือก Airport และ Airline แล้วกดคำนวณอีกครั้ง / Please select Airport and Airline then press calculate again');

                }



                setTimeout(function() {

                    $.each($('.total_itf_cost'), function() {

                        var $this = $(this);




                        const pallet_price = $this.parent().find('input.itf_pallet_price').val();

                        const clearance_price = $this.parent().find('input.itf_clearance_price').val();

                        const transport_price = $this.parent().find('input.itf_transport_price').val();

                        const cost_price = $this.parent().find('input.itf_cost_price').val();

                        const freight_rate = $this.parent().find('input.itf_freight_rate').val();

                        if (pallet_price != "" && clearance_price != "" && transport_price != "" && cost_price != "" && freight_rate != "") {

                            const total_itf_cost = parseFloat(pallet_price) + parseFloat(clearance_price) + parseFloat(transport_price) + parseFloat(cost_price) + parseFloat(freight_rate);

                            $this.val(total_itf_cost.toFixed(5));

                        }

                    })

                }, 700)

                setTimeout(function() {

                    if ($('.total_itf_cost').val() != '') {

                        $.each($('.unit_price'), function() {

                            var $this = $(this);

                            const total_itf_cost = parseFloat($this.parent().parent().find('td:nth-child(5) input.total_itf_cost').val());

                            const qty = parseFloat($this.parent().parent().find('td:nth-child(2) input.qty').val());

                            const unit_price = total_itf_cost / qty;

                            const markup_rate = parseFloat($('#markup_rate').val())

                            const ex_rate = parseFloat($('#ex_rate').val())

                            if (markup_rate >= 0 && ex_rate >= 0) {

                                const cal = (1 + ((0.0198 * (Math.pow(markup_rate, 2)) + (0.7901 * markup_rate) + 1.26) / 100)) * unit_price / ex_rate;

                               // $this.val(cal.toFixed(5));

                            } else {

                                alert('กรุณาเลือก Currency และ EX Rate และ Markup Rate แล้วกดคำนวณอีกครั้ง / Please select Currency and EX Rate and Markup Rate then press calculate again');

                            }



                        })

                    }

                }, 1000)

                setTimeout(function() {

                    if ($('.unit_price').val() != '') {

                        $.each($('.profit'), function() {

                            var $this = $(this);

                            const qty = parseFloat($this.parent().parent().find('td:nth-child(2) input.qty').val());

                            const total_itf_cost = parseFloat($this.parent().parent().find('td:nth-child(5) input.total_itf_cost').val());

                            const itf_freight_rate = parseFloat($this.parent().parent().find('td:nth-child(5) input.itf_freight_rate').val());

                            const unit_price = parseFloat($this.parent().parent().find('td:nth-child(6) input.unit_price').val());

                            const ex_rate = parseFloat($('#ex_rate').val())



                            const profit = ((unit_price * ex_rate * qty) - total_itf_cost) / total_itf_cost * 100;

                            //$this.val(profit.toFixed(5));

                            const fob_th = (unit_price * ex_rate * qty) - itf_freight_rate;

                            $this.parent().find('input.fob').val(fob_th.toFixed(5));

                        })

                    }

                }, 8000)



                setTimeout(function()

                    {

                        if ($('.unit_price').val() != '')

                        {

                            $.each($('.unit_price'), function()

                                {

                                    var $this = $(this);

                                    const unit_price = parseFloat($this.val());

                                    const qty = parseFloat($this.parent().parent().find('td:nth-child(2) input.qty').val());

                                    const ex_rate = parseFloat($('#ex_rate').val())

                                    fob += unit_price * ex_rate * qty;




                                })

                            const total_fob = fob - $('#total_freight').val();

                            $('#span_fob').text(sudhirComma(total_fob.toFixed(5)));

                            $('#total_fob').val(total_fob.toFixed(5));

                            $.each($('.total_itf_cost'), function() {

                                var $this = $(this);

                                const total_itf_cost = parseFloat($this.val());

                                total_all_cost += total_itf_cost;

                            })

                            profit_before_rebate = fob - total_all_cost;

                            $('#span_pro_before_rebate').text(sudhirComma(profit_before_rebate.toFixed(2)));

                            $('#total_pro_before_rebate').val(profit_before_rebate.toFixed(2));

                        }

                    }, 1300)



                setTimeout(function() {

                    if ($('#total_pro_before_rebate').val() != '') {

                        const rebate = parseFloat($('#rebate').val());

                        if ($('#rebate').val() != '') {

                            profit_after_rebate = profit_before_rebate - (rebate / 100 * fob);

                            $('#span_pro_after_rebate').text(sudhirComma(profit_after_rebate.toFixed(2)));

                            $('#total_pro_after_rebate').val(profit_after_rebate.toFixed(2));

                            const profit_percent = (profit_after_rebate / total_all_cost) * 100;

                            $('#span_pro_percent').text(sudhirComma(profit_percent.toFixed(5)));

                            $('#total_pro_percent').val(profit_percent.toFixed(5));

                        } else {

                            alert('กรุณากรอก Rebate แล้วกดคำนวณอีกครั้ง / Please enter Rebate then press calculate again')

                        }

                    }

                    $.ajax({

                        url: FULLURL + '/getRate',

                        method: 'get',

                        data: {
                            total_gw: total_gw,
                            clear: $('#select_clearance').val(),
                            destination: $('#airport').val(),
                            airline: $('#airline').val()
                        },

                        success: function(data) {

                            if (data.min > total_gw) {

                                alert("min GW requirment not met. please increase order to min GW of " + total_gw + ' to more than ' + data.min);

                            }

                        }

                    })

                }, 1500)




                $("#Tbl tbody").html(" ");

                var ok = 1;
  var nm = $(".c-avatar").text();
                $.each($('.unitcount'), function()

                    {


                        var $this = $(this);

                        if (!$(this).val()) {
                            ok = 2;
                            alert("Dear " + nm + " please choose UNIT!");
                            $(this).focus();
                            return false;

                        }
                        let number_box = parseFloat($this.parent().parent().find('td:nth-child(4) input.number_box').val());;


                        if (number_box > 0) {
                            var res1 = number_box.toString();
                            var res = res1.split(".");
                            var dec = parseFloat(res[1]);
                            //alert(dec);
                            if (dec > 0) {
                                ok = 2;
                              
                                alert("Dear " + nm + " your number of boxes are " + number_box + ", please adjust your quantity to complete full box");
                                $this.parent().parent().find('td:nth-child(2) input.qty').focus();
                                return false;
                            }


                        } else {
                            if (res) {
                                ok = 2;
                                var nm = $(".c-avatar").text();
                                alert("Dear " + nm + " your number of boxes are " + number_box + ", please adjust your quantity to complete full box");
                                $this.parent().parent().find('td:nth-child(2) input.qty').focus();
                                return false;
                            }

                        }




                        let qty = parseFloat($this.parent().parent().find('td:nth-child(2) input.qty').val());




                        let itfUnit = $(this).val();;



                        let nw = parseFloat($this.parent().parent().find('td:nth-child(5) input.nw').val());;



                        let unit_price = parseFloat($this.parent().parent().find('td:nth-child(6) input.unit_price').val()).toFixed(3);;

                        let profitP = parseFloat($this.parent().parent().find('td:nth-child(5) input.profitP').val());;

                        let cost = parseFloat($this.parent().parent().find('td:nth-child(5) input.itf_cost_price').val());;

                        let itf_cal_selling = parseFloat($this.parent().parent().find('td:nth-child(5) input.itf_cal_selling').val());;



                        let gw_weight = parseFloat($this.parent().parent().find('td:nth-child(5) input.gw_weight').val());;



                        let itf_CBM = $this.parent().parent().find('td:nth-child(5) input.itf_CBM').val();

                        let itf_freight_rate = parseFloat($this.parent().parent().find('td:nth-child(5) input.itf_freight_rate').val());;

                        let fob = 0;

                        let rebat = 0;



                        let itf_fx_price = parseFloat($this.parent().parent().find('td:nth-child(5) input.itf_fx_price').val()).toFixed(2);

                        let total_cost = parseFloat($this.parent().parent().find('td:nth-child(5) input.total_cost').val()).toFixed(5);

                        let ean_cost = parseFloat($this.parent().parent().find('td:nth-child(5) input.ean_cost').val()).toFixed(5);



                        let itf_GW1 = parseFloat($this.parent().parent().find('td:nth-child(5) input.itf_GW1').val()).toFixed(2);
                        let fixPrice = parseFloat($this.parent().parent().find('td:nth-child(5) input.fixPrice').val()).toFixed(2);



                        // let fixPrice=parseFloat(unit_price/parseFloat($("#ex_rate").val())).toFixed(2);



                        let iclearance = parseFloat($this.parent().parent().find('td:nth-child(5) input.iclearance').val());

                        let ichamber = parseFloat($this.parent().parent().find('td:nth-child(5) input.ichamber').val());

                        let itruck = parseFloat($this.parent().parent().find('td:nth-child(5) input.itruck').val());

                        let ipallets = parseFloat($this.parent().parent().find('td:nth-child(5) input.ipallets').val());

                        let ifreight = parseFloat($this.parent().parent().find('td:nth-child(5) input.ifreight').val());



                        let itf_fob = $this.parent().parent().find('td:nth-child(5) input.itf_fob').val();

                        let itf_freight_1 = Math.round(parseFloat(ifreight) * parseFloat(number_box));
                        let itf_freight_ = parseFloat(itf_freight_1).toFixed(5);
                        var EX_rate = parseFloat($("#ex_rate").val())
                        var rebat1 = parseFloat($("#rebate").val())


                        //  itf_fob=(parseFloat(itf_fx_price)*parseFloat(number_box)*parseFloat(EX_rate))-parseFloat(itf_freight_); client 
                        itf_fob = Math.round(parseFloat((parseFloat(fixPrice) * parseFloat(qty) * parseFloat(EX_rate)) - parseFloat(itf_freight_))).toFixed(5);


                        //alert("fixPrice :"+fixPrice+" qty:"+qty+" EX_rate: "+EX_rate+" itf_freight_: "+itf_freight_+" itf_fob:"+itf_fob);




                        //  let profit2= $this.parent().parent().find('td:nth-child(5) input.profit2').val();


                        //alert("fixPrice: "+fixPrice+"  ex_rate: "+EX_rate+" qty:"+qty+" red freight:"+ifreight+" box:"+number_box+" itf_fob:"+itf_fob)

                        let rebate = parseFloat($('#rebate').val())

                        var tcost = parseFloat(total_cost) + parseFloat(iclearance) + parseFloat(itruck) + parseFloat(ipallets) + parseFloat(ifreight);


                        let profit2 = (parseFloat(itf_fob) + parseFloat(itf_freight_)) - (parseFloat(tcost) * parseFloat(number_box));
                        // var tcost=parseFloat(total_cost)+parseFloat(iclearance);

                        //  alert("clearance:"+iclearance+" Ean Cost:"+total_cost+" truck:"+itruck+" pallets:"+ipallets+" freight:"+ifreight+" total cost"+tcost)



                        profit2 = profit2.toFixed(5)




                        //alert(ifreight+"  "+number_box)

                        //profit2=(parseFloat(itf_fob)+parseFloat(itf_freight_))-(parseFloat(tcost)*parseFloat(number_box));
                        // ((V4*$B$8)*(1-B7)-P4)/Q4

                        profitP = ((parseFloat(itf_fx_price) * parseFloat(EX_rate)) * (1 - parseFloat(rebat1) / 100) - parseFloat(tcost)) / parseFloat(itf_cal_selling);


                        // let price_pr=((itf_fx_price*parseFloat($("#ex_rate").val()))-total_cost)/(fob+itf_freight_rate)/box;


                        profitP = parseFloat(profitP * 100).toFixed(2);




                        profitP = profitP + "%";


                        setTimeout(function() {
                            $this.parent().parent().find('td:nth-child(6) input.unit_price').val(fixPrice);

                            $this.parent().parent().find('td:nth-child(7) input.profit').val(profitP);

                            $this.parent().parent().find('td:nth-child(5) input.itf_freight').val(itf_freight_);
                            $this.parent().parent().find('td:nth-child(5) input.itf_fob').val(itf_fob);
                            //alert(fixPrice)
                        }, 1200)
                       // alert(fixPrice)
                  


                       // $this.parent().parent().find('td:nth-child(6) input.unit_price').val(fixPrice);
                       /* $this.parent().parent().find('td:nth-child(5) input.profit').val(profitP);*/
                        let red = "EAN_Cost:" + total_cost + " <br> clearance:" + iclearance + " <br> chamber:" + ichamber + " <br> truck:" + itruck + " <br> pallets:" + ipallets + " <br> ifreight:" + ifreight + " <br> Total_cost:" + tcost + " <br> itf_cal_selling:" + itf_cal_selling;



                        //rebat=parseFloat((parseFloat(rebat1)*parseFloat(number_box)*parseFloat(itf_fx_price)*parseFloat(EX_rate))/100).toFixed(2);
                        rebat = parseFloat((parseFloat(itf_fob) + parseFloat(itf_freight_)) * parseFloat(rebate) / 100).toFixed(5);



                        let ared = "itf_fob:" + Math.round(itf_fob).toFixed(5) + " <br> freight_:" + itf_freight_ + " <br> Profit:" + profit2 + " <br> Rebate: " + rebat + " <br> ITF FX Price:" + itf_fx_price;




                        //alert("unit_price:"+unit_price+" ex_rate:"+$("#ex_rate").val())

                        //  $this.parent().parent().find('td:nth-child(5) input.fixPrice').val(fixPrice);;



                        let str = "<tr><td>" + qty + "</td><td>" + itfUnit + "</td><td>" + number_box + "</td><td>" + nw + "</td><td>" + gw_weight + "</td><td>" + itf_CBM + "</td><td>" + fixPrice + "</td><td>" + profitP + "</td><td>" + red + "</td><td>" + ared + "</td>";




                        $("#Tbl tbody").append(str);




                    })

                if (ok == 2) {
                    return false;
                }




                let total_box = 0;

                let total_gws = 0;

                let itf_CBM = 0;

                let titem = 0;
                let nww = 0;

                setTimeout(function() {
                    $.each($('input.number_box'), function() {



                        total_box += parseFloat($(this).val());

                        titem += 1;

                    })


                    $("#span_box").text(sudhirComma(total_box));
                    $("#span_Item").text(sudhirComma(titem));


                    $.each($('input.nw'), function() {



                        nww += parseFloat($(this).val());



                    })


                    $("#span_nw").text(sudhirComma(nww));

                    $.each($('input.itf_GW1'), function() {



                        total_gws += parseFloat($(this).val());

                    })



                    $.each($('input.itf_CBM'), function() {



                        itf_CBM += parseFloat($(this).val());

                    })

                    var itf_freight_t = 0;

                    $.each($('input.itf_freight'), function() {



                        itf_freight_t += parseFloat($(this).val());

                    })


                    $("#span_freight").text(sudhirComma(itf_freight_t.toFixed(5)));




                    var itf_fob = 0;

                    $.each($('input.itf_fob'), function() {



                        itf_fob += parseFloat($(this).val());

                    })


                    $("#span_fob").text(sudhirComma(itf_fob.toFixed(5)));

                    var span_fcnft = parseFloat(itf_fob) + parseFloat(itf_freight_t);
                    $("#span_fcnft").text(sudhirComma(span_fcnft.toFixed(2)));


                



                   



                    var profit2 = 0;

                    $.each($('input.profit2'), function() {



                        profit2 += parseFloat($(this).val());

                    })


                    $("#span_pro_before_rebate").text(sudhirComma(profit2.toFixed(2)));



                    let rebateX = parseFloat($('#rebate').val());

                    let span_pro_after_rebate = parseFloat(profit2) - (parseFloat(span_fcnft) * parseFloat(rebateX) / 100);

                    //alert("profit2: "+profit2+"  span_fcnft: "+span_fcnft+" rebate: "+rebateX);
                    $("#span_pro_after_rebate").text(sudhirComma(span_pro_after_rebate.toFixed(2)));

                    var span_pro_percent = parseFloat(span_pro_after_rebate) / parseFloat(span_fcnft) * 100;
                    $("#span_pro_percent").text(sudhirComma(span_pro_percent.toFixed(2)));

                }, 1900)

                let tfrt = 0;

                let tfob = 0;

                let tcnf = 0;

                let pbr = 0;

                let par = 0;

                let profit = 0;



                $(".titem").text(sudhirComma(titem));

                $(".tbox").text(sudhirComma(total_box));

                $(".tgw").text(sudhirComma(total_gws));

                $(".tcbm").text(sudhirComma(itf_CBM));

                $(".tfrt").text(sudhirComma(tfrt));

                $(".tfob").text(sudhirComma(tfob));

                $(".tcnf").text(sudhirComma(tcnf));

                $(".pbr").text(sudhirComma(pbr));

                $(".par").text(sudhirComma(par));

               // $(".profit").text(sudhirComma(profit));



                $("#myBtn").click();



            }

        })

    } else

    {

        alert('กรุณาเลือก Clearance แล้วกดคำนวณอีกครั้ง / Please select Clearance then press calculate again')

    }


},8000)

}



$(document).on('change', '.unit_price', function() {

    var $this = $(this);

    const qty = parseFloat($this.parent().parent().find('td:nth-child(2) input.qty').val());



    const total_itf_cost = parseFloat($this.parent().parent().find('td:nth-child(5) input.total_itf_cost').val());

    const itf_freight_rate = parseFloat($this.parent().parent().find('td:nth-child(5) input.itf_freight_rate').val());

    const unit_price = parseFloat($this.val());

    const ex_rate = parseFloat($('#ex_rate').val())

    let rebate = parseFloat($('#rebate').val());

    const profit = ((unit_price * ex_rate * qty) - total_itf_cost) / total_itf_cost * 100;

    var markup_rateCall = ((0.0198 * (markup_rate * markup_rate)) + (0.7901 * markup_rate) + 1.34) / 100;

    $("#markup_rateCal").val(markup_rateCall.toFixed(4));



    //alert(markup_rateCal)

   // $this.parent().parent().find('td:nth-child(7) input.profit').val(profit.toFixed(2));




    const fob_th = (unit_price * ex_rate * qty) - itf_freight_rate;

    $this.parent().parent().find('td:nth-child(7) input.fob').val(fob_th.toFixed(4));



    let fob = 0;

    let total_all_cost = 0;

    let profit_before_rebate = 0;

    let profit_after_rebate = 0;

    setTimeout(function() {

        $.each($('.unit_price'), function() {

            var $this = $(this);

            const unit_price = parseFloat($this.val());

            const qty = parseFloat($this.parent().parent().find('td:nth-child(2) input.qty').val());

            const ex_rate = parseFloat($('#ex_rate').val())

            fob += unit_price * ex_rate * qty;

        })

        const total_fob = fob - $('#total_freight').val();

        $('#span_fob').text(sudhirComma(total_fob.toFixed(2)));

        $('#total_fob').val(total_fob.toFixed(2));

        $.each($('.total_itf_cost'), function() {

            var $this = $(this);

            const total_itf_cost = parseFloat($this.val());

            total_all_cost += total_itf_cost;

        })

        profit_before_rebate = fob - total_all_cost;

        $('#span_pro_before_rebate').text(sudhirComma(profit_before_rebate.toFixed(2)));

        $('#total_pro_before_rebate').val(profit_before_rebate.toFixed(2));

    }, 1000)

    setTimeout(function() {

        if ($('#total_pro_before_rebate').val() != '') {

            const rebate = parseFloat($('#rebate').val());

            if ($('#rebate').val() != '') {

                profit_after_rebate = profit_before_rebate - (rebate / 100 * fob);

                $('#span_pro_after_rebate').text(sudhirComma(profit_after_rebate.toFixed(2)));

                $('#total_pro_after_rebate').val(profit_after_rebate.toFixed(2));

                const profit_percent = profit_after_rebate / total_all_cost * 100;

                $('#span_pro_percent').text(sudhirComma(profit_percent.toFixed(2)));

                $('#total_pro_percent').val(profit_percent.toFixed(2));

            } else {

                alert('กรุณากรอก Rebate แล้วกดคำนวณอีกครั้ง / Please enter Rebate then press calculate again')

            }

        }

    }, 1200)

})



function sum_array(input) {

    if (toString.call(input) !== "[object Array]")

        return false;



    var total = 0;

    for (var i = 0; i < input.length; i++)

    {

        if (isNaN(input[i])) {

            continue;

        }

        total += Number(input[i]);

    }

    return total;

}



function loading(time) {

    swal.fire({

        title: 'Now loading',

        allowEscapeKey: false,

        allowOutsideClick: false,

        timer: time,

        onOpen: () => {

            swal.showLoading();

        }

    })

}




function cal_pallet(n)

{



    if (n == 'yes')

    {

        let palletized = 0;



        $.each($('input.pallet'), function()

            {

                let pallet = parseFloat($(this).val());

                palletized += pallet;



                //alert(palletized);

                let one = pallet / palletized;

                $('#palletized').val(Math.ceil(palletized));

                $('#span_palletized').html(Math.ceil(palletized));

                const total_pallet = parseFloat($('#palletized').val());

                const price_pallet = parseFloat($('#price_pallet').val());

                const two = total_pallet * price_pallet;

                const price_allocation = parseFloat(one) * parseFloat(two);

                $(this).parent().parent().find('td:nth-child(5) input.price_allocation').val(Math.ceil(price_allocation));

                const qty = $(this).parent().parent().find('td:nth-child(2) input.qty').val();

                const allocation = $(this).parent().parent().find('td:nth-child(5) input.price_allocation').val();

                const price_pallet_unit = parseFloat(allocation) / parseFloat(qty);

                $(this).parent().parent().find('td:nth-child(5) input.price_pallet_unit').val(price_pallet_unit.toFixed(4));



                let palletized_price = (price_pallet * total_pallet) / palletized;

                $('#palletized_price').val(palletized_price.toFixed(4));

            })




        let select_palletized = $('#select_pallet').val();

        let select_clearance = $('#select_clearance').val();



        $.each($('input.gw_weight'), function()

            {

                let pallet_weight = (parseFloat($('#weight_pallet').val()) * parseFloat($('#palletized').val())) / parseFloat(palletized);

                let gw = parseFloat($(this).parent().parent().find('td:nth-child(4) input.number_box').val()) * parseFloat($(this).parent().parent().find('td:nth-child(2) input.new_weight').val());

                let gw_weight = parseFloat(gw) + (parseFloat($(this).parent().find('input.pallet').val()) * parseFloat(pallet_weight))

                $(this).val(gw_weight.toFixed(2));



                $(this).parent().parent().find('td:nth-child(5) input.itf_GW').val(gw.toFixed(2));



            })

    }



}




function getPalatass()

{

    const time = $('#sorted_table tbody tr').length * 1000;

    loading(time);

    const select_clearance = $('#select_clearance').val();

    let total_nw = $('#total_nw').val();

    let total_gw = $('#total_gw').val();

    let total_cbm = $('#total_cbm').val();

    let palletized = $('#palletized').val();

    let fob = 0;

    let total_all_cost = 0;

    let profit_before_rebate = 0;

    let profit_after_rebate = 0;



    if (select_clearance != '')

    {

        const clearance = parseFloat($('#clearance').val());

        const clearance_price = clearance / total_nw;

        $('#clearance_price').val(clearance_price);

        $.each($('input.itf_clearance_price'), function() {

            const nw = $(this).parent().find('input.nw').val();

            const itf_clearance_price = parseFloat($('#clearance_price').val()) * parseFloat(nw);

            $(this).val(itf_clearance_price.toFixed(4))

        })



        $.ajax({

            url: FULLURL + '/compareTransport',

            method: 'get',

            data: {
                total_nw: total_nw,
                total_cbm: total_cbm,
                select_clearance: select_clearance,
                pallet: palletized
            },

            success: function(data) {



                $('#transport').val(data);

                const transport = parseFloat($('#transport').val());

                const transport_price = transport / total_nw;

                $('#transport_price').val(transport_price);

                $.each($('input.itf_transport_price'), function() {

                    const itf_transport_price = parseFloat($('#transport_price').val()) * parseFloat($(this).parent().find('input.nw').val());

                    $(this).val(itf_transport_price.toFixed(4))

                    // $(this).val(transport.toFixed(4))

                })

            }

        })

    }

}



function getFreights(total_gw) {
    $.ajax({
        url: FULLURL + '/getRate',

        method: 'get',

        data: {
            total_gw: total_gw,
            clear: $('#select_clearance').val(),
            destination: $('#airport').val(),
            airline: $('#airline').val()
        },

        success: function(data) {
            $('#freights').val(data.rate);
           // alert("when get  freights is: " + $('#freights').val())

            $.each($('.itf_freight_rate'), function() {

                var $this = $(this);

                const gw = $this.parent().find('input.gw_weight').val();

                const itf_freight_rate = parseFloat(data.rate) * parseFloat(gw);

                $this.val(itf_freight_rate.toFixed(4));



                const total_freight_rate = parseFloat(data.rate) * parseFloat(total_gw);

                //alert(total_freight_rate);

                $('#span_freight').text(sudhirComma(total_freight_rate.toFixed(5)));

                // $('#freights').val(data.rate);

                $('#total_freight').val(total_freight_rate.toFixed(5));



                //alert(data.rate)

            })

        }

    })
}