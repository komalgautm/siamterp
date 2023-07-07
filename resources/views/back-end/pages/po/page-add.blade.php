<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="createForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Purchase Order Management</a></span>
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
                                    <div class="form-group col-lg-3">
                                        <h6>Create Date</h6>
                                    <input type="date" id="created" name="created" class="form-control" placeholder="" value="{{ date('Y-m-d') }}" />
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Create By</h6>
                                    <input type="hidden" id="user" name="user" value="{{ Auth::user()->id }}">
                                    <input type="text" id="user_name" name="user_name" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Vendor</h6>
                                        <select name="vendor" id="vendor" class="form-control select2" style="width:100%" required>
                                            <option value="">Please select Vendor</option>
                                            @foreach ($vendor as $v)
                                            <option value="{{ $v->id }}">{{ $v->name }}</option>
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
                                        <th width="17%">Price</th>
                                        <th width="8%">VAT</th>
                                        
                                        <th width="12%">Total</th>
                                        <th width="8%">WHT</th>
                                        <th width="8%">Crate</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="row1" data-id="1">
                                        <td data-label="Type">
                                            <select name="type[]" id="type1" class="form-control type" required>
                                                <option value="">Select Type</option>
                                                <option value="item">Produce </option>
                                                <option value="boxes">Boxes</option>
                                                <option value="packaging">Packaging</option>
                                            </select>
                                        </td>
                                        <td data-label="Name">
                                            <input type="hidden" class="form-control" name="barcode[]" value="01" readonly>
                                            <select name="item[]" id="item1" class="form-control select_item select2" style="width:100%" required>
                                                <option value="">Select Produce</option>
                                                {{-- @foreach ($item as $itm)
                                                <option value="{{ $itm->id }}">{{ $itm->name_th.' / '.$itm->name_en }}</option>
                                                @endforeach --}}
                                            </select>
                                        </td>
                                        <td data-label="Quantity">
                                            <input type="number" step="0.01" min="0" class="form-control qty" name="quantity[]" placeholder="Enter quantity" value="" required>
                                        </td>
                                        <td data-label="Unit">
                                            <select name="unitcount[]" id="unitcount1" class="form-control select2" style="width:100%" required>
                                                <option value="">Select Unit</option>
                                                @foreach ($unit as $u)
                                                <option value="{{ $u->id }}">{{ $u->unit_th.' / '.$u->unit_en }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td data-label="Price">
                                            {{-- <input type="text" class="form-control price" step="0.01" min="0" name="price[]" placeholder="Enter price(THB)" value=""> --}}
                                            <div class="input-group">
                                                <input class="form-control price" type="number" step="0.0001" min="0" name="price[]" placeholder="Price" required>
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                        <td data-label="VAT">
                                            <input type="number" class="form-control" name="vat[]" step="0.01" min="0"  value="" required>
                                        </td>
                                        <td data-label="Total">
                                         
                                            <div class="input-group">
                                                <input class="form-control total" type="number" step="0.01" min="0"  name="total[]" placeholder="Total Price" readonly required>
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                         <td data-label="WHT">
                                            <input type="number" class="form-control" step="0.01" min="0"  name="wht[]"  value="" required>
                                        </td>
                                         <td data-label="Crate">
                                            <input type="number" class="form-control" name="crate[]" placeholder="Enter number crate" value="" required>
                                        </td>
                                        <td data-label="Action">&nbsp;</td>
                                    </tr>
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
                        {{-- <div class="row">
                            <div class="form-group col-lg-4">
                                <h6>Email</h6>
                                <input type="text" class="form-control" id="email" name="email" readonly>
                            </div>
                            <div class="form-group col-lg-4">
                                <h6>Phone Number</h6>
                                <input type="text" class="form-control" id="phone" name="phone" readonly>
                            </div>
                            <div class="form-group col-lg-4">
                                <h6>Line ID</h6>
                                <input type="text" class="form-control" id="line_id" name="line_id" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-12">
                                <h6>Address</h6>
                                <textarea class="form-control" id="address" name="address" readonly></textarea>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <h6>Province</h6>
                                <input type="text" class="form-control" id="provinces" name="provinces" readonly>
                            </div>
                            <div class="form-group col-lg-6">
                                <h6>District</h6>
                                <input type="text" class="form-control" id="district" name="district" readonly>
                            </div>
                            <div class="form-group col-lg-6">
                                <h6>Sub-District</h6>
                                <input type="text" class="form-control" id="subdistrict" name="subdistrict" readonly>
                            </div>
                            <div class="form-group col-lg-6">
                                <h6>PostCode</h6>
                                <input type="text" class="form-control" id="postcode" name="postcode" readonly>
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <h6>Bank Name</h6>
                                <input type="text" class="form-control" id="bank_name" name="bank_name" readonly>
                            </div>
                            <div class="form-group col-lg-4">
                                <h6>Bank Account</h6>
                                <input type="text" class="form-control" id="bank_account" name="bank_account" readonly>
                            </div>
                            <div class="form-group col-lg-4">
                                <h6>Bank Number</h6>
                                <input type="text" class="form-control" id="bank_number" name="bank_number" readonly>
                            </div>
                        </div> --}}

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

        