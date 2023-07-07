<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="editForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf

                    <style type="text/css">

                /*.card .delete-row,.itm{display: none;}*/
</style>
                 <input type="hidden" id="isEdit" value="1">
             
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Order Management</a></span>
                        <span class="breadcrumb-item active">Edit Form</span>
                        <div class="card-header-actions"><small class="text-muted"><a href="https://getbootstrap.com/docs/4.0/components/input-group/#custom-file-input">docs</a></small></div>
                    </div>
                    <div class="card-body">
                        @csrf
                        
                        {{-- <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#TH">TH</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#EN">EN</a></li>
                        </ul> --}}
                        <div class="tab-content" id="myTabContent">
                            <br>
                            <div class="tab-pane fade show active" id="TH" aria-labelledby="home-tab">
                                <div class="row"> 
                                    <div class="form-group col-lg-4">
                                        <h6>Code</h6>
                                        <input type="text" id="code" name="code" class="form-control" placeholder="" value="{{ $row->code }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Setup Date</h6>
                                    <input type="text" id="created" name="created" class="form-control" placeholder="" value="{{ date('d-M-Y',strtotime($row->created)) }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Create By</h6>
                                    <input type="hidden" id="user" name="user" value="{{ $row->user_id }}">
                                    @php($user = \App\UserModel::where('id',$row->user_id)->first())
                                    <input type="text" id="user_name" name="user_name" class="form-control" value="{{ $user->name }}" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3">
                                        <h6>Client</h6>
                                        <select class="form-control select2" name="client" id="client" style="width:100%" required>
                                            <option value="">Select client</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}" @if($row->client_id == $client->id) selected @endif>{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                    
                                        <h6>Ship To</h6>
                                             <select name="shipto" id="shipto" class="form-control select2"  style="width:100%" required>
                                            <option value="">Select ship to</option>
                                            @foreach ( $shiptos as $s)
                                            @if($row->shipto_id == $s->id)
                                            <option value="{{ $s->id }}" selected="selected" >{{ $s->name }}</option>
                                            @else
                                            <option value="{{ $s->id }}" >{{ $s->name }}</option>
                                            @endif
                                            
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Airport</h6>
                                        <select class="form-control select2" name="airport" id="airport" style="width:100%" required>
                                            <option value="">Select airport</option>
                                            @if($row->shipto_id == '')
                                            @foreach ($airports as $air)
                                                <option value="{{ $air->id }}" @if($row->airport_id == $air->id) selected @endif>{{ $air->country.' - '.$air->city.' ['.$air->airport_code.']' }}</option>
                                            @endforeach
                                            @else
                                            @php($airport = \App\AirportModel::where('id',$shipto->airport)->first())
                                            <option value="{{ $airport->id }}" @if($row->airport_id == $airport->id) selected @endif>{{ $airport->country.' - '.$airport->city.' ['.$airport->airport_code.']' }}</option>
                                            @endif
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Airline</h6>
                                        <select class="form-control select2" name="airline" id="airline" style="width:100%" required>
                                            <option value="">Select airline</option>
                                            @php($airlines = \App\FreightModel::select('freight.*','line.id as airline_id','line.name','line.airline_code')->leftJoin('airline as line','freight.airline','=','line.id')->where(['freight.status'=>'on','destination'=>$row->airport_id])->groupBy('airline')->get())
                                            @foreach ($airlines as $line)
                                                <option value="{{ $line->airline_id }}" @if($row->airline_id == $line->airline_id) selected @endif>{{ $line->name.' ['.$line->airline_code.']' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3">
                                        <h6>Currency</h6>
                                        <select class="form-control" name="currency" id="currency" required>
                                            <option value="">Select currency</option>
                                            @foreach ($currencys as $currency)
                                                <option value="{{ $currency->id }}" @if($row->currency_id == $currency->id) selected @endif>{{ $currency->currency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>EX Rate</h6>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="ex_rate" id="ex_rate" placeholder="" value="{{ $row->ex_rate }}" required>
                                            <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Markup Rate</h6>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="markup_rate" id="markup_rate" placeholder="" value="{{ $row->markup_rate }}" required>
                                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Rebate</h6>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="rebate" id="rebate" placeholder="" value="{{ $row->rebate }}" required>
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
                                                <option value="{{ $clear->id }}" @if($row->clearance_id == $clear->id) selected @endif>{{ $clear->name }}</option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" class="" name="clearance" id="clearance" value="{{ $row->clearance }}" readonly>
                                        <input type="hidden" class="" name="chamber" id="chamber" value="{{ $row->chamber }}" readonly>
                                        <input type="hidden" class="" name="clearance_price" id="clearance_price" value="{{ $row->clearance_price }}" readonly>
                                        <input type="hidden" class="" name="transport" id="transport" value="{{ $row->transport }}" readonly>
                                        <input type="hidden" class="" name="transport_price" id="transport_price" value="{{ $row->transport_price }}" readonly>


                                        
                                         <input type="hidden" class="" name="markup_rateCal" id="markup_rateCal" value="{{ $row->markup_rateCal }}">




                                        

                                        <input type="hidden" class="" name="freights" id="freights"  value="{{ $row->freights }}" readonly>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Palletized</h6>
                                        <select class="form-control" name="select_pallet" id="select_pallet" onchange="recalculation()">
                                            <option value="no" @if($row->select_pallet == 'no') selected @endif>No</option>
                                            <option value="yes" @if($row->select_pallet == 'yes') selected @endif>Yes</option>
                                        </select>
                                          <input type="hidden" class="" name="weight_pallet" id="weight_pallet" value="{{$pall->weight}}" readonly>

                                        <input type="hidden" class="" name="price_pallet" id="price_pallet" value="{{$pall->cost}}" readonly>

                                        <input type="hidden" class="" name="cbm_pallet" id="cbm_pallet" value="{{$pall->volume}}" readonly>

                                         <input type="hidden" class="" name="total_pallet" id="total_pallet" value="" readonly>
                                        <input type="hidden" class="" name="complete_pallet" id="complete_pallet" value="" readonly>
                                        <input type="hidden" class="" name="total_pallet_cost" id="total_pallet_cost" value="" readonly>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>CO from CHamber</h6>
                                        <select class="form-control" name="select_chamber" id="select_chamber" onchange="recalculation()">
                                            <option value="no" @if($row->select_chamber == 'no') selected @endif>No</option>
                                            <option value="yes" @if($row->select_chamber == 'yes') selected @endif>Yes</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Ship Date</h6>
                                        <input type="date" class="form-control" name="ship_date" id="ship_date" value="{{ $row->ship_date }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="form-group col-lg-6">
                                    <button type="button" class="btn btn-xs btn-success cal1" onclick="cal()">Calculate</button>
                                     <button type="button" class="btn btn-xs btn-success cal2" onclick="cal2()">Update Quantity</button><br> 
                                     <button type="button" class="btn btn-xs btn-success reset_prices" onclick="reset_prices()">Reset Prices</button><br>

                                    {{-- <small style="color:red"><b>**ห้ามกดย้อนกลับและรีเฟรชหน้า / Do not press back and refresh page.**</b></small><br> --}}
                                    <small style="color:red"><b>**ทุกครั้งที่มีการเปลี่ยนแปลงข้อมูลกรุณากดปุ่ม Calculate**</b></small><br>
                                    <small style="color:red"><b>**Every time information is changed, please press the button Calculate**</b></small>
                                </div>    


                                <div class="form-group col-lg-6">
                                    <button type="button" style="float: right;    margin-right: 15%;" class="btn btn-outline-success update-row" title="Update QTY"><i class="fas fa-square-root-alt"></i></button>
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
                                        <th width="10%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    
                                    @foreach($details as $key => $detail)
									
                                    <tr id="row{{ $key+1 }}" class="rr-{{$key}}" data-id="{{ $key+1 }}">
                                        <!--<td data-label="ITF">
                                            <select name="itf[]" id="itf{{ $key+1 }}" class="form-control select_itf select2" style="width:100%"  onclick="return false" readonly required>
                                                <!-- <option value="">Select ITF</option> -->
                                                <!--@foreach ($itfs as $itf)
                                                @if($detail->itf_id == $itf->id)
                                                <option value="{{ $itf->id }}" selected >{{ $itf->name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                            <input type="hidden" class="detail_id" name="detail_id[]" value="{{ $detail->id }}">
                                        </td>-->
										<td data-label="ITF">
                                            <select name="itf[]" id="itf{{ $key+1 }}" class="form-control select_itf select2" style="width:100%"  onclick="return false" @if($detail->isDone=='1') readonly @endif required>
                                                <!-- <option value="">Select ITF</option> -->
												
                                                @foreach ($itfs as $itf)
                                                @if($detail->isDone=='1')
                                                  @if($detail->itf_id == $itf->id)
                                                 <option value="{{ $itf->id }}" selected >{{ $itf->name }}</option>
                                                  @endif
                                                @else
                                                 <option value="{{ $itf->id }}" @if($detail->itf_id == $itf->id) selected @endif>{{ $itf->name }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                            <input type="hidden" class="detail_id" name="detail_id[]" value="{{ $detail->id }}">
                                        </td>
										
										
                                        <td data-label="Quantity">
                                            <input type="number" class="form-control qty" name="quantity[]" step="0.01" min="0" placeholder="Enter quantity" value="{{ $detail->qty }}" onchange="myFunction({{$key+1}},this)" @if($detail->isDone=='1') readonly @endif required>
                                            <input type="hidden" class="ean_id" name="ean_id[]" placeholder="ean_id" value="{{ $detail->ean_id }}">
                                            <input type="hidden" class="ean_qty" name="ean_qty[]" placeholder="ean_qty" value="{{ $detail->ean_qty }}">
                                            <input type="hidden" class="net_weight" name="net_weight[]" placeholder="net_weight" value="{{ $detail->net_weight }}">
                                            <input type="hidden" class="new_weight" name="new_weight[]" placeholder="new_weight" value="{{ $detail->new_weight }}">
                                            <input type="hidden" class="maxcbm" name="maxcbm[]" placeholder="maxcbm" value="{{ $detail->maxcbm }}">
                                            <input type="hidden" class="maxpallet" name="maxpallet[]" placeholder="maxpallet" value="{{ $detail->maxpallet }}">


                                            <input type="hidden" class="net_weight2" name="net_weight2[]" value="{{ $detail->net_weight2 }}">

                                              <input type="hidden" class="ean_ppITF" name="ean_ppITF[]" value="{{ $detail->ean_ppITF }}">
                                            <input type="hidden" class="itfQty" name="itfQty[]" value="{{ $detail->itfQty }}">
                                            <input type="hidden" class="hpl_avg_weight" name="hpl_avg_weight[]" value="{{ $detail->hpl_avg_weight }}">
                                           <input type="hidden" class="net_weightNew" name="net_weightNew[]" value="{{$detail->net_weightNew}}" >

                                        </td>
                                        <td data-label="Unit">
                                            <select onclick="changC(1)" name="unitcount[]" id="unitcount{{ $key+1 }}" class="form-control unitcount" for="{{$detail->unitcount_id}}" style="width:100%" @if($detail->isDone=='1') readonly @endif  required>
                                                @if($detail->isDone=='0')
                                                 <option value="">Select Unit</option>
                                                @endif
                                                @foreach ($units as $u)
                                                @if($detail->isDone=='1')
                                                  @if($detail->unitcount_id == $u->id)
                                                 <option value="{{ $u->id }}" @if($detail->unitcount_id == $u->id) selected @endif>{{ $u->name_th.' / '.$u->name_en }}</option>
                                                  @endif
                                                @else
                                                 <option value="{{ $u->id }}" @if($detail->unitcount_id == $u->id) selected @endif>{{ $u->name_th.' / '.$u->name_en }}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </td>
                                        <td data-label="Number of Box">
                                            <input type="text" class="form-control number_box" name="number_box[]" value="{{ $detail->number_box }}" placeholder="" readonly>
                                        </td>
                                        <td data-label="NW" >
                                            <input type="text" class="form-control nw" name="nw[]" value="{{ $detail->nw }}" placeholder="" readonly>
                                            <input type="hidden" class="form-control gw_weight" name="gw_weight[]" placeholder="gw_weight" value="{{ $detail->gw_weight }}">
                                            <input type="hidden" class="form-control cbm" name="cbm[]" placeholder="cbm" value="{{ $detail->cbm }}">
                                            <input type="hidden" class="form-control pallet" name="pallet[]" placeholder="pallet" value="{{ $detail->pallet }}">
                                            <input type="hidden" class="form-control price_allocation" name="price_allocation[]" placeholder="price_allocation" value="{{ $detail->price_allocation }}">
                                            <input type="hidden" class="form-control price_pallet_unit" name="price_pallet_unit[]" placeholder="price_pallet_unit" value="{{ $detail->price_pallet_unit }}">
                                            <input type="hidden" class="form-control itf_pallet_price" name="itf_pallet_price[]" placeholder="itf_pallet_price" value="{{ $detail->itf_pallet_price }}">
                                            <input type="hidden" class="form-control itf_clearance_price" name="itf_clearance_price[]" placeholder="itf_clearance_price" value="{{ $detail->itf_clearance_price }}">
                                            <input type="hidden" class="form-control itf_transport_price" name="itf_transport_price[]" placeholder="itf_transport_price" value="{{ $detail->itf_transport_price }}">
                                            <input type="hidden" class="form-control itf_cost_price" name="itf_cost_price[]" placeholder="itf_cost_price" value="{{ $detail->itf_cost_price }}">
                                            <input type="hidden" class="form-control itf_freight_rate" name="itf_freight_rate[]" placeholder="itf_freight_rate" value="{{ $detail->itf_freight_rate }}">
                                            <input type="hidden" class="form-control total_itf_cost" name="total_itf_cost[]" placeholder="total_itf_cost" value="{{ $detail->total_itf_cost }}">


                                             <input type="hidden" class="form-control itf_cal_selling" name="itf_cal_selling[]" placeholder="itf_cal_selling" value="{{$detail->itf_cal_selling}}">

                                            <input type="hidden" class="form-control itf_cost" name="itf_cost[]" placeholder="itf_cost">

                                            <input type="hidden" class="form-control fixPrice" name="fixPrice[]" value="{{$detail->fx_price}}" placeholder="fixPrice">

                                          

                                            <input type="hidden" class="form-control profit2" name="profit2[]" value="{{$detail->profit2}}"  >

                                            <input type="hidden" class="form-control itf_GW" name="itf_GW[]" placeholder="itf_GW" value="{{ $detail->itf_GW }}">





                                            <input type="hidden" class="form-control itf_CBM" name="itf_CBM[]" placeholder="itf_CBM">

                                            <input type="hidden" class="form-control itf_freight" name="itf_freight[]" placeholder="itf_freight">

                                            <input type="hidden" class="form-control itf_fob" name="itf_fob[]" placeholder="itf_fob">

                                            <input type="hidden" class="form-control itf_rebate" name="itf_rebate[]" placeholder="itf_rebate">

                                            <input type="hidden" class="form-control itf_price" name="itf_price[]" placeholder="itf_price">

                                            <input type="hidden" class="form-control ean_cost" name="ean_cost[]" placeholder="ean_cost">

                                            <input type="hidden" class="form-control pack_cost" name="pack_cost[]" placeholder="pack_cost">

                                            <input type="hidden" class="form-control total_cost" name="total_cost[]" placeholder="total_cost">

                                            <input type="hidden" class="form-control itf_fx_price" name="itf_fx_price[]" placeholder="itf_fx_price" value="{{$detail->itf_fx_price }}" >  





                                             <input type="hidden" class="form-control of_pallats" name="of_pallats[]" placeholder="of_pallats">

                                             <input type="hidden" class="form-control box_pallet" name="box_pallet[]" value="{{ $detail->box_pallet}}">

                                             <input type="hidden" class="form-control box_weight" name="box_weight[]" value="{{ $detail->box_weight}}" >

                                             <input type="hidden" class="form-control total_pallat_weight" name="total_pallat_weight[]" placeholder="total pallat_weight orange">

                                             <input type="hidden" class="form-control total_weight" name="total_weight[]" placeholder="total_weight">






                                                    



                                            <input type="hidden" class="form-control itotal_cost" name="itotal_cost[]" value="{{@$detail->itotal_cost}}" >

                                            <input type="hidden" class="form-control rean_cost" name="rean_cost[]" value="{{@$detail->rean_cost}}">
                                            <input type="hidden" class="form-control rclearance" name="rclearance[]" value="{{@$detail->rclearance}}">
                                            <input type="hidden" class="form-control rchamber" name="rchamber[]" value="{{@$detail->rchamber}}">
                                            <input type="hidden" class="form-control rtruck" name="rtruck[]" value="{{@$detail->rtruck}}">
                                            <input type="hidden" class="form-control rpallets" name="rpallets[]" value="{{@$detail->rpallets}}">
                                            <input type="hidden" class="form-control rifreight" name="rifreight[]" value="{{@$detail->rifreight}}">






                                        </td>
                                        <td data-label="Unit Price">
                                            <div class="input-group">
                                                <input class="form-control unit_price" type="text" name="unit_price[]" placeholder="" value="{{ $detail->unit_price }}" required onclick="return false">
                                                @php($cur = \App\CurrencyModel::where('id',$row->currency_id)->first())
                                                <div class="input-group-append"><span class="input-group-text currency_text">{{ $cur->currency }}</span></div>
                                            </div>
                                        </td>
                                        <td data-label="Profit">
                                            <input class="form-control profit" type="text" name="profit[]" placeholder="" value="{{ $detail->profit }}" readonly>
                                            <input class="form-control fob" type="hidden" name="fob[]" placeholder="fob" value="{{ $detail->fob }}" readonly>
                                        </td>
                                        <td data-label="Action">
                                            <button type="button" class="btn btn-outline-primary calculate-row" value="{{$key+1}}" title="Calculate"><i class="fas fa-square-root-alt"></i></button>
                                           <!--  <button type="button" class="btn btn-outline-success update-row" title="Update QTY"><i class="fas fa-square-root-alt"></i></button> -->
										   <!--@if($key > 0)
												<button  type="button" class="btn btn-danger delete-row" data-id="{{ $detail->id }}" data-timing="remove" title="Delete"><i class="far fa-trash-alt"></i></button>
											@else
												<button type="button" class="btn btn-danger Reset"  data-timing="Reset" onclick="resetFirstRow()" title="Reset"><i class="fa fa-undo" aria-hidden="true"></i></button>
											@endif-->
                                            @if($key > 0)
												@if($detail->isDone=='1') 
												<button  type="button" class="btn btn-danger delete-row" data-id="{{ $detail->id }}" data-timing="remove" title="Delete" disabled><i class="far fa-trash-alt"></i></button>
												@else
												<button  type="button" class="btn btn-danger delete-row" data-id="{{ $detail->id }}" data-timing="remove" title="Delete"><i class="far fa-trash-alt"></i></button>
												@endif
											@else
												@if($detail->isDone=='1') 
												<button type="button" class="btn btn-danger Reset"  data-timing="Reset" onclick="resetFirstRow()" title="Reset" disabled><i class="fa fa-undo" aria-hidden="true"></i></button>
												@else
												<button type="button" class="btn btn-danger Reset"  data-timing="Reset" onclick="resetFirstRow()" title="Reset"><i class="fa fa-undo" aria-hidden="true"></i></button>
												@endif
											@endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row itm">
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Items :</strong>
                                <button type="button" class="btn btn-xs btn-success add-row" >add</button>
                            </div>

                        </div>
                        <hr>
                       <div class="row">
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total Box :</strong> <span class="buttom_vl" id="span_box">{{ $row->total_box }}</span>
                                <input type="hidden" id="total_box" name="total_box" value="{{ $row->total_box }}">
                            </div>
                            <div class="col-lg-2">

                                <strong style="font-size:18px">Total Items :</strong> <span class="buttom_vl" id="span_Item">{{ count($details) }}</span>

                                <input type="hidden" id="total_item" name="total_item" value="{{ count($details) }}" >

                            </div>

                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total NW :</strong> <span class="buttom_vl" id="span_nw">{{ number_format($row->total_nw,2) }}</span>
                                <input type="hidden" id="total_nw" name="total_nw" value="{{ $row->total_nw }}">
                            </div>
                            <div class="col-lg-2">

                                <strong style="font-size:18px">Total CNF :</strong> <span class="buttom_vl" id="span_fcnft">{{ number_format($row->total_cnf,2) }}</span>

                                <input type="hidden" id="total_cnf" name="total_cnf" value="{{ $row->total_cnf }}">

                            </div>

                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total GW :</strong> <span class="buttom_vl" id="span_gw">{{ $row->total_gw }}</span>
                                <input type="hidden" id="total_gw" name="total_gw" value="{{ $row->total_gw }}">
                            </div>
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total CBM :</strong> <span class="buttom_vl" id="span_cbm">{{ $row->total_cbm }}</span>
                                <input type="hidden" id="total_cbm" name="total_cbm" value="{{ $row->total_cbm }}">
                            </div>
                            <div class="col-lg-2" id="hid_palletizad" style="@if($row->select_pallet == 'no') display:none @endif">
                                <strong style="font-size:18px">Pallet :</strong> <span id="span_palletized">{{ $row->palletized }}</span>
                                <input type="hidden" class="" name="palletized" id="palletized" value="{{ $row->palletized }}">
                                <input type="hidden" class="" name="palletized_price" id="palletized_price" value="{{ $row->palletized }}">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <?php


                                $total_fob= floatval(preg_replace("/[^-0-9\.]/","",$row->total_fob));
        $total_freight= floatval(preg_replace("/[^-0-9\.]/","",$row->total_freight));


        ?>
                                <strong style="font-size:18px">Total Freight :</strong> <span class="buttom_vl" id="span_freight">{{ number_format($total_freight,2) }}</span>
                                <input type="hidden" id="total_freight" name="total_freight" value="{{ round($total_freight,2) }}">
                            </div>
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total FOB :</strong> <span id="span_fob">{{ number_format($total_fob,2) }}</span>
                                <input type="hidden" id="total_fob" name="total_fob" value="{{ round($total_fob,2) }}">
                            </div>
                            <div class="col-lg-3">
                                <strong style="font-size:18px">Profit before Rebate :</strong> <span class="buttom_vl" id="span_pro_before_rebate">{{ number_format($row->total_pro_before_rebate,2) }}</span>
                                <input type="hidden" id="total_pro_before_rebate" name="total_pro_before_rebate" value="{{ $row->total_pro_before_rebate }}">
                            </div>
                            <div class="col-lg-3">
                                <strong style="font-size:18px">Profit after Rebate :</strong> <span class="buttom_vl" id="span_pro_after_rebate">{{ number_format($row->total_pro_after_rebate,2) }}</span>
                                <input type="hidden" id="total_pro_after_rebate" name="total_pro_after_rebate" value="{{ $row->total_pro_after_rebate }}">
                            </div>
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Profit(%) :</strong> <span class="buttom_vl" id="span_pro_percent">{{ $row->total_pro_percent }}</span>
                                <input type="hidden" id="total_pro_percent" name="total_pro_percent" value="{{ $row->total_pro_percent }}">
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{-- <button class="btn btn-success btn_recost" type="button">Confirm</button> --}}
                        <button class="btn btn-primary update" type="submit">Update</button>
                        <a class="btn btn-danger cancel" href="{{url("/$folder")}}">Cancel</a>
                    </div>
                </form>
            </div>            
        </div>
    </div>              
</div>
{{-- <script type="text/javascript">
    document.onkeydown=function(e) 
    {
        e=e||window.event;
        ///prevent F5 refresh page
        if (e.keyCode === 116 ) 
        {
            e.keyCode = 0;
            if(e.preventDefault)
                e.preventDefault();
            else 
                e.returnValue = false;
            return false;
        }

        switch (event.keyCode) { 
        case 116 : //F5 button
            event.returnValue = false;
            event.keyCode = 0;
            return false; 
        case 82 : //R button
            if (event.ctrlKey) { 
                event.returnValue = false; 
                event.keyCode = 0;  
                return false; 
            } 
        }
    }
</script>    --}}





















<input type="hidden" id="isCallables" value="0">








    



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

<div id="myModal" class="modal mslC">



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
