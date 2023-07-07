var fullUrl = window.location.origin;

var FULLURL = "invoice";
var lists=0;
var lists1=0;
function setcal(isCallables) {
    
    $("#isCallables").val(isCallables);
}
var cnts=0;;
function changC(cntss)
{
    cnts=cntss;
}



function myFunction(th) {


    $(".buttom_vl").text(" ")
    $("#sorted_table .unit_price").val("")
    $("#sorted_table .profit").val("")
   // $('#unitcount' + th).prop('selectedIndex', 0);
     setcal(0);
     if($('#unitcount'+th).val())
     {
       setTimeout(function()
       {
        $('.unitcount').change();  
       },200)
      //$('.unitcount').change();  
     }

   //alert($('#unitcount'+th).val())
    


    
}


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




$(document).on('change', '#client', function() {

    var $this = $(this);

    const id = $this.val();

    $.get(FULLURL + '/getShip?id=' + id)

        .done(function(data) {



            $('#shipto').empty();

            $('#shipto').append(data);

        })

    $.get(FULLURL + '/getAir?shipto_id=')

        .done(function(data) {



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

            // //////console.log(data)

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

        $('#shipto').attr('readonly', true);

        $.get(FULLURL + '/getAir?airport_id=' + id)

            .done(function(data) {

                $('#airline').empty();

                $('#airline').append(data.airline);

            })

    } else {

        $('#shipto').attr('readonly', false);

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

            total_gw = 0;

            $.each($('input.gw_weight'), function() {

                const gw_weight = parseFloat($(this).val());

                total_gw += gw_weight;

            })



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


            total_gw = 0;
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


    } else {

        if ($this.val() != '')

            $.get(FULLURL + '/getVal?id=' + id)

            .done(function(data) {

            


            })

    }


})



function recalculation() 
{
     $(".unitcount").change(); 
    if ($('.unit_price').val() == "") 
    {
        return false;
       
    }
    else
    {
        
        setTimeout(function() 
        {
            cal('2');
        },2500)
    }


   

}




$(document).on('change', '.unitcount', function() 
{


    if ($('#airport').val() == "" || $('#airline').val() == "") 
    {
        $('.unitcount').prop('selectedIndex', 0);
        alert('Please select Airport and Airline');
        return false;
    }

    /* start point of calculation */

    var $this = $(this);
    setBlanck($this);
    /*var qty = $this.parent().parent().find('td:nth-child(2) input.qty').val();*/
    var qty;

    const itf_id = $this.parent().parent().find('td:nth-child(1) select.select_itf').val();

    const id = $this.val();
    ////console.log(id+" unt");
    const ean_id = $this.parent().parent().find('td:nth-child(2) input.ean_id').val().split(',');

    const count_ean_id = ean_id.length;

    const ean_qty = $this.parent().parent().find('td:nth-child(2) input.ean_qty').val();
    const itfQty = parseFloat($this.parent().parent().find('td:nth-child(2) input.itfQty').val());
    const number_box = parseFloat($this.parent().parent().find('td:nth-child(4) input.number_box').val());
    const box_of_order = parseFloat($this.parent().parent().find('input.box_of_order').val());

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

 

   qty=$this.parent().parent().find('td:nth-child(2) input.qty').val();
   box =number_box;
   $this.parent().parent().find('td:nth-child(2) input.qty').val(qty);

//alert("number_box:"+number_box+" net_weightNew:"+net_weightNew+" itfQty:"+itfQty+" qty:"+qty)

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

    // $this.parent().parent().find('td:nth-child(5) input.nw').val(nw.toFixed(2));



    const cbm = parseFloat(box) * parseFloat(maxcbm);
    let itf_CBM = cbm;



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

        var isCallables = $("#isCallables").val();

        if (isCallables == 0) {
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

               console.log(data);

                $.each(data.rs, function(k, v) {

                    if (v == 'true' && data.rs_re[k] == 'true') 
                    {

                        //////console.log(data);

                        if (data.count[k] != 0) {

                            $.get(FULLURL + '/getCost?id=' + itf_id + '&num_box=' + num_box)

                                .done(function(data) {


                                    $this.parent().parent().find('td:nth-child(5) input.itf_cost_price').val(data.cost.toFixed(4));

                                    $this.parent().parent().find('td:nth-child(5) input.pack_cost').val(data.pack_cost.toFixed(4));

                                    $this.parent().parent().find('td:nth-child(5) input.ean_cost').val(data.ean_cost.toFixed(4));

                                    $this.parent().parent().find('td:nth-child(5) input.total_cost').val(data.total_cost.toFixed(4));




                                    let itf_nw = parseFloat($this.parent().parent().find('td:nth-child(5) input.nw').val());;




                                    let unitPrice;

                                    let fixPrice;

                                    let of_pallats = 0;

                                    let total_pallat_weight = 0;

                                    let itfUnit = id;




                                    let box_pallet = $this.parent().parent().find('td:nth-child(5) input.box_pallet').val();



                                    let select_chamber = $('#select_chamber').val();




                                    let total_pallet = 0;
                                    let complete_pallet = 0;



                                    if (select_palletized == 'yes') 
                                    {
                                        complete_pallet=parseFloat($("#complete_pallet").val());
                                        total_pallet=parseFloat($("#total_pallet").val());
                                    }

                                    // $("#total_pallet").val(total_pallet);


                                    var price_pallet = parseFloat($("#price_pallet").val());

                                  




                                    $("#total_pallet_cost").val(complete_pallet * price_pallet);
                                   // alert("complete_pallet:"+complete_pallet+" price_pallet:"+price_pallet+" complete_pallet * price_pallet:"+complete_pallet * price_pallet)



                                    let total_box = 0;

                                    $.each($('input.number_box'), function() {

                                        if (!isNaN(parseFloat($(this).val()))) {
                                            total_box += parseFloat($(this).val());
                                        }


                                    })




                                    total_pallat_weight = parseFloat($("#total_pallet_weight").val());


                                    let box_weight = $this.parent().parent().find('td:nth-child(5) input.box_weight').val();




                                    let total_weight = total_pallat_weight + parseFloat(box_weight);



                                   // $this.parent().parent().find('td:nth-child(5) input.total_pallat_weight').val(parseFloat(total_pallat_weight));




                                   // $this.parent().parent().find('td:nth-child(5) input.total_weight').val(parseFloat(total_weight).toFixed(3));



                                   // let itf_GW = parseFloat(box) * parseFloat(total_weight);



                                    // $this.parent().parent().find('td:nth-child(5) input.itf_GW').val(parseFloat(itf_GW).toFixed(3));

                                    var titem = 0;

                                    var span_gw = 0;
                                    $.each($('input.gw_weight'), function() 
                                    {


                                        if (!isNaN(parseFloat($(this).val()))) 
                                        {
                                            span_gw += parseFloat($(this).val());
                                        }
                                        titem += 1;


                                    })


                                    
                                        $("#span_gw").text(sudhirComma(span_gw.toFixed(3)));
                                    




                                    var span_box = 0;

                                    $.each($('input.number_box'), function() {

                                        if (!isNaN(parseFloat($(this).val()))) {
                                            span_box += parseFloat($(this).val());


                                        }



                                    })


                                    $("#span_box").text(sudhirComma(span_box));
                                    $("#span_Item").text(sudhirComma(titem));

                                    var nww = 0;
                                    $.each($('input.nw'), function() {

                                        if (!isNaN(parseFloat($(this).val()))) {
                                            nww += parseFloat($(this).val());
                                        }




                                    })


                                    $("#span_nw").text(sudhirComma(nww));




                                    var span_cbm = 0;

                                    $.each($('input.itf_CBM'), function() {
                                        if (!isNaN(parseFloat($(this).val()))) {
                                            span_cbm += parseFloat($(this).val());
                                        }




                                    })

                                    var k15 = parseFloat($("#cbm_pallet").val());
                                    if ($('#select_pallet').val() == 'yes') {

                                        var cmplt = 0;

                                        cmplt = $('#complete_pallet').val();


                                        //$('#span_palletized').html(cmplt);

                                        $("#span_cbm").text(sudhirComma((parseFloat(span_cbm) + parseFloat(k15) * complete_pallet).toFixed(3)));
                                    } else {

                                        $("#span_cbm").text(sudhirComma(parseFloat(span_cbm).toFixed(3)));
                                    }




                                    let iclearance = 0

                                    let totaNW = 0;

                                    $.each($('input.nw'), function() {

                                        if (!isNaN(parseFloat($(this).val()))) {

                                            totaNW += parseFloat($(this).val());
                                        }

                                    })




                                    let tPrice = 0;;




                                    const total_pallet_cost = parseFloat($('#total_pallet_cost').val());




                                    var total_gw = 0;

                                    $.each($('input.gw_weight'), function() {

                                        if (!isNaN(parseFloat($(this).val()))) {
                                            let gw_weight = parseFloat($(this).val());

                                            total_gw += gw_weight;
                                        }


                                    })
 // alert(" change"+cnts)
                                    if(cnts==1)
                                    {
                                      changC(0); 

                                      $(".unitcount").change(); 
                                    }
                                    


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
                        /* coded and commented by sudhir*/
                         if (data.count[k] == 1 && parseFloat(data.sum[k]) == 0) 

                        {

                            alert('ไม่มีสินค้าที่แพ็คแล้ว / No products have in stock.')

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



                       console.log(data.count_re);
                        $.each(data.count_re, function(key, val) 
                        {

                            if (val == 0 && parseFloat(data.sum_re[key]) == 0) {

                                  loading(1);
                                     setTimeout(function()
                                    {
                                        alert('ไม่มีส่วนประกอบ / No product setup.')
                                    },200)

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
                    var titm= $("#total_item").val();
                    lists1++;
                    if(lists1==titm)
                    {


                        setTimeout(function()
                        {

                            //calNow('0',3); /// after loading calculate
                            cal(0); /// after loading calculate

                        },100)
                    }
                })
    
            }

        })

        $.each($('input.number_box'), function() {

            const box = parseFloat($(this).val());

            total_box += box;

        })



        $('#total_box').val(total_box);



        $.each($('input.nw'), function() {

            const nw = parseFloat($(this).val());

            total_nw += nw;

        })



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

            // const gw = parseFloat($(this).parent().parent().find('td:nth-child(4) input.number_box').val()) * parseFloat($(this).parent().parent().find('td:nth-child(2) input.new_weight').val());

            // const gw_weight = parseFloat(gw) + (parseFloat($(this).parent().find('input.pallet').val()) * parseFloat(pallet_weight))

            // $(this).val(gw_weight.toFixed(3));

        })



        $.each($('input.itf_pallet_price'), function() {

            const palletized_price = parseFloat($('#palletized_price').val());

            const pallet = parseFloat($(this).parent().find('input.pallet').val());

            const itf_pallet_price = pallet * palletized_price;

            $(this).val(itf_pallet_price.toFixed(4));

        })

        total_gw = 0;



        $.each($('input.gw_weight'), function() {

            const gw_weight = parseFloat($(this).val());

            total_gw += gw_weight;


        })



        $('#total_gw').val(total_gw.toFixed(2));



        $.each($('input.cbm'), function() {

            const cbm = parseFloat($(this).val());

            total_cbm += cbm;

        })

        const cbm_pallet = parseFloat($('#cbm_pallet').val());

        const cal_cbm = parseFloat(total_cbm) + (parseFloat($('#palletized').val()) * parseFloat(cbm_pallet));



        $('#total_cbm').val(cal_cbm.toFixed(3));

    } else {

       // const gw_weight = parseFloat($this.parent().parent().find('td:nth-child(4) input.number_box').val()) * parseFloat($this.parent().parent().find('td:nth-child(2) input.new_weight').val());
// alert(gw_weight)
        //$this.parent().parent().find('td:nth-child(5) input.gw_weight').val(gw_weight.toFixed(2));



        $.each($('input.maxpallet'), function() {

            $(this).parent().parent().find('td:nth-child(5) input.price_allocation').val(palletized);

            $(this).parent().parent().find('td:nth-child(5) input.price_pallet_unit').val(palletized);

            $(this).parent().parent().find('td:nth-child(5) input.itf_pallet_price').val(palletized);

        })


        total_gw = 0;
        $.each($('input.gw_weight'), function() {

            const gw_weight = parseFloat($(this).val());

            total_gw += gw_weight;

        })



        $('#total_gw').val(total_gw.toFixed(2));



        $.each($('input.cbm'), function() {

            const cbm = parseFloat($(this).val());

            total_cbm += cbm;

        })



        $('#total_cbm').val(total_cbm.toFixed(3));

    }




})

function setBlanck($this) {
    if (!$this.val()) {
        $this.parent().parent().find('td:nth-child(5) input.gw_weight').val(0);
        $this.parent().parent().find('td:nth-child(5) input.nw').val(0);
        $this.parent().parent().find('td:nth-child(5) input.itf_GW').val(0);
    }

}


function cal(x = '0') 
{

//alert("ok0")

    const select_clearance = $('#select_clearance').val();



    let total_nw = $('#total_nw').val();

    let total_gw = $('#total_gw').val();

    let total_cbm = $('#total_cbm').val();

    let palletized = $('#palletized').val();

    let fob = 0;

    let total_all_cost = 0;

    let profit_before_rebate = 0;

    let profit_after_rebate = 0;
    let totalRow = parseFloat($("#span_Item").text());

    var cntRow = 0;



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

            success: function(data)
             {
                
                 if(data==='na')
                {  
                    setTimeout(function() {alert("Please check Transport Clearance -cal")},1000)
                    return false;
                }


                if ($('#airport').val()== "" && $('#airline').val()== "") 
                {
                    alert('กรุณาเลือก Airport และ Airline แล้วกดคำนวณอีกครั้ง / Please select Airport and Airline then press calculate again');
                    return false;

                 }

                    var ok = 1;
                    var nm = $(".c-avatar").text();
                    $.ajax({

                        url: FULLURL + '/getRate',

                        method: 'get',

                        data: {
                            total_gw: total_gw,
                            clear: $('#select_clearance').val(),
                            destination: $('#airport').val(),
                            airline: $('#airline').val()
                        },

                        success: function(data) 
                        {

//alert("new_rate1")
                            console.log(data)

                          if(data==='na')
                            {
                                alert("No rates available for the combination of agent,destination and Airline");
                                return false;
                            }

                            if(data.rate==0)
                            {
                               // alert("No rates available for this weight category");

                                 
                                  let new_rate = prompt("No rates available for this weight category\n Please enter New Rate:", "");
                                  if (new_rate == null || new_rate == ""|| new_rate ==0) 
                                  {
                                   return false;
                                  } 
                                  else 
                                  {
                                  
                                     $("#new_rate").val(new_rate);
                                     $('#freights').val(new_rate);
                                      calNow(x,1);
                                  }

                                return false;
                            }
                            else
                            {
                                 $('#freights').val(data.rate);
                                 $("#new_rate").val(data.rate)
                                 calNow(x,2);
                            }

                        

                        }

                    })
                 }
                 })
    }


                 




}
function calNow(x = '0',c=0) {

//alert(c)


    const time = $('#sorted_table tbody tr').length *8000;

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
    let totalRow = parseFloat($("#span_Item").text());

    var cntRow = 0;



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
                
                 if(data==='na')
                {   Swal.fire({timer:1})
                    setTimeout(function() {alert("Please check Transport Clearance -Now cal")},1000)
                    return false;
                }

                $('#transport').val(data);

                const transport = parseFloat($('#transport').val());

                const transport_price = transport / total_nw;

                $('#transport_price').val(transport_price);

                $.each($('input.itf_transport_price'), function() {

                    const itf_transport_price = parseFloat($('#transport_price').val()) * parseFloat($(this).parent().find('input.nw').val());

                    $(this).val(itf_transport_price.toFixed(4))



                })




                if ($('#airport').val()== "" && $('#airline').val()== "") 
                {
                    alert('กรุณาเลือก Airport และ Airline แล้วกดคำนวณอีกครั้ง / Please select Airport and Airline then press calculate again');
                    return false;

                 }

                   var ok = 1;
                var nm = $(".c-avatar").text();


                 




                

               $("#Tbl tbody").html("");

                $.each($('.unitcount'), function()
                    {


                        var $this = $(this);

                        if (!$(this).val()) 
                        {
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


                        var box = number_box;

                        var rebate = parseFloat($('#rebate').val())
                        var markup_rate = parseFloat($('#markup_rate').val())
                        var ex_rate = parseFloat($('#ex_rate').val());




                        let itfUnit = $(this).val();;



                        let nw = parseFloat($this.parent().parent().find('td:nth-child(5) input.nw').val());;
                        let net_weightNew = parseFloat($this.parent().parent().find('td:nth-child(2) input.net_weightNew').val());;




                        let fob = 0;

                        let rebat = 0;
                        let itf_fob = 0;




                        let fixPrice = 0;
                        let total_nw = parseFloat($('#total_nw').val());
                        let totaNW = parseFloat($('#total_nw').val());
                        let total_gw = parseFloat($('#total_gw').val());




                        const total_pallet_cost = parseFloat($('#total_pallet_cost').val());



                        let ipallets = parseFloat(total_pallet_cost) / parseFloat(totaNW) * parseFloat(net_weightNew);


//alert("ipallets cost:"+ipallets+" total_pallet_cost:" + total_pallet_cost + " totaNW:" + totaNW + " net_weightNew:" + net_weightNew);

                        //console.log("total_pallet_cost:" + total_pallet_cost + " totaNW:" + totaNW + " net_weightNew:" + net_weightNew);

                        ipallets = parseFloat(ipallets).toFixed(6);
                        ipallets = parseFloat(ipallets).toFixed(5);
                        if ($('#select_pallet').val() == 'yes') {

                            $this.parent().parent().find('td:nth-child(5) input.ipallets').val(ipallets);
                        } else {
                            $this.parent().parent().find('td:nth-child(5) input.ipallets').val(0);
                            ipallets = 0;
                        }

                        let itruck = 0;
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
                                        //alert("s")
                                         console.log(data)
                                        if(data==='na')
                                        {
                                            alert("No rates available for the combination of agent and destination");
                                            return false;
                                        }
                                      ///  $('#freights').val(data.rate);
                                        if($("#new_rate").val()>0)
                                        {
                                           $('#freights').val($("#new_rate").val());  
                                        }
                                      

                                        let unit_price = parseFloat($this.parent().parent().find('td:nth-child(6) input.unit_price').val()).toFixed(3);;

                                        let cost = parseFloat($this.parent().parent().find('td:nth-child(5) input.itf_cost_price').val());;
                                        let qty = parseFloat($this.parent().parent().find('td:nth-child(2) input.qty').val());

                                        let ean_cost = parseFloat($this.parent().parent().find('td:nth-child(5) input.ean_cost').val()).toFixed(5);
                                        let ean_ppITF = parseFloat($this.parent().parent().find('td:nth-child(2) input.ean_ppITF').val()).toFixed(5);
                                        let ean_ppkg = parseFloat($this.parent().parent().find('td:nth-child(2) input.ean_ppkg').val()).toFixed(5);
                                        let ean_qty = parseFloat($this.parent().parent().find('td:nth-child(2) input.ean_qty').val()).toFixed(5);
                                        let itf_CBM = $this.parent().parent().find('td:nth-child(5) input.itf_CBM').val();
                                        let itf_GW = parseFloat($this.parent().parent().find('td:nth-child(5) input.itf_GW').val()).toFixed(2);

                                        let itf_freight_rate = parseFloat($this.parent().parent().find('td:nth-child(5) input.itf_freight_rate').val());;
                                        let total_cost = parseFloat($this.parent().parent().find('td:nth-child(5) input.total_itf_cost').val()).toFixed(5);


                                        let gw_weight = parseFloat($this.parent().parent().find('td:nth-child(5) input.gw_weight').val());;



                                        /**/
                                        var of_pallats = $this.parent().parent().find('td:nth-child(5) input.of_pallats').val();

                                        let total_pallet = 0;
                                        let complete_pallet = 0;
                                        let select_palletized = $('#select_pallet').val();
                                        let total_box = parseFloat($('#total_box').val());
                                        if (select_palletized == 'yes') 
                                        {
                                            complete_pallet=parseFloat($("#complete_pallet").val());
                                            total_pallet=parseFloat($("#total_pallet").val());
                                        }


                                        




                                        var price_pallet = parseFloat($("#price_pallet").val());




                                       


                                        let box_weight = $this.parent().parent().find('td:nth-child(5) input.box_weight').val();




                                      
                                       total_pallat_weight = parseFloat($("#total_pallet_weight").val());



                                        /**/



                                        net_weightNew = parseFloat($this.parent().parent().find('td:nth-child(2) input.net_weightNew').val());;
                                        select_itf = parseFloat($this.parent().parent().find('td:nth-child(1) .select_itf ').val());;



                                        let clearance = parseFloat($("#clearance").val());

                                        let chamber = parseFloat($("#chamber").val());

                                        let select_chamber = $("#select_chamber").val();
                                        if (select_chamber == 'yes') {
                                            clearance = clearance - chamber;
                                        }
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

                                        iclearance = parseFloat(iclearance).toFixed(6)


                                        let ichamber = 0;

                                        if (select_chamber == "yes" && box > 0)

                                        {




                                            if (ITF_NWO = "actual")

                                            {

                                                ichamber = parseFloat(chamber) / parseFloat(totaNW) * parseFloat(net_weightNew);


                                            } else

                                            {



                                                ichamber = parseFloat(chamber) / parseFloat(ITF_NWO) * parseFloat(net_weightNew);

                                            }



                                        }

                                        ichamber=parseFloat(ichamber).toFixed(5);


                                        if (box > 0)

                                        {




                                            if (ITF_NWO = "actual")

                                            {

                                                itruck = parseFloat(transport) / parseFloat(totaNW) * parseFloat(net_weightNew);



                                            } else

                                            {



                                                itruck = parseFloat(transport) / parseFloat(ITF_NWO) * (parseFloat(net_weightNew) * parseFloat(box));

                                            }



                                        }


                                        itruck = itruck.toFixed(4);

                                        var total_weight=parseFloat($this.parent().parent().find('td:nth-child(5) input.total_weight').val())

                                        let ifreight1 = total_weight * parseFloat($('#freights').val());
                                        

                                        let ifreight = parseFloat(ifreight1).toFixed(5)




                                        var tcost1 = parseFloat(iclearance) + parseFloat(ichamber) + parseFloat(itruck) + parseFloat(ipallets) + parseFloat(ifreight) + parseFloat(total_cost);
                                         var net_weightNew_order = parseFloat($this.parent().parent().find('input.net_weightNew_order').val());; 
                                           var itf_fx_price1 = parseFloat($this.parent().parent().find('td:nth-child(5) input.itf_fx_price').val());; 
                                             let itf_fx_price = parseFloat(itf_fx_price1).toFixed(2);
                                         let order_price = parseFloat(itf_fx_price)/parseFloat(net_weightNew_order);

                                        var tcost = parseFloat(tcost1).toFixed(5);
                                         if(itfUnit=='2')
                                        {
                                           fixPrice= order_price;
                                        }
                                        else if(itfUnit=='5')
                                        {
                                             //fixPrice= parseFloat(order_price)*parseFloat(net_weightNew);
                                              fixPrice= itf_fx_price;
                                        }
                                        else if(itfUnit=='1')
                                        {
                                             fixPrice= parseFloat(order_price)/parseFloat(ean_ppkg);
                                        }


/* alert("fixPrice:"+fixPrice+" order_price:"+order_price+" ean_ppkg:"+ean_ppkg+" itf_fx_price:"+itf_fx_price+" net_weightNew_order:"+net_weightNew_order)*/


                                let itf_cal_selling =(fixPrice*qty/box)*ex_rate;
                                
                                 itf_cal_selling = parseFloat(itf_cal_selling).toFixed(5);
                                 $this.parent().parent().find('td:nth-child(5) input.itf_cal_selling').val(itf_cal_selling);


                                        fixPrice = parseFloat(fixPrice).toFixed(2)

                                        if (itfUnit == '5') 
                                        {

                                            unitPrice = itf_cal_selling;

                                        } 
                                        else if (itfUnit == '2')
                                        {

                                            unitPrice = parseFloat(itf_cal_selling) / parseFloat(qty);

                                        } 
                                        else if (itfUnit == '1')

                                        {

                                            unitPrice = parseFloat(itf_cal_selling) / parseFloat(ean_ppITF);

                                        } else

                                        {

                                         

                                            unitPrice = 0;

                                        }

                                        unitPrice = parseFloat(unitPrice).toFixed(2)

                                       // let itf_fx_price1 = (parseFloat(fixPrice) * parseFloat(qty)) / (parseFloat(box));

                                      
                                       
                                        let itf_freight_ = parseFloat(parseFloat(ifreight) * parseFloat(box)).toFixed(5);
                                      
                                        
                                       
                                        $this.parent().parent().find('input.order_price').val(parseFloat(order_price).toFixed(2))
                                       $this.parent().parent().find('td:nth-child(5) input.fixPrice').val(fixPrice);
                                       
                                        itf_fob = parseFloat((parseFloat(fixPrice) * parseFloat(qty) * parseFloat($('#ex_rate').val())) - parseFloat(itf_freight_)).toFixed(5);


                                        let profit2 = (parseFloat(itf_fob) + parseFloat(itf_freight_)) - (parseFloat(tcost) * parseFloat(box));


                                        



                                        let rebates = parseFloat($('#rebate').val());
                                 




                                        $this.parent().parent().find('td:nth-child(5) input.itf_fob').val(parseFloat(itf_fob).toFixed(5));

                                        $this.parent().parent().find('td:nth-child(5) input.profit2').val(parseFloat(profit2).toFixed(5));

                                        //$this.parent().parent().find('td:nth-child(5) input.itf_fx_price').val(parseFloat(itf_fx_price).toFixed(2));




                                        $this.parent().parent().find('td:nth-child(5) input.itotal_cost').val(parseFloat(tcost).toFixed(5));




                                        /*cl*/

                                        var EX_rate = parseFloat($("#ex_rate").val())
                                        var rebat1 = parseFloat($("#rebate").val())



                                        let rebate = parseFloat($('#rebate').val())


                                        profit2 = (parseFloat(itf_fob) + parseFloat(itf_freight_)) - (parseFloat(tcost) * parseFloat(number_box));


                                        profit2 = profit2.toFixed(5)




    profitP = (((parseFloat(fixPrice) * parseFloat(qty)/parseFloat(number_box) * parseFloat(EX_rate))*(1 - parseFloat(rebat1) / 100))- parseFloat(tcost))/parseFloat(itf_cal_selling);

     // alert("fixPrice="+fixPrice+" qty:"+qty+" number_box:"+number_box+" EX_rate:"+EX_rate+" rebate:"+rebate+" total cost:"+tcost+" itf_cal_selling:"+itf_cal_selling)


                                        profitP = parseFloat(profitP*100).toFixed(2);




                                        profitP = profitP + "%";



                                        $this.parent().parent().find('td:nth-child(7) input.profit').val(profitP);

                                        $this.parent().parent().find('td:nth-child(5) input.itf_freight').val(itf_freight_);
                                        $this.parent().parent().find('td:nth-child(5) input.itf_fob').val(itf_fob);


                                        let red = "EAN_Cost:" + total_cost + " <br> clearance:" + iclearance + " <br> chamber:" + ichamber + " <br> truck:" + itruck + " <br> pallets:" + ipallets + " <br> ifreight:" + ifreight + " <br> Total_cost:" + tcost + " <br> itf_cal_selling:" + itf_cal_selling;



                                        rebat = parseFloat((parseFloat(itf_fob) + parseFloat(itf_freight_)) * parseFloat(rebate) / 100).toFixed(5);



                                        let ared = "itf_fob:" + parseFloat(itf_fob).toFixed(5) + " <br> freight_:" + itf_freight_ + " <br> Profit:" + profit2 + " <br> Rebate: " + rebat + " <br> ITF FX Price(Order price):" + itf_fx_price;



                                        let str = "<tr class='yess'><td>" + qty + "</td><td>" + itfUnit + "</td><td>" + number_box + "</td><td>" + nw + "</td><td>" + gw_weight + "</td><td>" + itf_CBM + "</td><td>" + fixPrice + "</td><td>" + profitP + "</td><td>" + red + "</td><td>" + ared + "</td>";


                                        
                                       // alert(lists)
                                       
                                        $("#Tbl tbody").append(str);

                               



                                        /****************/
                                        cntRow++;
                                        if (totalRow == cntRow) 
                                        {

                                            let total_box = 0;

                                            let total_gws = 0;

                                            let itf_CBM = 0;

                                            let titem = 0;
                                            let nww = 0;

                                            setTimeout(function() {
                                                $.each($('input.number_box'), function() {


                                                    if (!isNaN(parseFloat($(this).val()))) {
                                                        total_box += parseFloat($(this).val());
                                                    }

                                                    titem += 1;

                                                })


                                                $("#span_box").text(sudhirComma(total_box));
                                                $("#span_Item").text(sudhirComma(titem));


                                                $.each($('input.nw'), function() {


                                                    if (!isNaN(parseFloat($(this).val()))) {
                                                        nww += parseFloat($(this).val());
                                                    }



                                                })


                                                $("#span_nw").text(sudhirComma(nww));

                                                $.each($('input.itf_GW'), function() {


                                                    if (!isNaN(parseFloat($(this).val()))) {
                                                        total_gws += parseFloat($(this).val());
                                                    }

                                                })



                                                $.each($('input.itf_CBM'), function() {

                                                    if (!isNaN(parseFloat($(this).val()))) {

                                                        itf_CBM += parseFloat($(this).val());
                                                    }

                                                })

                                                var itf_freight_t = 0;

                                                $.each($('input.itf_freight'), function() {

                                                    if (!isNaN(parseFloat($(this).val()))) {

                                                        itf_freight_t += parseFloat($(this).val());
                                                    }

                                                })


                                                $("#span_freight").text(sudhirComma(itf_freight_t.toFixed(5)));
                                                $('#total_freight').val(itf_freight_t.toFixed(5));




                                                var itf_fob = 0;

                                                $.each($('input.itf_fob'), function() {
                                                    if (!isNaN(parseFloat($(this).val()))) {


                                                        itf_fob += parseFloat($(this).val());
                                                    }

                                                })


                                                $("#span_fob").text(sudhirComma(itf_fob.toFixed(5)));
                                                $('#total_fob').val(itf_fob.toFixed(5));

                                                var span_fcnft = parseFloat(itf_fob) + parseFloat(itf_freight_t);
                                                $("#span_fcnft").text(sudhirComma(span_fcnft.toFixed(2)));
                                                $("#total_cnf").val(sudhirComma(span_fcnft.toFixed(2)));




                                                var profit2 = 0;

                                                $.each($('input.profit2'), function() {


                                                    if (!isNaN(parseFloat($(this).val()))) {
                                                        profit2 += parseFloat($(this).val());
                                                    }

                                                })


                                                $("#span_pro_before_rebate").text(sudhirComma(profit2.toFixed(2)));
                                                $('#total_pro_before_rebate').val(profit2.toFixed(2));


                                                let rebateX = parseFloat($('#rebate').val());

                                                let span_pro_after_rebate = parseFloat(profit2) - (parseFloat(span_fcnft) * parseFloat(rebateX) / 100);

                                              //  console.log("profit2: " + profit2 + "  span_fcnft: " + span_fcnft + " rebate: " + rebateX);
                                                $("#span_pro_after_rebate").text(sudhirComma(span_pro_after_rebate.toFixed(2)));
                                                $('#total_pro_after_rebate').val(span_pro_after_rebate.toFixed(2));

                                                var span_pro_percent = parseFloat(span_pro_after_rebate) / parseFloat(span_fcnft) * 100;
                                                $("#span_pro_percent").text(sudhirComma(span_pro_percent.toFixed(2)));
                                                $('#total_pro_percent').val(sudhirComma(span_pro_percent.toFixed(2)));
                                                loading(1);
                                                //alert("done")
                                            }, 200)


                                          
                                        }
                                        /****************/

                                    }

                                })
                            }
                        })


                    })

                if (ok == 2) {
                    return false;
                }




                let tfrt = 0;

                let tfob = 0;

                let tcnf = 0;

                let pbr = 0;

                let par = 0;

                let profit = 0;




                if (x=='0') 
                {
                    $("#myBtn").click();
                }




            }

        })

    } else

    {

        alert('กรุณาเลือก Clearance แล้วกดคำนวณอีกครั้ง / Please select Clearance then press calculate again')

    }




}




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

        title: 'Calculating',

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

                // let gw = parseFloat($(this).parent().parent().find('td:nth-child(4) input.number_box').val()) * parseFloat($(this).parent().parent().find('td:nth-child(2) input.new_weight').val());

                // let gw_weight = parseFloat(gw) + (parseFloat($(this).parent().find('input.pallet').val()) * parseFloat(pallet_weight))

                // $(this).val(gw_weight.toFixed(2));



                // $(this).parent().parent().find('td:nth-child(5) input.itf_GW').val(gw.toFixed(2));



            })

    }



}




