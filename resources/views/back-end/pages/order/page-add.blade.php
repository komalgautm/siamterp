<style type="text/css">
.c-sidebar-nav{z-index: 00000;}
.mslC{z-index: 11111;}
.swal2-backdrop-show{z-index: 99999999;}

/*.editOnly{display: none;}*/
</style>
<div class="fade-in">

    <div class="row">

        <div class="col-lg-12 col-md-12">

            <form id="createForm" method="post" action="" enctype="multipart/form-data"> 

                @csrf

                @method('PUT')

                <div class="card">

                    <div class="card-header">

                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Order Management</a></span>

                        <span class="breadcrumb-item active">Create Form</span>

                        <div class="card-header-actions"><small class="text-muted"><a href="https://getbootstrap.com/docs/4.0/components/input-group/#custom-file-input">docs</a></small></div>

                    </div>

                    <div class="card-body">

                        @csrf

                        
                       <input type="hidden" id="isEdit" value="0">
                        {{-- <ul class="nav nav-pills">

                            <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#TH">TH</a></li>

                            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#EN">EN</a></li>

                        </ul> --}}

                        <div class="tab-content" id="myTabContent">

                            <br>

                            <div class="tab-pane fade show active" id="TH" aria-labelledby="home-tab">

                                <div class="row">

                                    <div class="form-group col-lg-4">

                                        <h6>Setup Date</h6>

                                    <input type="text" id="created" name="created" class="form-control" placeholder="" value="{{ date('d-M-Y') }}" readonly/>

                                    </div>

                                    <div class="form-group col-lg-4">

                                        <h6>Create By</h6>

                                    <input type="hidden" id="user" name="user" value="{{ Auth::user()->id }}">

                                    <input type="text" id="user_name" name="user_name" class="form-control" value="{{ Auth::user()->name }}" readonly>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="form-group col-lg-3">

                                        <h6>Client</h6>

                                        <select class="form-control select2" name="client" id="client" style="width:100%" @if(Auth::user()->role != 'admin') required @endif>

                                            <option value="">Select client</option>

                                            @foreach ($clients as $client)

                                                <option value="{{ $client->id }}">{{ $client->name }}</option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="form-group col-lg-3">

                                        <h6>Ship To</h6>

                                        <select name="shipto" id="shipto" class="form-control select2"  style="width:100%" required> 

                                            <option value="">Select ship to</option>

                                        </select>

                                    </div>

                                    <div class="form-group col-lg-3">

                                        <h6>Airport</h6>

                                        <select class="form-control select2" name="airport" id="airport" style="width:100%" required>

                                            <option value="">Select airport</option>

                                            @foreach ($airports as $air)

                                                <option value="{{ $air->id }}">{{ $air->country.' - '.$air->city.' ['.$air->airport_code.']' }}</option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="form-group col-lg-3">

                                        <h6>Airline</h6>

                                        <select class="form-control select2" name="airline" id="airline" style="width:100%" required>

                                            <option value="">Select airline</option>

                                            {{-- @foreach ($airlines as $line)

                                                <option value="{{ $line->id }}">{{ $line->name.' ['.$line->airline_code.']' }}</option>

                                            @endforeach --}}

                                        </select>

                                    </div>

                                </div>

                                <div class="row">

                                    <div class="form-group col-lg-3">

                                        <h6>Currency</h6>

                                        <select class="form-control" name="currency" id="currency" required>

                                            <option value="">Select currency</option>

                                            @foreach ($currencys as $currency)

                                                <option value="{{ $currency->id }}">{{ $currency->currency }}</option>

                                            @endforeach

                                        </select>

                                    </div>

                                    <div class="form-group col-lg-3">

                                        <h6>EX Rate</h6>

                                        <div class="input-group">

                                            <input class="form-control" type="text" name="ex_rate" id="ex_rate" placeholder="" required>

                                            <div class="input-group-append"><span class="input-group-text">THB</span></div>

                                        </div>

                                    </div>

                                    <div class="form-group col-lg-3">

                                        <h6>Markup Rate</h6>

                                        <div class="input-group">

                                            <input class="form-control" type="text" name="markup_rate" id="markup_rate" placeholder="" value="0" required>

                                            <div class="input-group-append"><span class="input-group-text">%</span></div>

                                        </div>

                                    </div>

                                    <div class="form-group col-lg-3">

                                        <h6>Rebate</h6>

                                        <div class="input-group">

                                            <input class="form-control" type="text" name="rebate" id="rebate" placeholder="" value="0" required>

                                            <div class="input-group-append"><span class="input-group-text">%</span></div>

                                        </div>

                                    </div>

                                </div>

                                <div class="row">

                                    

                                    <div class="form-group col-lg-3">

                                        <h6>Clearance</h6>

                                        <select class="form-control select2" name="select_clearance" id="select_clearance" style="width:100%" required>

                                            <option value="">Select clearance</option>

                                            @foreach ($clearance as $clear)

                                                <option value="{{ $clear->id }}">{{ $clear->name }}</option>

                                            @endforeach

                                        </select>

                                        <input type="hidden" class="" name="clearance" id="clearance" value="" readonly>

                                        <input type="hidden" class="" name="chamber" id="chamber" value="" readonly>

                                        <input type="hidden" class="" name="clearance_price" id="clearance_price" value="" readonly>

                                        <input type="hidden" class="" name="transport" id="transport" readonly>

                                        <input type="hidden" class="" name="transport_price" id="transport_price" readonly>

                                        <input type="hidden" class="" name="markup_rateCal" id="markup_rateCal" readonly>




                                        

                                        <input type="hidden" class="" name="freights" id="freights"  value="10" readonly>

                                     

                                    </div>

                                    <div class="form-group col-lg-3">

                                        <h6>Palletized</h6>

                                        <select class="form-control" name="select_pallet" id="select_pallet" onchange="recalculation()">

                                            <option value="no">No</option>

                                            <option value="yes">Yes</option>

                                        </select>

                                        <input type="hidden" class="" name="weight_pallet" id="weight_pallet" value="{{$pall->weight}}" readonly>

                                        <input type="hidden" class="" name="price_pallet" id="price_pallet" value="{{$pall->cost}}" readonly>

                                        <input type="hidden" class="" name="cbm_pallet" id="cbm_pallet" value="{{$pall->volume}}" readonly>


                                        <!-- By sudhir -->
                                        <input type="hidden" class="" name="total_pallet" id="total_pallet" value="" readonly>
                                        <input type="hidden" class="" name="complete_pallet" id="complete_pallet" value="" readonly>
                                        <input type="hidden" class="" name="total_pallet_cost" id="total_pallet_cost" value="" readonly>

                                    </div>

                                    <div class="form-group col-lg-3">

                                        <h6>CO from Chamber</h6>

                                        <select class="form-control" name="select_chamber" id="select_chamber" onchange="recalculation()">

                                            <option value="no">No</option>

                                            <option value="yes">Yes</option>

                                        </select>

                                    </div>

                                    <div class="form-group col-lg-3">

                                        <h6>Ship Date</h6>

                                        <input type="date" class="form-control" name="ship_date" id="ship_date" required>

                                    </div>

                                </div>



                                <div class="row">

                                    

                                    <div class="form-group col-lg-3" style="display:none">

                                        <h6>ITF NW</h6>

                                        <select class="form-control ITF_NWO" name="ITF_NWO" id="ITF_NWO" style="width:100%" required>

                                            <option value="actual">Actual</option>

                                            <option value="500">500</option>

                                            <option value="750">750</option>

                                            <option value="1000">1000</option>

                                            <option value="1000">1250</option>

                                            <option value="1000">1500</option>

                                            <option value="1000">1750</option>

                                            <option value="1000">2000</option>

                                           

                                        </select>

                                        

                                    </div>

                                   

                                </div>

                            </div>

                            <div class="row">

                                <div class="form-group col-lg-6">

                                    <button type="button" class="btn btn-xs btn-success" onclick="cal()">Calculate</button><br>

                                    <small style="color:red"><b>**ทุกครั้งที่มีการเปลี่ยนแปลงข้อมูลกรุณากดปุ่ม Calculate**</b></small><br>

                                    <small style="color:red"><b>**Every time information is changed, please press the button Calculate**</b></small>

                                </div>

                            </div>

                            {{-- <div class="tab-pane fade" id="EN" aria-labelledby="home-tab">

                                <div class="row"> 

                                    <div class="form-group col-lg-8">

                                        <h6>Title</h6>

                                        <input type="text" name="title_en" class="form-control" value=""/>

                                    </div>

                                </div>       

                                <div class="row"> 

                                        <div class="form-group col-lg-12">

                                            <h6>Caption</h6>

                                            <textarea type="text" name="caption_en" class="form-control" rows="6"></textarea>

                                        </div>

                                    </div> 

                                <div class="row"> 

                                    <div class="form-group col-lg-12">

                                        <h6>Detail</h6>

                                        <textarea type="text" name="detail_en" class="form-control tiny" rows="9"></textarea>

                                    </div>

                                </div>   

                            </div> --}}

                        </div>

                        <hr>

                        <div class="table-responsive">

                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important" width="100%">

                                <thead>

                                    <tr role="">

                                        <th width="10%">ITF</th>

                                        <th width="10%">Quantity</th>

                                        <th width="10%">Unit</th>

                                        <th width="10%">Number of Box</th>

                                        <th width="10%">NW</th>

                                        <th width="10%">Unit Price</th>

                                        {{-- <th width="8%">Crate</th> --}}

                                        <th width="10%">Profit</th>

                                        <th width="5%">Action</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    <tr id="row1" data-id="1">

                                        <td data-label="ITF">

                                            <select name="itf[]" id="itf1" class="form-control select_itf select2" style="width:100%" required>

                                                <option value="">Select ITF</option>

                                                @foreach ($itfs as $itf)

                                                <option value="{{ $itf->id }}">{{ $itf->name }}</option>

                                                @endforeach

                                            </select>

                                        </td>

                                        <td data-label="Quantity">

                                            <input type="number" class="form-control qty" name="quantity[]" step="0.01" min="0" placeholder="Enter quantity" value="" id="tqty1" onchange="myFunction(1)" required>

                                            <input type="hidden" class="ean_id" name="ean_id[]" placeholder="ean_id">

                                            <input type="hidden" class="ean_qty" name="ean_qty[]" placeholder="ean_qty">

                                            <input type="hidden" class="net_weight" name="net_weight[]" placeholder="net_weight">

                                            <input type="hidden" class="net_weight2" name="net_weight2[]" placeholder="net_weight2">

                                            



                                            <input type="hidden" class="new_weight" name="new_weight[]" placeholder="new_weight">

                                            <input type="hidden" class="maxcbm" name="maxcbm[]" placeholder="maxcbm">

                                            <input type="hidden" class="maxpallet" name="maxpallet[]" placeholder="maxpallet">



                                            <input type="hidden" class="ean_ppITF" name="ean_ppITF[]" placeholder="ean_ppITF">
                                            <input type="hidden" class="itfQty" name="itfQty[]" placeholder="itfQty">
                                            <input type="hidden" class="hpl_avg_weight" name="hpl_avg_weight[]" >
                                           <input type="hidden" class="net_weightNew" name="net_weightNew[]" >



                                        </td>

                                        <td data-label="Unit">

                                            <select onclick="changC(1)"  name="unitcount[]" id="unitcount1" class="form-control unitcount" style="width:100%" required>

                                                <option value="">Select Unit</option>

                                                @foreach ($units as $u)

                                                <option value="{{ $u->id }}">{{ $u->name_th.' / '.$u->name_en }}</option>

                                                @endforeach

                                            </select>

                                        </td>

                                        <td data-label="Number of Box">

                                            <input type="text" class="form-control number_box" name="number_box[]" value="" placeholder="" readonly>

                                        </td>

                                        <td data-label="NW" for="5">

                                            <input type="text" class="form-control nw" name="nw[]" value="" placeholder="" readonly>

                                            <input type="hidden" class="form-control gw_weight" name="gw_weight[]" placeholder="gw_weight">

                                            <input type="hidden" class="form-control cbm" name="cbm[]" placeholder="cbm">

                                            <input type="hidden" class="form-control pallet" name="pallet[]" placeholder="pallet">

                                            <input type="hidden" class="form-control price_allocation" name="price_allocation[]" placeholder="price_allocation">

                                            <input type="hidden" class="form-control price_pallet_unit" name="price_pallet_unit[]" placeholder="price_pallet_unit">

                                            <input type="hidden" class="form-control itf_pallet_price" name="itf_pallet_price[]" placeholder="itf_pallet_price">

                                            <input type="hidden" class="form-control itf_clearance_price" name="itf_clearance_price[]" placeholder="itf_clearance_price">

                                            <input type="hidden" class="form-control itf_transport_price" name="itf_transport_price[]" placeholder="itf_transport_price">

                                            <input type="hidden" class="form-control itf_cost_price" name="itf_cost_price[]" placeholder="itf_cost_price">

                                            <input type="hidden" class="form-control itf_freight_rate" name="itf_freight_rate[]" placeholder="itf_freight_rate">

                                            <input type="hidden" class="form-control total_itf_cost" name="total_itf_cost[]" placeholder="total_itf_cost">









                                            <input type="hidden" class="form-control itf_cal_selling" name="itf_cal_selling[]" placeholder="itf_cal_selling">

                                            <input type="hidden" class="form-control itf_cost" name="itf_cost[]" placeholder="itf_cost">

                                            <input type="hidden" class="form-control fixPrice" name="fixPrice[]" placeholder="fixPrice">

                                          

                                            <input type="hidden" class="form-control profit2" name="profit2[]" placeholder="profit2">

                                            <input type="hidden" class="form-control itf_GW" name="itf_GW[]" placeholder="itf_GW" value="0">





                                            <input type="hidden" class="form-control itf_CBM" name="itf_CBM[]" placeholder="itf_CBM">

                                            <input type="hidden" class="form-control itf_freight" name="itf_freight[]" placeholder="itf_freight">

                                            <input type="hidden" class="form-control itf_fob" name="itf_fob[]" placeholder="itf_fob">

                                            <input type="hidden" class="form-control itf_rebate" name="itf_rebate[]" placeholder="itf_rebate">

                                            <input type="hidden" class="form-control itf_price" name="itf_price[]" placeholder="itf_price">

                                            <input type="hidden" class="form-control ean_cost" name="ean_cost[]" placeholder="ean_cost">

                                            <input type="hidden" class="form-control pack_cost" name="pack_cost[]" placeholder="pack_cost">

                                            <input type="hidden" class="form-control total_cost" name="total_cost[]" placeholder="total_cost">

                                            <input type="hidden" class="form-control itf_fx_price" name="itf_fx_price[]" placeholder="itf_fx_price">  





                                             <input type="hidden" class="form-control of_pallats" name="of_pallats[]" placeholder="of_pallats">

                                             <input type="hidden" class="form-control box_pallet" name="box_pallet[]" placeholder="box_pallet">

                                             <input type="hidden" class="form-control box_weight" name="box_weight[]" value="0" placeholder="box_weight">

                                             <input type="hidden" class="form-control total_pallat_weight" name="total_pallat_weight[]" placeholder="total pallat_weight orange">

                                             <input type="hidden" class="form-control total_weight" name="total_weight[]" placeholder="total_weight">





                                                    



                                                       <input type="hidden" class="form-control itotal_cost" name="itotal_cost[]" placeholder="itotal_cost">
                                            <input type="hidden" class="form-control rean_cost" name="rean_cost[]">
                                            <input type="hidden" class="form-control rclearance" name="rclearance[]">
                                            <input type="hidden" class="form-control rchamber" name="rchamber[]">
                                            <input type="hidden" class="form-control rtruck" name="rtruck[]">
                                            <input type="hidden" class="form-control rpallets" name="rpallets[]">
                                            <input type="hidden" class="form-control rifreight" name="rifreight[]">







                                        </td>

                                        <td data-label="Unit Price">

                                            <div class="input-group">

                                                <input class="form-control unit_price" type="text" name="unit_price[]" placeholder="" required>

                                                <div class="input-group-append"><span class="input-group-text currency_text">THB</span></div>

                                            </div>

                                        </td>

                                        <td data-label="Profit">

                                            <input class="form-control profit" type="text" name="profit[]" placeholder="" readonly>

                                          

                                        </td>

                                        <td data-label="Action">&nbsp;</td>

                                    </tr>

                                </tbody>

                            </table>

                        </div>

                        <hr>

                        <div class="row">

                            <div class="col-lg-2">

                                <strong style="font-size:18px">Items :</strong>

                                <button type="button" class="btn btn-xs btn-success add-row" >add</button>

                            </div>



                        </div>

                        <hr>

                        <div class="row">

                            <div class="col-lg-2">

                                <strong style="font-size:18px">Total Box :</strong> <span class="buttom_vl" id="span_box"></span>

                                <input type="hidden" id="total_box" name="total_box">

                            </div>
                         <div class="col-lg-2">

                                <strong style="font-size:18px">Total Items :</strong> <span class="buttom_vl" id="span_Item"></span>

                                <input type="hidden" id="total_item" name="total_item">

                            </div>

                            <div class="col-lg-2">

                                <strong style="font-size:18px">Total NW :</strong> <span class="buttom_vl" id="span_nw"></span>

                                <input type="hidden" id="total_nw" name="total_nw">

                            </div>

                            <div class="col-lg-2">

                                <strong style="font-size:18px">Total GW :</strong> <span class="buttom_vl" id="span_gw"></span>

                                <input type="hidden" id="total_gw" name="total_gw">

                            </div>

                             <div class="col-lg-2">

                                <strong style="font-size:18px">Total CNF :</strong> <span class="buttom_vl" id="span_fcnft"></span>

                                <input type="hidden" id="total_cnf" name="total_cnf">

                            </div>


                            <div class="col-lg-2">

                                <strong style="font-size:18px">Total CBM :</strong> <span class="buttom_vl" id="span_cbm"></span>

                                <input type="hidden" id="total_cbm" name="total_cbm">

                            </div>

                            

                        </div>

                        <div class="row">

                            <div class="col-lg-2">

                                <strong style="font-size:18px">Total Freight :</strong> <span class="buttom_vl" id="span_freight"></span>

                                <input type="hidden" id="total_freight" name="total_freight">

                            </div>

                            <div class="col-lg-2">

                                <strong style="font-size:18px">Total FOB :</strong> <span class="buttom_vl" id="span_fob"></span>

                                <input type="hidden" id="total_fob" name="total_fob">

                            </div>

                            <div class="col-lg-3">

                                <strong style="font-size:18px">Profit before Rebate :</strong> <span class="buttom_vl" id="span_pro_before_rebate"></span>

                                <input type="hidden" id="total_pro_before_rebate" name="total_pro_before_rebate">

                            </div>

                            <div class="col-lg-3">

                                <strong style="font-size:18px">Profit after Rebate :</strong> <span class="buttom_vl" id="span_pro_after_rebate"></span>

                                <input type="hidden" id="total_pro_after_rebate" name="total_pro_after_rebate">

                            </div>

                            <div class="col-lg-2">

                                <strong style="font-size:18px">Profit(%) :</strong> <span class="buttom_vl" id="span_pro_percent"></span>

                                <input type="hidden" id="total_pro_percent" name="total_pro_percent">

                            </div>

                            <div class="col-lg-2" id="hid_palletizad" style="display:none">

                                <strong style="font-size:18px">Pallet :</strong> <span class="buttom_vl" id="span_palletized"></span>

                                <input type="hidden" class="" name="palletized" id="palletized">

                                <input type="hidden" class="" name="palletized_price" id="palletized_price">

                            </div>

                        </div>


                    </div>

                    <div class="card-footer">

                        <button class="btn btn-primary" type="submit" name="signup">Create</button>

                        <a class="btn btn-danger" href="{{url("/$folder")}}">Cancel</a>                    

                    </div>

                </form>

            </div>            

        </div>

    </div>              

