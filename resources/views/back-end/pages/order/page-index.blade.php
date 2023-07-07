<div>
    <div class="fade-in"> 
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">                
                    <div class="card-header"> 
                        
                        <a href="{{ url("/$folder") }}" class="card-header-action">Order Management</a>
                        <div class="card-header-actions">
                            {{-- <button class="btn btn-default btn-sm" id="sort" data-text="Sort">Sort</button> --}}
                            <a class="btn btn-sm btn-primary" href="{{url("/$folder/create")}}"> Create</a>
                            {{-- <button class="btn btn-sm btn-danger" type="reset" id="delSelect" disabled> Delete</button> --}}
                        </div>                            
                    </div>
                    <div class="card-body">
                        @csrf
                        <form action="" method="get">                            
                            <!--<div class="row">
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
                            </div>-->
                        </form>
                        <br class="d-block d-sm-none"/>
                        <div class="table-responsive">
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important">
                                <thead>
                                    <tr role="">
                                        {{-- <th width="5%" style="text-align:center;">#</th> --}}
                                        <th width="5%">Code</th>
                                        <th width="5%">Quotation</th>
                                        <th width="10%">Name</th>
                                        <th width="10%">TT Ref</th>
                                        {{-- <th width="15%">Create by</th> --}}
                                        <th width="5%">Date</th>
                                        <th width="5%">Ship Date</th>
                                        <th width="5%">Status</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(@$rows)
                                    @foreach($rows as $key => $row)
                                        <tr role="row" class="odd" data-row="{{$key+1}}" data-id="{{$row->id}}">
                                            @php($ship = \App\ShipToModel::where('id',$row->shipto_id)->first())
                                            @php($client = \App\ClientModel::where('id',$row->client_id)->first())
                                            @php($airport = \App\AirportModel::where('id',$row->airport_id)->first())
                                            @php($quotation = \App\QuotationModel::where('id',$row->quotation_id)->first())
                                            {{-- <td data-label="No.">
                                                <span class="no">{{$key+1}}</span>
                                                <i class="fas fa-bars handle" style="display:none;"></i>
                                            </td> --}}
                                            <td data-label="Code">
                                               <span>{{$row->code}}</span>
                                            </td>
                                            <td data-label="Quotation">
                                                <span>{{@$quotation->code}}</span>
                                            </td>
                                            <td data-label="Name">
                                                @if($row->shipto_id == '' && $row->client_id == '')
                                                <span>{{ $airport->country.' - '.$airport->city.' ['.$airport->airport_code.']' }}</span>
                                                @elseif($row->shipto_id == '')
                                                <span>{{ $client->name }}</span>
                                                @else
                                                <span>{{ $ship->name }}</span>
                                                @endif
                                             </td> 
                                             <td data-label="TT Ref">

                                                <?php 
                                                $ttref=$row->tt_ref;
                                               
                                            
                                            ?>


                                               {{$ttref}}
                                             </td>
                                            {{-- <td data-label="Create By">
                                                @php($user = \App\UserModel::where('id',$row->user)->first())
                                                {{ $user->name }}
                                            </td> --}}
                                            <td data-label="Date">
                                               <span>{{date('d-M-Y',strtotime($row->created))}}</span> 
                                            </td>
                                            <td data-label="Ship Date">
                                                <span>{{date('d-M-Y',strtotime($row->ship_date))}}</span> 
                                             </td>
                                            <td data-label="status">
                                                <?php if($row->status == 'pending'){ $color = 'blue';}elseif($row->status == 'confirm'){ $color = 'green'; } else { $color = 'red'; } ?>
                                                <span style="color:{{ $color }}">{{ $row->status }}</span>
                                            </td>
                                            <td data-label="Action">
                                                <a href="{{url("$folder/view/$row->id")}}" class="btn btn-secondary" title="View"><i class="fas fa-eye"></i></a>
                                                <a href="{{url("$folder/oparetion/$row->id")}}" target="_blank" class="btn btn-secondary oparetion" title="oparetion"><i class="far fa-file-pdf"></i></a>
                                                <a href="{{url("$folder/customs/$row->id")}}" target="_blank" class="btn btn-secondary customs" title="customs"><i class="far fa-file-pdf"></i></a>
                                                @if($row->status == 'pending')
                                                <a href="{{url("$folder/$row->id")}}" class="btn btn-secondary" title="Edit"><i class="far fa-edit"></i></a>




												<button class="btn btn-info freight" type="button" data-toggle="modal" data-code="{{$ttref}}" data-to_code="" data-id="{{$row->id}}" data-target="#largeModal" title="Freight Detail"><i class="fas fa-plane"></i></button>
                                              <!--   <a href="javascript:" class="btn btn-success confirm" data-id="{{$row->id}}" title="Confirm"><i class="fas fa-check-circle"></i></a> -->
                                                {{-- <a href="javascript:" class="btn btn-danger cancel"  data-id="{{$row->id}}" title="Cancel"><i class="fas fa-times-circle"></i></a> --}}
												<!-- Comment open by Malti --->
												<a href="{{url('order/cancel_order/?id='.$row->id)}}" class="btn btn-danger cancel"  title="Cancel" onclick="return confirm('Are you sure?')"><i class="fas fa-times-circle"></i></a> 
												
                                                <a href="{{url("operation/$row->id")}}" target="_blank" class="btn btn-secondary " title="Operation"><i class="fas fa-tape"></i></a>
                                                @endif

                                                
                                            </td>
                                        </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            
                        </div>
                    </div>
                    <div class="card-footer">
                        
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
                <h4 class="modal-title">Freight Detail</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="{{ $folder.'/update_freight_detail' }}" id="FormFreightDetail" name="FormFreightDetail" method="post" enctype="multipart/form-data">
            <input type="hidden" id="quotation_id" name="id" value="">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-3">
                        <h6>AWB No.</h6>
                        <input type="text" class="form-control" id="awb_no" name="awb_no" value="" required>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Airline</h6>
                        <select class="form-control airline select2" name="airline" id="airline" style="width:100%" required>
                        </select>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Freight Details</h6>
                        <input type="text" class="form-control" name="freight_detail" id="freight_detail" value="" required>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Ship Date</h6>
                        <input type="date" class="form-control" id="ship_date" name="ship_date" value="" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <h6>ETD</h6>
                        <input type="text" class="form-control" id="etd" name="etd" value="" required>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>ETA</h6>
                        <input type="text" class="form-control" id="eta" name="eta" value="" required>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Load Date</h6>
                        <input type="date" class="form-control" id="load_date" name="load_date" value="" required>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Load Time</h6>
                        <input type="time" class="form-control" id="load_time" name="load_time" value="" required>
                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-3">
                        <h6>PO Number</h6>
                        <input type="text" class="form-control" id="po_number" name="po_number" value="" required>
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>Total Packages</h6>
                        <input type="text" class="form-control" id="total_package" name="total_package" value="">
                    </div>
                    <div class="form-group col-lg-3">
                        <h6>TT Ref</h6>
                        <input type="text" class="form-control" id="tt_ref" name="tt_ref" value="" required >
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