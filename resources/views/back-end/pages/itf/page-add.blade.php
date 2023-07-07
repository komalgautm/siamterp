<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="createForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Products Setup Management</a></span>
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
                                        <h6>ITF Code</h6>
                                    <input type="text" id="code" name="code" class="form-control" placeholder="" value="" required/>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Setup Date</h6>
                                    <input type="text" id="created" name="created" class="form-control" placeholder="" value="{{ date('d-M-Y') }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Create By</h6>
                                    <input type="hidden" id="user" name="user" value="{{ Auth::user()->id }}">
                                    <input type="text" id="user_name" name="user_name" class="form-control" value="{{ Auth::user()->name }}" readonly>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Product Name</h6>
                                        <input type="text" class="form-control" id="product_name" name="product_name" @if(Auth::user()->role != 'superadmin' && Auth::user()->role != 'admin') readonly @endif>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="form-group col-lg-3">
                                        <b>Product Weight :</b> <span id="total_weight"></span> <br>
                                        <b>Net Weight :</b> <span id="net_weight"></span> <br>
                                        <b>CBM :</b> <span id="cbm"></span> <br>
                                        <b>MLW :</b> <span id="minload"></span> <br>
                                        <b>Box/Pallet :</b> <span id="box_pallet"></span> <br>
                                        <input type="hidden" class="all_weight" name="all_weight">
                                        <input type="hidden" class="net_weight" name="net_weight">
                                        <input type="hidden" class="maxcbm" name="maxcbm">
                                        <input type="hidden" class="maxminload" name="maxminload">
                                        <input type="hidden" class="maxbox_pallet" name="maxbox_pallet">
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Weight Adjustment</h6>
                                        <input type="text" class="form-control adjust_weight" name="adjust_weight" value="0">
                                        <input type="hidden" id="new_weight" name="new_weight" value="0">
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

                        <style type="text/css"> 
                    .ean_ppITF{display:none;}</style>
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
                                    <tr id="row1" data-id="1">
                                        <td data-label="Type">
                                            <select name="type[]" id="type1" class="form-control type">
                                                <option value="">Select Type</option>
                                                <option value="ean">EAN</option>
                                                <option value="boxes">Boxes</option>
                                                <option value="packaging">Packaging</option>
                                            </select>
                                        </td>
                                        <td data-label="Name">
                                            <select name="item[]" id="item1" class="form-control select_item select2" style="width:100%">
                                                <option value="">Select Item</option>
                                                {{-- @foreach ($item as $itm)
                                                <option value="{{ $itm->id }}">{{ $itm->name_th.' / '.$itm->name_en }}</option>
                                                @endforeach --}}
                                            </select>
                                            <input type="hidden" class="total_qty" name="total_qty[]" value="">
                                        </td>
                                        <td data-label="Quantity">
                                            <input type="number" class="form-control qty" step="0.01" min="0" name="quantity[]" placeholder="Enter quantity" value="">
                                            <input type="hidden" class="weight" name="weight[]" value="">
                                            <input type="hidden" class="wrap_weight" name="wrap_weight[]" value="">
                                            <input type="hidden" class="cbm" name="cbm[]" value="">
                                            <input type="hidden" class="minload" name="minload[]" value="">
                                            <input type="hidden" class="box_pallet" name="box_pallet[]" value="">
                                            <input type="hidden" class="eanQty" name="eanQty[]" value="">
                                        </td>
                                        <td data-label="Unit">
                                            <select name="unitcount[]" id="unitcount1" class="form-control unitcount select2" style="width:100%">
                                                <option value="">Select Unit</option>
                                                {{-- @foreach ($unit as $u)
                                                <option value="{{ $u->id }}">{{ $u->unit_th.' / '.$u->unit_en }}</option>
                                                @endforeach --}}
                                            </select>
                                        </td>
                                        <td data-label="Total Weights">
                                            <input type="text" class="form-control total_weight" name="total_weight[]" value="" placeholder="" readonly>
                                        </td>
                                         <td data-label="Ean PP  itf">
                                            <input type="text" class="form-control ean_ppITF" name="ean_ppITF[]" value="" placeholder="" readonly>
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

        