</div>
<input type="hidden" id="isCallables" value="0">
<script>

 







    var dtToday = new Date();

    

    var month = dtToday.getMonth() + 1;

    var day = dtToday.getDate();

    var year = dtToday.getFullYear();

    if(month < 10)

        month = '0' + month.toString();

    if(day < 10)

        day = '0' + day.toString();

    

    var minDate= year + '-' + month + '-' + day;

    

    //$('#ship_date').attr('min', minDate);



    document.getElementById("ship_date").setAttribute("min",minDate);



</script>  









    



 <style>

/* The Modal (background) */

.modal {

  display: none; /* Hidden by default */

  position: fixed; /* Stay in place */

 z-index: 99999; /* Sit on top */

  padding-top: 100px; /* Location of the box */

  left: 0;

  top: 0;

  width: 100%; /* Full width */

  height: 100%; /* Full height */

  overflow: auto; /* Enable scroll if needed */

  background-color: rgb(0,0,0); /* Fallback color */

  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */

}



/* Modal Content */

.modal-content {

  background-color: #fefefe;

  margin: auto;

  padding: 20px;

  border: 1px solid #888;

  width: 99%;

}



/* The Close Button */

.close {

  color: #aaaaaa;

  float: right;

  font-size: 28px;

  font-weight: bold;

}



