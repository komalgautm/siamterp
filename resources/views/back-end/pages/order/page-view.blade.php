<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="viewForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Order Management</a></span>
                        <span class="breadcrumb-item active">View Form</span>
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
                                    @php $user = \App\UserModel::where('id',$row->user_id)->first(); @endphp 
                                    <input type="text" id="user_name" name="user_name" class="form-control" value="{{ $user->name }}" readonly>
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="form-group col-lg-4">
                                        <span><b>Client :</b> {{ @$clients->name }}</span> <br>
                                        <span><b>Ship To :</b> {{ @$shipto->name }}</span> <br>
                                        <span><b>Airport :</b> {{ @$airports->country.' - '.$airports->city.' ['.$airports->airport_code.']' }}</span> <br>
                                        <span><b>Airline :</b> {{ @$airlines->name.' ['.$airlines->airline_code.']' }}</span> <br>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <span><b>Currency :</b> {{ $currencys->currency }}</span> <br>
                                        <span><b>EX Rate :</b> {{ $row->ex_rate }}</span> <br>
                                        <span><b>Markup Rate :</b> {{ $row->markup_rate }}</span> <br>
                                        <span><b>Rebate :</b> {{ $row->rebate }}</span> <br>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <span><b>Clearance :</b> {{ $clearance->name }}</span> <br>
                                        <span><b>Palletized :</b> {{ $row->select_pallet }}</span> <br>
                                        <span><b>CO from CHamber :</b> {{ $row->select_chamber }}</span> <br>
                                        <span><b>Ship Date :</b> {{ date('d/m/Y',strtotime($row->ship_date)) }}</span> <br>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="form-group col-lg-6">
                                    <button type="button" class="btn btn-xs btn-success" onclick="cal()">Calculate</button><br>
                                    <small style="color:red"><b>**ทุกครั้งที่มีการเปลี่ยนแปลงข้อมูลกรุณากดปุ่ม Calculate**</b></small><br>
                                    <small style="color:red"><b>**Every time information is changed, please press the button Calculate**</b></small>
                                </div>
                            </div> --}}
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
                                        <th width="5%">#</th>
                                        <th width="15%">ITF</th>
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Unit</th>
                                        <th width="10%">Number of Box</th>
                                        <th width="10%">NW</th>
                                        <th width="10%">Unit Price</th>
                                        {{-- <th width="8%">Crate</th> --}}
                                        <th width="10%">Profit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $key => $detail)
                                    <tr id="row{{ $key+1 }}" data-id="{{ $key+1 }}">
                                        <td data-label="#">{{ $key+1 }}</td>
                                        <td data-label="ITF">
                                            @php $itf = \App\ITFModel::where('id',$detail->itf_id)->first(); @endphp
                                            {{ $itf->name }}
                                        </td>
                                        <td data-label="Quantity">
                                            {{ $detail->qty }}
                                        </td>
                                        <td data-label="Unit">
                                            @php $unit = \App\UnitCountModel::where('id',$detail->unitcount_id)->first(); @endphp
                                            {{ $unit->name_th.'/'.$unit->name_en }}
                                        </td>
                                        <td data-label="Number of Box">
                                            {{ $detail->number_box }}
                                        </td>
                                        <td data-label="NW">
                                            {{ $detail->nw }}
                                        </td>
                                        <td data-label="Unit Price">
                                            <div class="input-group">
                                                {{ $detail->unit_price }} {{ $currencys->currency }}
                                            </div>
                                        </td>
                                        <td data-label="Profit">
                                            {{ $detail->profit }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        {{-- <div class="row">
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Items :</strong>
                                <button type="button" class="btn btn-xs btn-success add-row" >add</button>
                            </div>

                        </div> --}}
                        <hr>
                        <div class="row">
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total Box :</strong> <span id="span_box">{{ $row->total_box }}</span>
                            </div>
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total NW :</strong> <span id="span_nw">{{ $row->total_nw }}</span>
                            </div>
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total GW :</strong> <span id="span_gw">{{ $row->total_gw }}</span>
                            </div>
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total CBM :</strong> <span id="span_cbm">{{ $row->total_cbm }}</span>
                            </div>
                            <div class="col-lg-2" id="hid_palletizad" style="display:none">
                                <strong style="font-size:18px">Pallet :</strong> <span id="span_palletized">{{ $row->palletized }}</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total Freight :</strong> <span id="span_freight">{{ $row->total_freight }}</span>
                            </div>
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Total FOB :</strong> <span id="span_fob">{{ $row->total_fob }}</span>
                            </div>
                            <div class="col-lg-3">
                                <strong style="font-size:18px">Profit before Rebate :</strong> <span id="span_pro_before_rebate">{{ $row->total_pro_before_rebate }}</span>
                            </div>
                            <div class="col-lg-3">
                                <strong style="font-size:18px">Profit after Rebate :</strong> <span id="span_pro_after_rebate">{{ $row->total_pro_after_rebate }}</span>
                            </div>
                            <div class="col-lg-2">
                                <strong style="font-size:18px">Profit(%) :</strong> <span id="span_pro_percent">{{ $row->total_pro_percent }}</span>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        {{-- <button class="btn btn-primary" type="submit" name="signup">Update</button> --}}
                        <a class="btn btn-danger" href="{{url("/$folder")}}">Back</a>                    
                    </div>
                </form>
            </div>            
        </div>
    </div>              
</div>         

        