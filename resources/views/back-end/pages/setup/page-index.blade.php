<div>
    <div class="fade-in"> 
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">                
                    <div class="card-header"> 
                        
                        <a href="{{ url("/$folder") }}" class="card-header-action">EAN Setup Management</a>
                        <div class="card-header-actions">
                            {{-- <button class="btn btn-default btn-sm" id="sort" data-text="Sort">Sort</button> --}}
                            <a class="btn btn-sm btn-primary" href="{{url("/$folder/create")}}"> Create</a>
                            {{-- <button class="btn btn-sm btn-danger" type="reset" id="delSelect" disabled> Delete</button>                                                      --}}
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
                                        <input type="text" name="keyword" class="form-control" id="search" value="{{Request::get('keyword')}}" placeholder="EAN Code/Name">
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
                                        {{-- <th width="5%" style="text-align:center;">#</th> --}}
                                        <th width="10%">EAN Code</th>
                                        <th width="15%">Name</th>
                                        {{-- <th width="15%">Create by</th> --}}
                                        <th width="15%">Setup Date</th>
                                        <th width="15%">Total Weight</th>
                                        <th width="10%">Status</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rows)
                                    @foreach($rows as $key => $row)
                                        <tr role="row" class="odd" data-row="{{$key+1}}" data-id="{{$row->id}}">
                                            {{-- <td data-label="No.">
                                                <span class="no">{{$key+1}}</span>
                                                <i class="fas fa-bars handle" style="display:none;"></i>
                                            </td> --}}
                                            <td data-label="PO">
                                               <span>{{$row->code}}</span>
                                            </td>
                                            <td data-label="Name">
                                               <span>{{ $row->name }}</span>
                                            </td>
                                            {{-- <td data-label="Create By">
                                                @php($user = \App\UserModel::where('id',$row->user)->first())
                                                {{ $user->name }}
                                            </td> --}}
                                            <td data-label="Setup Date">
                                               <span>{{date('d-M-Y',strtotime($row->created))}}</span> 
                                            </td>
                                            <td data-label="Total Weight">
                                                <span>{{ $row->total_weight }}</span>
                                            </td>
                                            <td data-label="status">
                                                <label class="c-switch c-switch-label c-switch-pill c-switch-success">
                                                    <input class="c-switch-input status" type="checkbox" data-id="{{$row->id}}" @if($row->status=='on') checked @endif><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </td>
                                            <td data-label="Action">
                                                <a href="{{url("$folder/$row->id")}}" class="btn btn-secondary" title="Edit"><i class="far fa-edit"></i></a>
                                                {{-- <a href="javascript:" class="btn btn-danger deleteItem" data-id="{{$row->id}}" title="Delete"><i class="far fa-trash-alt"></i></a> --}}
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

<div class="modal fade" id="largeModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Payment</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="/po/payment" id="FormPayment" name="FormPayment" method="post" enctype="multipart/form-data">
            <input type="hidden" id="po_id" name="id" value="">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-4">
                        <h6>Status</h6>
                        <select name="paid" id="paid" class="form-control" style="width:100%">
                            <option value="notpaid">Not Paid</option>
                            <option value="paid">Paid</option>
                            {{-- <option value="receive" @if($row->paid=='receive') selected @endif>Receive</option> --}}
                            {{-- <option value="cancel" @if($row->paid=='cancel') selected @endif>Cancel</option> --}}
                        </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <h6>Staff press payment</h6>
                        <input type="hidden" id="user" name="user" value="{{ Auth::user()->id }}">
                        <input type="text" class="form-control" id="staff_press" name="staff_press" value="{{ Auth::user()->name }}" readonly>
                    </div>
                    <div class="form-group col-lg-4">
                        <h6>Payment Date</h6>
                    <input type="date" class="form-control" id="paid_date" name="paid_date" value="{{ date('Y-m-d') }}" readonly>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-4">
                        <h6>Paid By</h6>
                        <select class="form-control paid_by" name="paid_by" id="paid_by" disabled>
                            <option value="">Please Select</option>
                            <option value="scb">SCB</option>
                            <option value="kbank">KBank</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <h6>Staff Name</h6>
                        {{-- <input type="text" class="form-control" id="staff_name" name="staff_name" value="" placeholder="Plase enter name" readonly> --}}
                        @php($user = \App\UserModel::where('role','staff')->get())
                        <select class="form-control select2" style="width:100%" name="staff_name" id="staff_name" disabled>
                            <option value="">Please Select</option>
                            @foreach ($user as $use)
                            <option value="{{$use->id}}">{{$use->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                <button class="btn btn-primary" type="submit">Save changes</button>
            </div>
            </form>
        </div>
    </div>
</div>