function getPalatass()

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
                 if(data=='na')
                    {
                        /* Swal.fire({timer:1})
                         setTimeout(function() {alert("Please check Transport Clearance")},1000)*/
                        
                        return false;
                    }


                $('#transport').val(data);

                const transport = parseFloat($('#transport').val());

                const transport_price = transport / total_nw;

                $('#transport_price').val(transport_price);

                $.each($('input.itf_transport_price'), function() {

                    const itf_transport_price = parseFloat($('#transport_price').val()) * parseFloat($(this).parent().find('input.nw').val());

                    $(this).val(itf_transport_price.toFixed(4))



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
          //  $('#freights').val(data.rate);
alert("new_rate0")
             $('#freights').val($("#new_rate").val());


            $.each($('.itf_freight_rate'), function() {

                var $this = $(this);

                const gw = $this.parent().find('input.gw_weight').val();

                const itf_freight_rate = parseFloat(data.rate) * parseFloat(gw);

                $this.val(itf_freight_rate.toFixed(4));

               


            })

        }

    })
}



$(document).on('change', '.unit_price', function() {

    var $this = $(this).parent();

    const qty = parseFloat($this.parent().parent().find('td:nth-child(2) input.qty').val());
    const box = parseFloat($this.parent().parent().find('td:nth-child(4) input.number_box').val());

    const tcost = parseFloat($this.parent().parent().find('td:nth-child(5) input.itotal_cost').val());
    // alert(qty)


    const total_itf_cost = parseFloat($this.parent().parent().find('td:nth-child(5) input.total_itf_cost').val());

    const itf_freight_rate = parseFloat($this.parent().parent().find('td:nth-child(5) input.itf_freight_rate').val());

    const unit_price = parseFloat($(this).val());

    var fixPrice = unit_price;

    const ex_rate = parseFloat($('#ex_rate').val())

    let rebate = parseFloat($('#rebate').val());

    const profit = ((unit_price * ex_rate * qty) - total_itf_cost) / total_itf_cost * 100;

    let markup_rate = parseFloat($('#markup_rate').val())

    var markup_rateCal = ((0.0198 * (markup_rate * markup_rate)) + (0.7901 * markup_rate) + 1.34) / 100;

    $("#markup_rateCal").val(markup_rateCal.toFixed(4));




    let fob = 0;

    let total_all_cost = 0;

    let profit_before_rebate = 0;

    let profit_after_rebate = 0;

    var itf_fob = 0;
    var profitx = 0;
    var itf_freight = 0;
    var itf_fx_price1 = 0;
    var itf_fx_price = 0;
    setTimeout(function() {


        $.each($('.unit_price'), function() {

            var $this = $(this);
            const itf_cal_selling = parseFloat($this.parent().parent().parent().find('td:nth-child(5) input.itf_cal_selling').val());;


            const unit_price = parseFloat($this.val());

            const qty = parseFloat($this.parent().parent().parent().find('td:nth-child(2) input.qty').val());
            const itf_freight_rate = parseFloat($this.parent().parent().parent().find('td:nth-child(5) input.itf_freight_rate').val());
            const itotal_cost = parseFloat($this.parent().parent().parent().find('td:nth-child(5) input.itotal_cost').val());
            const number_box = parseFloat($this.parent().parent().parent().find('td:nth-child(4) input.number_box').val());

            const itf_freight1 = parseFloat($this.parent().parent().parent().find('td:nth-child(5) input.itf_freight').val());

            const ex_rate = parseFloat($('#ex_rate').val())

            const itf_fob1 = parseFloat(parseFloat((parseFloat(unit_price) * parseFloat(qty) * parseFloat(ex_rate)) - parseFloat(itf_freight_rate))).toFixed(5);
            itf_fob = parseFloat(itf_fob) + parseFloat(itf_fob1);

            const profit = (parseFloat(itf_fob1) + parseFloat(itf_freight_rate)) - (parseFloat(itotal_cost) * parseFloat(number_box));

            profitx = parseFloat(profitx) + parseFloat(profit);


            itf_freight = parseFloat(itf_freight) + parseFloat(itf_freight1);

            itf_fx_price1 = (parseFloat(unit_price) * parseFloat(qty)) / (parseFloat(number_box));

            itf_fx_price = parseFloat(itf_fx_price1).toFixed(2);


            profitP = ((parseFloat(itf_fx_price) * parseFloat(ex_rate)) * (1 - parseFloat(rebate) / 100) - parseFloat(itotal_cost)) / parseFloat(itf_cal_selling);


            profitP = parseFloat(profitP * 100).toFixed(2);

            $this.parent().parent().parent().find('td:nth-child(7) input.profit').val(profitP + "%");


        })



        $('#span_fob').text(sudhirComma(itf_fob.toFixed(5)));

        $('#total_fob').val(itf_fob.toFixed(5));



        $('#span_pro_before_rebate').text(sudhirComma(profitx.toFixed(2)));
        $('#total_pro_before_rebate').val(profitx.toFixed(2));



    }, 1000)



    setTimeout(function() {

        if ($('#total_pro_before_rebate').val() != '') {

            const rebate = parseFloat($('#rebate').val());

            if ($('#rebate').val() != '') {

                var span_fcnft = parseFloat(itf_fob) + parseFloat(itf_freight);
                $("#span_fcnft").text(sudhirComma(span_fcnft.toFixed(2)));
                $("#total_cnf").val(sudhirComma(span_fcnft.toFixed(2)));

                let span_pro_after_rebate = parseFloat(profitx) - (parseFloat(span_fcnft) * parseFloat(rebate) / 100);




                $('#span_pro_after_rebate').text(sudhirComma(span_pro_after_rebate.toFixed(2)));

                $('#total_pro_after_rebate').val(span_pro_after_rebate.toFixed(2));


                var span_pro_percent = parseFloat(span_pro_after_rebate) / parseFloat(span_fcnft) * 100;
                $("#span_pro_percent").text(sudhirComma(span_pro_percent.toFixed(2)));



                $('#total_pro_percent').val(sudhirComma(span_pro_percent.toFixed(2)));

            } else {

                alert('กรุณากรอก Rebate แล้วกดคำนวณอีกครั้ง / Please enter Rebate then press calculate again')

            }

        }

    }, 1200)

})


$(document).ready(function()
{
    var unitcount= $('.unitcount').val();
    if(unitcount)
    {
     $('.unitcount').change();
    }
	 $('#sorted_table').DataTable(); 
				
				
});

