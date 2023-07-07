<div>
    <div class="fade-in"> 
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">                
                    <div class="card-header"> 
                        
                        <a href="{{url("/$folder")}}" class="card-header-action">Sorting Management</a>
                        <div class="card-header-actions">
                            {{-- <button class="btn btn-default btn-sm" id="sort" data-text="Sort">Sort</button> --}}
                            {{-- <a class="btn btn-sm btn-primary" href="{{url("/$folder/create")}}"> Create</a> --}}
                            {{-- <button class="btn btn-sm btn-danger" type="reset" id="delSelect" disabled> Delete</button>                                                    --}}
                        </div>                            
                    </div>
                    <div class="card-body">
                        @csrf
                        <form action="" method="get">                            
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="form-group">    
                                        <label for="view">View : </label> 
                                        @php($numrows=10)
                                        <select name="view" id="view" class="form-control">
                                            <option value="10" @if(Request::get('view')==10) selected @endif>10</option>
                                            @for($i=1; $i<5; $i++)
                                            <option value="{{$numrows = $numrows*2}}" @if(Request::get('view')==$numrows) selected @endif>{{$numrows}}</option>
                                            @endfor
                                            <option value="all" @if(Request::get('view')=='all') selected @endif>All</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-xs-12">
                                    <label for="search">Keyword :</label>
                                    <div class="input-group">                                        
                                        <input type="text" name="keyword" class="form-control" id="search" value="{{Request::get('keyword')}}" placeholder="Code">
                                        <span class="input-group-append">
                                            <button class="btn btn-secondary" type="submit">Search</button>
                                        </span>
                                    </div>
                                    
                                </div>
                            </div>
                        </form>
                        <br class="d-block d-sm-none"/>
                        <div class="table-responsive">
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important">
                                <thead>
                                    <tr role="">
                                        <th width="15%">Code</th>
                                        <th width="15%">Name</th>
                                        <th width="5%">Quantity</th>
                                        <th width="15%">Unit</th>
                                        <th width="5%">Crate</th>
                                        <th width="15%">Receiving Date</th>
                                        <th width="5%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rows)
                                    @foreach($rows as $key => $row)
                                        <tr role="row" class="odd" data-row="{{$key+1}}" data-id="{{$row->id}}">
                                            <td data-label="Code">
                                             {{ $row->code }}
                                            </td>
                                            <td data-label="Name">
                                                @if($row->type == 'item')
                                                {{$row->name_th_item}} @if($row->name_en_item != null) / {{ $row->name_en_item }} @endif
                                                @else
                                                {{ $row->name_th_item }}
                                                @endif
                                            </td>
                                            <td data-label="Quantity">
                                                @if($row->unit == 1)
                                                {{ $row->num_qty }}
                                                @else
                                                {{ $row->total_weight }}
                                                @endif
                                            </td>
                                            <td data-label="Unit">
                                                {{$row->name_th_unit}} @if($row->name_en_unit != null) / {{ $row->name_en_unit }} @endif
                                            </td>
                                            <td data-label="Crate">
                                                {{ $row->num_crate }}
                                            </td>
                                            <td data-label="Receiving Date">
                                                {{date('d-M-Y H:i:s',strtotime($row->receive_date))}}
                                            </td>
                                            <td data-label="Action">
                                                @if($row->type == 'item' && $row->unit == 1)
                                                <a href="javascript:" class="btn btn-secondary confirmPC" data-toggle="modal" data-target="#pcModal" data-id="{{$row->id}}" title="Confirm"><i class="fas fa-check"></i></a>  
                                                @endif
                                                @if($row->type == 'item' && $row->unit == 2 || $row->type == 'item' && $row->unit == 4)
                                                <a href="javascript:" class="btn btn-secondary confirmKG" data-toggle="modal" data-target="#kgModal" data-id="{{$row->id}}" title="Confirm"><i class="fas fa-check"></i></a>                                          
                                                @endif
                                                {{-- @if($row->type == 'boxes' || $row->type == 'packaging')
                                                <a href="javascript:" class="btn btn-secondary confirm" data-toggle="modal" data-target="#Modal" data-id="{{$row->id}}" title="Confirm"><i class="fas fa-check"></i></a>
                                                @endif --}}
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            @if(Request::get('view')!='all') {{$rows->links()}} @endif
                        </div>
                    </div>
                    <div class="card-footer">
                        <strong>ทั้งหมด</strong> {{$rows->count()}} @if(Request::get('view')!='all'): <strong>จาก</strong> {{$rows->firstItem()}} - {{$rows->lastItem()}} @endif
                    </div>
                </div>                
            </div>
        </div>                
    </div>         
</div>

