<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="createForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Invoice Management</a></span>
                        <span class="breadcrumb-item active">Create Form</span>
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
                                        <input type="text" id="code" name="code" class="form-control" placeholder="" value="{{ $code }}" readonly/>
                                    </div>
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
                                        <select class="form-control select2" name="client" id="client" style="width:100%" required>
                                            <option value="">Select client</option>
                                            @foreach ($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Ship To</h6>
                                        <select class="form-control select2" name="shipto" id="shipto" style="width:100%" required>
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
                                        {{-- <input type="text" class="" name="rate" id="rate" value="" readonly>
                                        <input type="text" class="" name="freight_rate_total" id="freight_rate_total" value="" readonly> --}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3">
                                        <h6>Currency</h6>
                                        <select class="form-control" name="currency" id="currency">
                                            <option value="">Select currency</option>
                                            @foreach ($currencys as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->currency }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>EX Rate</h6>
                                        <div class="input-group">
                                            <input class="form-control" type="text" name="ex_rate" id="ex_rate" placeholder="">
                                            <div class="input-group-append"><span class="input-group-text">THB</span></div>
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
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Palletized</h6>
                                        <select class="form-control" name="select_pallet" id="select_pallet">
                                            <option value="no">No</option>
                                            <option value="yes">Yes</option>
                                        </select>
                                        <input type="hidden" class="" name="weight_pallet" id="weight_pallet" value="8.5" readonly>
                                        <input type="hidden" class="" name="price_pallet" id="price_pallet" value="368.5" readonly>
                                        <input type="hidden" class="" name="cbm_pallet" id="cbm_pallet" value="0.15" readonly>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>CO from CHamber</h6>
                                        <select class="form-control" name="select_chamber" id="select_chamber">
                                            <option value="no">No</option>
                                            <option value="yes">Yes</option>
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
                                        <th width="15%">Unit Price</th>
                                        {{-- <th width="8%">Crate</th>
                                        <th width="12%">Total</th> --}}
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="row1" data-id="1">
                                        <td data-label="ITF">
                                            <select name="itf[]" id="itf1" class="form-control select_itf select2" style="width:100%">
                                                <option value="">Select ITF</option>
                                                @foreach ($itfs as $itf)
                                                <option value="{{ $itf->id }}">{{ $itf->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td data-label="Quantity">
                                            <input type="number" class="form-control qty" name="quantity[]" placeholder="Enter quantity" value="">
                                            <input type="hidden" class="ean_qty" name="ean_qty[]" placeholder="ean_qty">
                                            <input type="hidden" class="net_weight" name="net_weight[]" placeholder="net_weight">
                                            <input type="hidden" class="new_weight" name="new_weight[]" placeholder="new_weight">
                                            <input type="hidden" class="maxcbm" name="maxcbm[]" placeholder="maxcbm">
                                            <input type="hidden" class="maxpallet" name="maxpallet[]" placeholder="maxpallet">
                                            {{-- <input type="text" class="packing_id" name="packing_id" placeholder="packing_id">
                                            <input type="text" class="cost" name="cost" placeholder="cost">
                                            <input type="text" class="setup" name="setup" placeholder="setup"> --}}
                                        </td>
                                        <td data-label="Unit">
                                            <select name="unitcount[]" id="unitcount1" class="form-control unitcount" style="width:100%">
                                                <option value="">Select Unit</option>
                                                @foreach ($units as $u)
                                                <option value="{{ $u->id }}">{{ $u->name_th.' / '.$u->name_en }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td data-label="Number of Box">
                                            <input type="text" class="form-control number_box" name="number_box[]" value="" placeholder="" readonly>
                                        </td>
                                        <td data-label="NW">
                                            <input type="text" class="form-control nw" name="nw[]" value="" placeholder="nw_weight" readonly>
                                            <input type="hidden" class="form-control gw_weight" name="gw_weight[]" placeholder="gw_weight" readonly>
                                            <input type="hidden" class="form-control cbm" name="cbm[]" placeholder="cbm" readonly>
                                            <input type="hidden" class="form-control pallet" name="pallet[]" placeholder="pallet" readonly>
                                            <input type="hidden" class="form-control price_allocation" name="price_allocation[]" placeholder="price_allocation" readonly>
                                            <input type="hidden" class="form-control price_pallet_unit" name="price_pallet_unit[]" placeholder="price_pallet_unit" readonly>
                                            <input type="hidden" class="form-control itf_pallet_price" name="itf_pallet_price[]" placeholder="itf_pallet_price" readonly>
                                            <input type="hidden" class="form-control itf_clearance_price" name="itf_clearance_price[]" placeholder="itf_clearance_price" readonly>
                                            <input type="hidden" class="form-control itf_transport_price" name="itf_transport_price[]" placeholder="itf_transport_price" readonly>
                                            <input type="hidden" class="form-control itf_cost_price" name="itf_cost_price[]" placeholder="itf_cost_price" readonly>
                                            <input type="hidden" class="form-control freight_rate" name="freight_rate[]" placeholder="freight_rate" readonly>
                                            <input type="hidden" class="form-control total_itf_cost" name="total_itf_cost[]" placeholder="total_itf_cost" readonly>
                                        </td>
                                        <td data-label="Unit Price">
                                            <div class="input-group">
                                                <input class="form-control unit_price" type="text" name="unit_price[]" placeholder="" readonly>
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
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
                                <strong style="font-size:18px">Total Box :</strong> <span id="span_box"></span>
                                <input type="hidden" id="total_box" name="total_box">
                            </div>
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total NW :</strong> <span id="span_nw"></span>
                                <input type="hidden" id="total_nw" name="total_nw">
                            </div>
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total GW :</strong> <span id="span_gw"></span>
                                <input type="hidden" id="total_gw" name="total_gw">
                            </div>
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total CBM :</strong> <span id="span_cbm"></span>
                                <input type="hidden" id="total_cbm" name="total_cbm">
                            </div>
                            <div class="col-lg-2" id="hid_palletizad" style="display:none">
                                <strong style="font-size:18px">Pallet :</strong> <span id="span_palletized"></span>
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

        