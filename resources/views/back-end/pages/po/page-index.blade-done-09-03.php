﻿<div>
    <div class="fade-in">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header">

                        <a href="{{ url("/$folder") }}" class="card-header-action">Purchase Order Management</a>
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
                                        <input type="text" name="keyword" class="form-control" id="search" value="{{Request::get('keyword')}}" placeholder="PO Number">
                                        <span class="input-group-append">
                                            <button class="btn btn-secondary" type="submit">Search</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-3" style="text-align: right">
                                    <a class="btn btn-warning" href="{{url("/$folder/lrp")}}" target="_blank">LPR <!-- LPR --> Report</a>
                                    <button class="btn btn-danger" type="button" data-toggle="modal" data-target="#filtermodal" title="Filter Search">PO Status Report</button>
                                </div>
                            </div>
                        </form>
                        <br class="d-block d-sm-none"/>
                        <div class="table-responsive">
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important">
                                <thead>
                                    <tr role="">
                                        {{-- <th width="5%" style="text-align:center;">#</th> --}}
                                        <th width="20%">PO Number</th>
                                        <th width="15%">Vendor</th>
                                        {{-- <th width="15%">Create by</th> --}}
                                        <th width="15%">Created</th>
                                        <th>Total</th>
                                        <th width="15%">Status</th>
                                        <th width="20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rows)
                                    @forelse($rows as $key => $row)
                                        <tr role="row" class="odd" data-row="{{$key+1}}" data-id="{{$row->id}}">
                                            {{-- <td data-label="No.">
                                                <span class="no">{{$key+1}}</span>
                                                <i class="fas fa-bars handle" style="display:none;"></i>
                                            </td> --}}
                                            <td data-label="PO">
                                               <span>{{$row->code}}</span>
                                            </td>
                                            <td data-label="Vendor">
                                                @php($vendor = \App\VendorModel::where('id',$row->vendor)->first())
                                               <span>{{ $vendor->name }}</span>
                                            </td>
                                            {{-- <td data-label="Create By">
                                                @php($user = \App\UserModel::where('id',$row->user)->first())
                                                {{ $user->name }}
                                            </td> --}}
                                            <td data-label="created">
                                               <span>{{date('d-M-Y',strtotime($row->created))}}</span>
                                            </td>
                                            <td data-label="total">
                                                <span>{{number_format($row->total,2)}} THB</span>
                                            </td>
                                            <?php
                                                $color = "";
                                                $status = "";
                                                $paid = "";
                                                switch($row->status){
                                                    case "pending":
                                                        $color = "";
                                                        $status = "Pending";
                                                        break;
                                                    case "pickup":
                                                        $color =  "blue";
                                                        $status = "Pick up";
                                                        break;
                                                    case "delivery":
                                                        $color =  "orange";
                                                        $status = "Delivery";
                                                        break;
                                                    case "receive":
                                                        $color = "green";
                                                        $status = "Receive";
                                                        break;
                                                    case "cancel":
                                                        $color = "red";
                                                        $status = "Cancel";
                                                        break;
                                                    case "return";
                                                        $color = "red";
                                                        $status = "Return";
                                                };
                                                switch ($row->paid) {
                                                    case 'notpaid';
                                                        $paid = "Not Paid";
                                                        break;
                                                    case 'paid';
                                                        $paid = "Paid";
                                                        break;
                                                    case 'cancel';
                                                        $paid = "Cancel";
                                                        break;
                                                }
                                            ?>
                                            <td data-label="status">
                                                <span style="color:{{ $color }}">{{ $status." / ".$paid }}</span>
                                            </td>
                                            <td data-label="Action">
                                                @if($row->paid == 'notpaid' && $row->status != 'return' && $row->status != 'cancel')
                                                <button class="btn btn-info pay" type="button" data-toggle="modal" data-id="{{$row->id}}" data-target="#largeModal" title="Payment"><i class="far fa-money-bill-alt"></i></button>
                                                @endif
                                                <a href="{{url("po/view/$row->id")}}" class="btn btn-secondary" title="View"><i class="fas fa-eye"></i></a>
                                                @if($row->paid == 'notpaid' && $row->status != 'receive' && $row->status != 'return' && $row->status != 'cancel' || Auth::user()->role != 'staff' && $row->status != 'receive' && $row->status != 'return' && $row->status != 'cancel')
                                                <a href="{{url("po/$row->id")}}" class="btn btn-secondary" title="Edit"><i class="far fa-edit"></i></a>
                                                @endif
                                                @if($row->paid!= 'paid')
                                                <a href="{{url("$folder/po/$row->id")}}" class="btn btn-secondary print" data-id="{{$row->id}}" target="_blank" title="Print"><i class="far fa-file-pdf"></i></a>
                                                @endif
                                                @if(Auth::user()->role != 'staff' && $row->status != 'cancel' && $row->paid != 'paid' && $row->status != 'receive' && $row->status != 'return')
                                                <a href="javascript:" class="btn btn-danger cancel-Item" data-id="{{$row->id}}" title="Cancel"><i class="fas fa-times-circle"></i></a>
                                                @endif
                                                @if(Auth::user()->role != 'staff' && $row->status == 'receive')
                                                <a href="javascript:" class="btn btn-danger return" title="return" data-id="{{$row->id}}"><i class="fas fa-undo"></i></a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                    <tr class="text-center">
                                        <td colspan="6"> No Result Data.</td>
                                    </tr>
                                    @endforelse
                                    @endif
                                </tbody>
                            </table>
                            @if(Request::get('view')!='all') {{$rows->appends(request()->query())->links()}} @endif
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
            <form action="{{url('/po/payment')}}" id="FormPayment" name="FormPayment" method="post" enctype="multipart/form-data">
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
                            <option value="{{$use->name}}">{{$use->name}}</option>
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
{{-- Filter Search --}}
<div class="modal fade" id="filtermodal" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">PO Status Report</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="">
            <input type="hidden" name="report" value="1">
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-4">
                        <h6>From</h6>
                       <input type="date" name="date_from" id="date_from" class="form-control">
                    </div>
                    <div class="form-group col-lg-4">
                        <h6>Vendor</h6>
                        <select name="vendor" id="vendor" class="select2 form-control" style="width:100%">
                            <option value="">ALL</option>
                            @foreach ($livendor as $item)
                                <option value="{{$item['id']}}">{{$item['name']}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group col-lg-4">

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-4">
                        <h6>To</h6>
                       <input type="date" name="date_to" id="date_to" class="form-control">
                    </div>
                    <div class="form-group col-lg-4">
                        <h6>Paid</h6>
                      <select name="paid" id="paid" class="form-control">
                          <option value="">ALL</option>
                          <option value="paid">Paid</option>
                          <option value="notpaid">Not Paid</option>
                          <option value="cancel">Cancel</option>
                      </select>
                    </div>
                    <div class="form-group col-lg-4">
                        <h6>Delivered</h6>
                      <select name="delivered" id="delivered" class="form-control">
                          <option value="">ALL</option>
                          <option value="pending">Pending</option>
                          <option value="pickup">Pickup</option>
                          <option value="delivery">Delivery</option>
                          <option value="receive">Receive</option>
                          <option value="cancel">Cancel</option>
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