.close:hover,

.close:focus {

  color: #000;

  text-decoration: none;

  cursor: pointer;

}

#Tbl tbody{font-weight: bold;}

</style>

</head>





<!-- Trigger/Open The Modal -->

<button id="myBtn" ></button>



<!-- The Modal -->

<div id="myModal" class="modal">



  <!-- Modal content -->

  <div class="modal-content">

    <span style=" float: right;" class="close">&times;</span>

    <h3>Only for test calculation</h3>

  <div>

      <table class="table" border="1" id="Tbl">

        <thead>

            

              <tr>

              <td>ITF Qty</td>

              <td>ITF Unit</td>

              <td>box</td>

              <td>Net weight</td>

              <td>ITF GW</td>

               <td>ITF CBM</td>

                

              <td>FX Price</td>

            

              <td>Profit %  </td>

              <td>Red Section</td>

              <td colspan="2">After Red Section</td>

           

             

             

          </tr>

        </thead>

        <tbody>

            

        </tbody>



      </table>



       <table class="table">

        <thead>

              <tr>

              <td>Total Item</td>

              <td>Total Box</td>

              <td>Total NW</td>

              <td>Total GW</td>

              <td>Total CBM</td>

              <td>Total Fright</td>

              <td>Total FOB</td>

              <td>Total CNF</td>

              <td> P-B-R</td>

              <td> P-A-R</td>

              <td>Profit %</td>

            

            

          </tr>

        </thead>

        <tbody>

            <tr>

              <td class="titem"></td>

              <td class="tbox"></td>

              <td class="tnw"></td>

              <td class="tgw"></td>

              <td class="tcbm"></td>

              <td class="tfrt"></td>

              <td class="tfob"></td>

              <td class="tcnf"></td>

              <td class="pbr"></td>

              <td class="par"></td>

              <td class="profit"></td>

             

          </tr>

        </tbody>



      </table>

  </div>

  </div>



</div>



<script>

// Get the modal

var modal = document.getElementById("myModal");



// Get the button that opens the modal

var btn = document.getElementById("myBtn");



// Get the <span> element that closes the modal

var span = document.getElementsByClassName("close")[0];



// When the user clicks the button, open the modal 

btn.onclick = function() {

  modal.style.display = "block";

}



// When the user clicks on <span> (x), close the modal

span.onclick = function() {

  modal.style.display = "none";

}



// When the user clicks anywhere outside of the modal, close it

window.onclick = function(event) {

  if (event.target == modal) {

    modal.style.display = "none";

  }

}

</script>       



        