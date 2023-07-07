<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="editForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Purchase Order Management</a></span>
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
                                    <div class="form-group col-lg-3">
                                        <h6>Code</h6>
                                    <input type="text" id="code" name="code" class="form-control" placeholder="" value="{{ $row->code }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Create Date</h6>
                                    <input type="date" id="created" name="created" class="form-control" placeholder="" value="{{ date('Y-m-d',strtotime($row->created)) }}" />
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Create By</h6>
                                    <input type="hidden" id="user" name="user" value="{{ $row->user }}">
                                    @php($user = \App\UserModel::where('id',$row->user)->first())
                                    <input type="text" id="user_name" name="user_name" class="form-control" value="{{ $user->name }}" readonly>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Vendor</h6>
                                        <select name="vendor" id="vendor" class="form-control select2" style="width:100%" required>
                                            <option value="">Please select Vendor</option>
                                            @foreach ($vendor as $v)
                                            <option value="{{ $v->id }}" @if($row->vendor == $v->id) selected @endif>{{ $v->name }}</option>
                                            @endforeach
                                        </select>
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
                                        <th width="10%">Type</th>
                                        <th width="5%">Name</th>
                                        <th width="5%">Quantity</th>
                                        <th width="10%">Unit</th>
                                        <th width="12%">Price</th>
                                        <th width="8%">VAT</th>
                                        <th width="12%">Total</th>
                                        <th width="8%">WHT</th>
                                        <th width="8%">Crate</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($import as $key => $imp)
                                    <tr id="row{{ $key+1 }}" data-id="{{ $key+1 }}">
                                        <td data-label="Type">
                                            <input type="hidden" name="imp_id[]" value="{{ $imp->id }}">
                                            <select name="type[]" id="type1" class="form-control type" required>
                                                <option value="">Select Type</option>
                                                <option value="item" @if($imp->type == 'item') selected @endif>Produce</option>
                                                <option value="boxes" @if($imp->type == 'boxes') selected @endif>Boxes</option>
                                                <option value="packaging" @if($imp->type == 'packaging') selected @endif>Packaging</option>
                                            </select>
                                        </td>
                                        <td data-label="Name">
                                            <!--<input type="hidden" class="form-control" name="barcode[]" value="{{ substr($imp->barcode,-3) }}" readonly>-->
                                            <input type="hidden" class="form-control" name="barcode[]" value="{{ substr($imp->barcode,-2) }}" readonly>
                                            <select name="item[]" id="item{{ $key+1 }}" class="form-control select_item select2" style="width:100%" required>
                                                <option value="">Select Item</option>
                                                @if($imp->type == 'item')
                                                @foreach ($item as $itm)
                                                <option value="{{ $itm->id }}" @if($imp->item == $itm->id) selected @endif>{{ $itm->name_th.' / '.$itm->name_en }}</option>
                                                @endforeach
                                                @elseif($imp->type == 'boxes')
                                                @foreach ($boxes as $box)
                                                <option value="{{ $box->id }}" @if($imp->item == $box->id) selected @endif>{{ $box->name_th }}</option>
                                                @endforeach
                                                @elseif($imp->type == 'packaging')
                                                @foreach ($pack as $p)
                                                <option value="{{ $p->id }}" @if($imp->item == $p->id) selected @endif>{{ $p->name_th }}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td data-label="Quantity">
                                            <input type="number" class="form-control qty" name="quantity[]" step="0.01" min="0" placeholder="Enter quantity" value="{{ $imp->quantity }}" required>
                                        </td>
                                        <td data-label="Unit">
                                            <select name="unitcount[]" id="unitcount{{ $key+1 }}" class="form-control select2" style="width:100%" required>
                                                <option value="">Select Unit</option>
                                                @foreach ($unit as $u)
                                                <option value="{{ $u->id }}" @if($imp->unit_count == $u->id) selected @endif>{{ $u->unit_th.' / '.$u->unit_en }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td data-label="Price">
                                            {{-- <input type="text" class="form-control price" step="0.01" min="0" name="price[]" placeholder="Enter price(THB)" value=""> --}}
                                            <div class="input-group">
                                            <input class="form-control price" type="text" name="price[]" step="0.0001" min="0" placeholder="Price" value="{{ number_format($imp->price,2) }}" required>
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                        <td data-label="VAT">
                                        <input type="number" class="form-control" name="vat[]" step="0.01" min="0" value="{{ $imp->vat }}" required>
                                        </td>
                                        <td data-label="Total">
                                            {{-- <input type="text" class="form-control" name="total[]" placeholder="Total price(THB)" value="" readonly> --}}
                                            <div class="input-group">
                                            <input class="form-control total" type="text" name="total[]" placeholder="Total Price" value="{{ number_format($imp->total_price,2) }}" readonly>
                                                <div class="input-group-append"><span class="input-group-text">THB </span></div>
                                            </div>
                                        </td>
                                          <td data-label="WHT">
                                        <input type="number" class="form-control" name="wht[]" step="0.01" min="0"  value="{{ $imp->wht }}" required>
                                        </td>
                                          <td data-label="Crate">
                                        <input type="number" class="form-control" name="crate[]" placeholder="Enter number crate" value="{{ $imp->crate }}" required>
                                        </td>
                                    <td data-label="Action">@if($key > 0) <a href="javascript:" class="btn btn-danger delete-row" data-id="{{ $imp->id }}" data-timing="remove" title="Delete"><i class="far fa-trash-alt"></i></a> @endif</td>
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

        