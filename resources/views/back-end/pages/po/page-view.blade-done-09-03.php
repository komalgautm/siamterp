@php($province = \App\ProvincesModel::select('name_th')->where('id',$vendor->provinces)->first())
@php($district = \App\DistrictModel::select('name_th')->where('id',$vendor->district)->first())
@php($subdistrict = \App\SubdistrictModel::select('name_th','zipcode')->where('id',$vendor->subdistrict)->first())
<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="viewForm" method="post" action="" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Purchase Order Management</a></span>
                        <span class="breadcrumb-item active">View Form</span>
                        <div class="card-header-actions"><small class="text-muted"><a href="https://getbootstrap.com/docs/4.0/components/input-group/#custom-file-input">docs</a></small></div>
                    </div>
                    <div class="card-body">
                        @csrf
                        
                        <!-- <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#TH">TH</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#EN">EN</a></li>
                        </ul> -->
                        <div class="tab-content" id="myTabContent">
                            <br>
                            <div class="tab-pane fade show active" id="TH" aria-labelledby="home-tab">
                                <div class="row"> 
                                    <div class="form-group col-lg-4">
                                        <h6>Code</h6>
                                    <input type="text" id="code" name="code" class="form-control" placeholder="" value="{{ $row->code }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Create Date</h6>
                                    <input type="text" id="created" name="created" class="form-control" placeholder="" value="{{ date('d-M-Y',strtotime($row->created)) }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Create By</h6>
                                    <input type="hidden" id="user" name="user" value="{{ $user->id }}">
                                    <input type="text" id="user_name" name="user_name" class="form-control" value="{{ $user->name }}" readonly>
                                    </div>
                                    
                                </div>   
                            </div>
                            <!-- <div class="tab-pane fade" id="EN" aria-labelledby="home-tab">
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
                            </div> -->
                        </div>
                        <hr>
                        {{-- <div class="row">
                            <div class="header">
                                <div class="col-lg-12">
                                    <strong style="font-size:18px">Vendor Info :</strong>
                                </div>
                            </div>
                        </div> --}}
                        <br>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <b>Vendor Info</b> <br><br>
                                <span><b>Name :</b> {{ $vendor->name }}({{ $vendor->id_card }})</span> <br>
                                <span><b>Address :</b> {!! $vendor->address !!} <br> {{ $subdistrict->name_th.$district->name_th.$province->name_th." ".$subdistrict->zipcode }}</span> <br>
                                <span><b>Contact :</b> {{ $vendor->phone }} / {{ $vendor->email }}</span> <br>
                                <span><b>Line ID :</b> {{ $vendor->line_id }}</span> <br>
                            </div>
                            <div class="form-group col-lg-4">
                                <b>Order History</b> <br><br>
                                <span><b>Create Date :</b> {{ date('d-M-Y H:i:s',strtotime($row->created)) }}</span> <br>
                                @if($row->pickup_date != '')<span><b>Pick Up Date :</b> {{ date('d-M-Y H:i:s',strtotime($row->pickup_date)) }}</span> <br>@endif
                                @if($row->delivery_date != '')<span><b>Delivery Date :</b> {{ date('d-M-Y H:i:s',strtotime($row->delivery_date)) }}</span> <br>@endif
                                @if($row->receive_date != '')<span><b>Receive Date :</b> {{ date('d-M-Y H:i:s',strtotime($row->receive_date)) }}</span> @endif
                                
                            </div>
                            <div class="form-group col-lg-4">
                                <b>Payment</b> <br><br>
                                <span><b>Totals :</b> {{ number_format($total,2) }} THB</span> <br>
                                <span><b>Bank Name :</b> {{ $vendor->bank_name }}</span> <br>
                                <span><b>Bank Account :</b> {{ $vendor->bank_account }}</span> <br>
                                <span><b>Bank Number :</b> {{ $vendor->bank_number }}</span> <br>
                                @if($row->paid_date != '')<span><b>Paid On :</b> {{ date('d-M-Y',strtotime($row->paid_date)) }}</span> <br>@endif
                                @if($row->paid_by != '')<span><b>Paid With :</b> @if($row->paid_by != 'staff'){{ $row->paid_by }} @else {{ $row->staff_name }} @endif</span> @endif
                            </div>
                        </div>
                        
                        {{-- <div class="row">
                            <div class="form-group col-lg-4">
                                <h6>Bank Name</h6>
                                <input type="text" class="form-control" id="bank_name" name="bank_name" value="{{ $vendor->bank_name }}" readonly>
                            </div>
                            <div class="form-group col-lg-4">
                                <h6>Bank Account</h6>
                                <input type="text" class="form-control" id="bank_account" name="bank_account" value="{{ $vendor->bank_account }}" readonly>
                            </div>
                            <div class="form-group col-lg-4">
                                <h6>Bank Number</h6>
                                <input type="text" class="form-control" id="bank_number" name="bank_number" value="{{ $vendor->bank_number }}" readonly>
                            </div>
                        </div> --}}
                        <hr>
                        <div class="row">
                            <div class="header">
                                <div class="col-lg-12">
                                    <strong style="font-size:18px">Items Info :</strong>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="table-responsive">
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important" width="100%">
                                <thead>
                                    <tr role="">
                                        <th width="5%">#</th>
                                        <th width="10%">Type</th>
                                        <th width="10%">Name</th>
                                        <th width="10%">Barcode</th>
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Unit</th>
                                        <th width="10%">Price</th>
                                        <th width="10%">Crate</th>
                                        <th width="10%">Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($import as $key => $item)
                                    <tr>
                                        <td data-label="No.">
                                            {{ $key+1 }}
                                        </td>
                                        <td data-label="Type">
                                            {{ $item->type }}
                                        </td>
                                        <td data-label="Name">
                                            @if($item->type == 'item') {{ $item->name_th_item.' / '.$item->name_en_item }}
                                            @else {{ $item->name_th_item }} @endif
                                        </td>
                                        <td>
                                            {{-- {!! DNS1D::getBarcodeHTML($item->barcode, 'C128') !!} --}}
                                            {{-- <img src="data:image/png;base64,{{DNS1D::getBarcodePNG($item->barcode, 'C128')}}" alt="barcode"/> --}}
                                            <span>{{ $item->barcode }}</span>
                                        </td>
                                        <td data-label="Quantity">
                                            {{ $item->quantity }}
                                        </td>
                                        <td data-label="Unit">
                                           @if($item->name_th_unit != '') {{ $item->name_th_unit.'/'.$item->name_en_unit }} @endif
                                        </td>
                                        <td data-label="Price">
                                            {{ 'THB '.number_format($item->price,2) }}
                                        </td>
                                        <td data-label="Crate">
                                            {{ $item->crate }}
                                        </td>
                                        <td data-label="Total">
                                            {{ 'THB '.number_format($item->total_price,2) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <br>
                        <div class="row">
                            <div class="col-lg-3">
                                <select class="form-control " name="status"  id="status" @if($row->status == 'receive' || $row->status == 'cancel'||$row->status== 'delivery') disabled @endif>
                                    <option value="pending" @if($row->status == 'pending') selected @endif>Pending</option>
                                    <option value="pickup" @if($row->status == 'pickup') selected @endif>Picked Up</option>
                                    <option value="delivery" @if($row->status == 'delivery')  selected @endif>Delivery</option>
                                    @if($row->status == 'receive')
                                    <option value="receive" @if($row->status == 'receive') selected @endif>Receive</option>
                                    @endif
                                </select>
                            </div>
                        </div>
                        <br>
                    <div class="card-footer">
                        <div class="row">
                            @if($row->status != 'receive' && $row->status != 'cancel'&& $row->status != 'delivery')
                            <button class="btn btn-primary" type="submit" name="signup">Update</button>
                            @endif
                            <a class="btn btn-danger" href="{{url("/$folder")}}">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>            
        </div>
    </div>              
</div>         

        