<div class="modal fade" id="kgModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sorting(KG)</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="{{url('/sorting/sortingKG')}}" id="FormKG" name="FormKG" method="post" enctype="multipart/form-data">
            <input type="hidden" id="imp_idKG" name="id" value="">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-4" id="infoKG">
                        {{-- <h6>Item</h6>
                        <input type="text" class="form-control" id="itemKG" name="itemKG" value="" readonly> --}}
                    </div>
                    {{-- <div class="form-group col-lg-3">
                        <h6>Unit</h6>
                        <input type="text" class="form-control" id="unitKG" name="unitKG" value="" readonly>
                    </div>
                    <div class="form-group col-lg-2">
                        <h6>Quantity</h6>
                        <input type="text" class="form-control" id="qtyKG" name="qtyKG" value="" readonly>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Number of Crates</h6>
                        <input type="text" class="form-control" id="crateKG" name="crateKG" value="" readonly>
                    </div> --}}
                   
                </div>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <h6>Good Weight</h6>
                        <div class="input-group">
                            <input class="form-control" type="text" id="good_weightKG" name="good_weightKG" placeholder="" required>
                            <div class="input-group-append"><span class="input-group-text">KG</span></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Blue Crates</h6>
                        <input type="text" class="form-control" id="crateKG" name="crateKG" value="" required>
                        <input type="hidden" class="form-control" id="qtyKG" name="qtyKG" value="">
                        <input type="hidden" class="form-control" id="netKG" name="netKG" value="">
                        <input type="hidden" class="form-control" id="totalKG" name="totalKG" value="">
                        <input type="hidden" class="form-control" id="wasteKG" name="wasteKG" value="">
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Wastage Weight</h6>
                        <div class="input-group">
                            <input class="form-control" type="text" id="wastage_weightKG" name="wastage_weightKG" placeholder="auto calculate" readonly>
                            <div class="input-group-append"><span class="input-group-text">KG</span></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Total Price</h6>
                        <div class="input-group">
                            <input class="form-control" type="text" id="total_priceKG" name="total_priceKG" placeholder="auto calculate" readonly>
                            <div class="input-group-append"><span class="input-group-text">THB</span></div>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <h6>Note</h6>
                        <textarea name="noteKG" id="noteKG" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Confirm By</h6>
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="text" class="form-control" id="userPC" name="userPC" value="{{ Auth::user()->name }}" placeholder="" readonly>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                <button class="btn btn-primary submitKG" type="button">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="pcModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Sorting(PC)</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="{{url('/sorting/sortingPC')}}" id="FormPC" name="FormPC" method="post" enctype="multipart/form-data">
            <input type="hidden" id="imp_idPC" name="id" value="">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-4" id="infoPC">
                        {{-- <h6>Item</h6>
                        <input type="text" class="form-control" id="itemPC" name="itemPC" value="" readonly> --}}
                    </div>
                    {{-- <div class="form-group col-lg-3">
                        <h6>Unit</h6>
                        <input type="text" class="form-control" id="unitPC" name="unitPC" value="" readonly>
                    </div>
                    <div class="form-group col-lg-2">
                        <h6>Quantity</h6>
                        <input type="text" class="form-control" id="qtyPC" name="qtyPC" value="" required>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Number of Crates</h6>
                        <input type="text" class="form-control" id="cratePC" name="cratePC" value="" readonly>
                    </div> --}}
                   
                </div>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <h6>Good Quantity</h6>
                        <div class="input-group">
                            <input class="form-control" type="text" id="good_weightPC" name="good_weightPC" placeholder="" required>
                            <div class="input-group-append"><span class="input-group-text">PC</span></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Blue Crates</h6>
                        <input type="text" class="form-control" id="cratePC" name="cratePC" value="" required>
                        <input type="hidden" class="form-control" id="qtyPC" name="qtyPC" value="">
                        <input type="hidden" class="form-control" id="netPC" name="netPC" value="">
                        <input type="hidden" class="form-control" id="totalPC" name="totalPC" value="">
                        <input type="hidden" class="form-control" id="wastePC" name="wastePC" value="">
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Wastage Quantity</h6>
                        <div class="input-group">
                            <input class="form-control" type="text" id="wastage_weightPC" name="wastage_weightPC" placeholder="auto calculate" readonly>
                            <div class="input-group-append"><span class="input-group-text">KG</span></div>
                        </div>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Total Price</h6>
                        <div class="input-group">
                            <input class="form-control" type="text" id="total_pricePC" name="total_pricePC" placeholder="auto calculate" readonly>
                            <div class="input-group-append"><span class="input-group-text">THB</span></div>
                        </div>
                    </div>
                    
                    
                </div>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <h6>Note</h6>
                        <textarea name="notePC" id="notePC" class="form-control" cols="30" rows="3"></textarea>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Confirm By</h6>
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="text" class="form-control" id="userPC" name="userPC" value="{{ Auth::user()->name }}" placeholder="" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                <button class="btn btn-primary submitPC" type="button">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirm</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="{{url('/receive/confirm')}}" id="Form" name="Form" method="post" enctype="multipart/form-data">
            <input type="hidden" id="imp_id" name="id" value="">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-4" id="info">

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <h6>Quantity Confirm</h6>
                        <input type="text" class="form-control" id="qty" name="qty" value="">
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Confirm By</h6>
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="text" class="form-control" id="user" name="user" value="{{ Auth::user()->name }}" placeholder="" readonly>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>