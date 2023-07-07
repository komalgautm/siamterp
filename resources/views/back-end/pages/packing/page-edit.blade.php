<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="createForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Packing Management</a></span>
                        <span class="breadcrumb-item active">Create Form</span>
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
                                    <div class="form-group col-lg-3">
                                        <h6>Item</h6>
                                        <select class="form-control select_item select2" name="item" id="item" width="100%" required>
                                            <option value="">Select Item</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}" @if($row->item == $item->id) selected @endif>{{ $item->name_th." / ".$item->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Unit Count</h6>
                                        <select class="form-control unitcount select2" name="unit" width="100%" required>
                                            <option value="">Select Unit</option>
                                            @foreach($units as $unit)
                                                <option value="{{ $unit->id }}" @if($row->unit == $unit->id) selected @endif>{{ $unit->name_th." / ".$unit->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <b>Code :</b> <span id="code">{{ $row->code }}</span> <br>
                                        <b>Quantity :</b> <span id="quantity">{{ $row->sorting_qty }}</span> <br>
                                        <b>Unit :</b> <span id="unit">{{ $row->name_th." / ".$row->name_en }}</span> <br>
                                        <b>Blue crate :</b> <span id="blue_crate">{{ $row->blue_crate }}</span>
                                        <input type="hidden" id="sorting_id" name="sorting_id" value="{{ $row->sorting_id }}">
                                        <input type="hidden" id="balance" name="balance" value="{{ $row->sorting_qty }}">
                                        <input type="hidden" id="cost_asl" name="cost_asl" value="{{ $row->cost_asl }}">
                                        <input type="hidden" id="po_price" name="po_price" value="{{ $row->po_price }}">
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Quantity</h6>
                                        <input type="text" class="form-control" id="qty" name="qty" value="{{ $row->qty }}" required>
                                        <input type="hidden" id="wastage" name="wastage" placeholder="wastage" value="{{ $row->wastage }}">
                                        <input type="hidden" id="wastage_weight" name="wastage_weight" placeholder="wastage_weight" value="{{ $row->wastage_weight }}">
                                        <input type="hidden" id="cost" name="cost" placeholder="cost" value="{{ $row->cost }}">
                                        <input type="hidden" class="form-control" id="ean_cost" name="ean_cost" placeholder="Auto Calculate" value="{{ $row->ean_cost }}" readonly>
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
                        <div class="row">
                            <div class="col-lg-4">
                                <h6>Number of Staff</h6>
                                <input type="text" class="form-control" id="number_staff" name="number_staff" value="{{ $row->number_staff }}" required>
                                <input type="hidden" class="form-control" id="wages" name="wages" placeholder="Auto Calculate" value="{{ $row->wages }}" readonly>
                            </div>
                            <div class="col-lg-4">
                                <h6>Start</h6>
                                <input type="time" class="form-control" id="start_time" name="start" value="{{ date('H:i',strtotime($row->start)) }}" required>
                            </div>
                            <div class="col-lg-4">
                                <h6>Finish</h6>
                                <input type="time" class="form-control" id="finish_time" name="finish" value="{{ date('H:i',strtotime($row->finish)) }}" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            {{-- <div class="form-group col-lg-6">
                                <small style="color:red"><b>**ทุกครั้งที่มีการเปลี่ยนแปลงข้อมูลกรุณากดปุ่ม Calculate**</b></small><br>
                                <small style="color:red"><b>**Every time information is changed, please press the button Calculate**</b></small><br>
                                <button type="button" class="btn btn-xs btn-success" >Calculate</button>
                            </div> --}}
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important" width="100%">
                                <thead>
                                    <tr role="">
                                        <th width="10%">EAN</th>
                                        <th width="10%">Quantity</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $key => $detail)
                                    <tr id="row1" data-id="1">
                                        <td data-label="EAN">
                                            <select name="ean[]" id="ean1" class="form-control select_ean select2" style="width:100%" required>
                                                <option value="">Select EAN</option>
                                                @foreach ($eans as $ean)
                                                <option value="{{ $ean->id }}" @if($detail->product == $ean->id) selected @endif>{{ $ean->name }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td data-label="Quantity">
                                            <input type="number" class="form-control qty" name="quantity[]" min="0" placeholder="Enter quantity" value="{{ $detail->number_pack }}" required>
                                            <input type="hidden" class="weight" name="weight[]" value="{{ $detail->product_weight }}">
                                            <input type="hidden" class="cost_packaging" name="cost_packaging[]" value="{{ $detail->cost_packaging }}">
                                            <input type="hidden" class="wrap_cost" name="wrap_cost[]" value="{{ $detail->wrap_cost }}">
                                            <input type="hidden" class="plus_cost" name="plus_cost[]" value="{{ $detail->plus_cost }}">
                                        </td>
                                        <td data-label="Action">@if($key > 0) <a href="javascript:" class="btn btn-danger delete-row" data-id="{{ $detail->id }}" data-timing="remove" title="Delete"><i class="far fa-trash-alt"></i></a> @endif &nbsp;</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="header">
                                <div class="col-lg-12">
                                    <strong style="font-size:18px">Items :</strong>
                                    {{-- <button type="button" class="btn btn-xs btn-success add-row" data-id="{{ $row->item }}">add</button> --}}
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="button" name="signup" onclick="cal()">Update</button>
                        <a class="btn btn-danger" href="{{url("/$folder")}}">Cancel</a>                    
                    </div>
                </form>
            </div>            
        </div>
    </div>              
</div>         

        