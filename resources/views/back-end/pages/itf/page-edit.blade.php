<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="editForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Products Setup Management</a></span>
                        <span class="breadcrumb-item active">Edit Form</span>
                        <div class="card-header-actions"><small class="text-muted"><a href="https://getbootstrap.com/docs/4.0/components/input-group/#custom-file-input">docs</a></small></div>
                    </div>
                    <style type="text/css"> 
                    .ean_ppITF{display:none;}</style>
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
                                    <div class="form-group col-lg-3">
                                        <h6>Code</h6>
                                    <input type="text" id="code" name="code" class="form-control" placeholder="" value="{{ $row->code }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Setup Date</h6>
                                    <input type="text" id="created" name="created" class="form-control" placeholder="" value="{{ date('d-M-Y',strtotime($row->setup_date)) }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Create By</h6>
                                    <input type="hidden" id="user" name="user" value="{{ $row->user }}">
                                    @php($user = \App\UserModel::where('id',$row->user)->first())
                                    <input type="text" id="user_name" name="user_name" class="form-control" value="{{ $user->name }}" readonly>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Product Name</h6>
                                        <input type="text" class="form-control" id="product_name" name="product_name" value="{{ $row->name }}" @if(Auth::user()->role != 'superadmin' && Auth::user()->role != 'admin') readonly @endif>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3">
                                        <b>Product Weight :</b> <span id="total_weight">@if($row->new_weight != 0){{ $row->new_weight }}@else {{ $row->all_weight }} @endif KG</span> <br>
                                        <b>Net Weight :</b> <span id="net_weight">{{ $row->net_weight }}</span> <br>
                                        <b>CBM :</b> <span id="cbm">{{ $row->maxcbm }}</span> <br>
                                        <b>MLW :</b> <span id="minload">{{ $row->maxminload }}</span> <br>
                                        <b>Box/Pallet :</b> <span id="box_pallet">{{ $row->maxbox_pallet }}</span> <br>
                                        <input type="hidden" class="all_weight" name="all_weight" value="{{ $row->all_weight }}">
                                        <input type="hidden" class="net_weight" name="net_weight" value="{{ $row->net_weight }}">
                                        <input type="hidden" class="maxcbm" name="maxcbm" value="{{ $row->maxcbm }}">
                                        <input type="hidden" class="maxminload" name="maxminload" value="{{ $row->maxminload }}">
                                        <input type="hidden" class="maxbox_pallet" name="maxbox_pallet" value="{{ $row->maxbox_pallet }}">
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Weight Adjustment</h6>
                                        <input type="text" class="form-control adjust_weight" name="adjust_weight" value="{{ $row->adjust_weight }}">
                                        <input type="hidden" id="new_weight" name="new_weight" value="{{ $row->new_weight }}">
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-6">
                                        <button type="button" class="btn btn-xs btn-success" onclick="cal()">Calculate</button><br>
                                        <small style="color:red"><b>**ทุกครั้งที่มีการเปลี่ยนแปลงข้อมูลกรุณากดปุ่ม Calculate**</b></small><br>
                                        <small style="color:red"><b>**Every time information is changed, please press the button Calculate**</b></small>
                                    </div>
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
                                        <th width="15%">Type</th>
                                        <th width="25%">Name</th>
                                        <th width="15%">Quantity</th>
                                        <th width="10%">Unit</th>
                                        <th width="20%">Total Weights</th>
                                        <th width="1%" style="display: none;">EAN PP ITF</th>
                                        {{-- <th width="8%">Crate</th>
                                        <th width="12%">Total</th> --}}
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($setup as $key => $set)
                                    <tr id="row{{ $key+1 }}" data-id="{{ $key+1 }}">
                                        <td data-label="Type">
                                            <input type="hidden" name="set_id[]" value="{{ $set->id }}">
                                            <select name="type[]" id="type1" class="form-control type">
                                                <option value="">Select Type</option>
                                                <option value="ean" @if($set->type == 'ean') selected @endif>EAN</option>
                                                <option value="boxes" @if($set->type == 'boxes') selected @endif>Boxes</option>
                                                <option value="packaging" @if($set->type == 'packaging') selected @endif>Packaging</option>
                                            </select>
                                        </td>
                                        <td data-label="Name">
                                            <select name="item[]" id="item{{ $key+1 }}" class="form-control select_item select2" style="width:100%" data-type="{{ $set->type }}">
                                                <option value="">Select Item</option>
                                                @if($set->type == 'ean')
                                                @foreach ($item as $itm)
                                                <option value="{{ $itm->id }}" @if($set->item == $itm->id) selected @endif>{{ $itm->name }}</option>
                                                @endforeach
                                                @elseif($set->type == 'boxes')
                                                @foreach ($box as $box)
                                                <option value="{{ $box->id }}" @if($set->item == $box->id) selected @endif>{{ $box->name_th }}</option>
                                                @endforeach
                                                @elseif($set->type == 'packaging')
                                                @foreach ($packaging as $pack)
                                                <option value="{{ $pack->id }}" @if($set->item == $pack->id) selected @endif>{{ $pack->name_th }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                            <input type="hidden" class="total_qty" name="total_qty[]" value="{{ $set->ean_qty }}">
                                        </td>
                                        <td data-label="Quantity">
                                            <input type="number" class="form-control qty" name="quantity[]" step="0.01" min="0" placeholder="Enter quantity" data-type="{{ $set->type }}" value="{{ $set->qty }}">
                                            <input type="hidden" class="weight" name="weight[]" value="{{ $set->weight }}">
                                            <input type="hidden" class="wrap_weight" name="wrap_weight[]" value="{{ $set->wrap_weight }}">
                                            <input type="hidden" class="cbm" name="cbm[]" value="{{ $set->cbm }}">
                                            <input type="hidden" class="minload" name="minload[]" value="{{ $set->minload }}">
                                            <input type="hidden" class="box_pallet" name="box_pallet[]" value="{{ $set->box_pallet }}">
                                            <input type="hidden" class="eanQty" name="eanQty[]" value="{{ $set->eanQty }}">
                                        </td>
                                        <td data-label="Unit">
                                            <select name="unitcount[]" id="unitcount{{ $key+1 }}" class="form-control unitcount select2" style="width:100%">
                                                <option value="">Select Unit</option>
                                                @foreach ($unit as $u)
                                                <option value="{{ $u->id }}" @if($set->unit == $u->id) selected @endif>{{ $u->unit_th.' / '.$u->unit_en }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td data-label="Total Weights">
                                            <input type="text" class="form-control total_weight" name="total_weight[]" value="{{ $set->total_weight }}" placeholder="" readonly>
                                        </td>
                                         <td data-label="Ean PP  itf">
                                            <input type="text" class="form-control ean_ppITF" name="ean_ppITF[]" value="{{ $set->ean_ppITF }}" placeholder="" readonly>
                                        </td>
                                        <td data-label="Action">@if($key > 0) <a href="javascript:" class="btn btn-danger delete-row" data-id="{{ $set->id }}" data-timing="remove" title="Delete"><i class="far fa-trash-alt"></i></a> @endif &nbsp;</td>
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
                                    <button type="button" class="btn btn-xs btn-success add-row" >add</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit" name="signup">Update</button>
                        <a class="btn btn-danger" href="{{url("/$folder")}}">Cancel</a>                    
                    </div>
                </form>
            </div>            
        </div>
    </div>              
</div>         

        