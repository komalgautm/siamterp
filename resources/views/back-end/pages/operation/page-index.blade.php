<div>
    <div class="fade-in"> 
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">                
                    <div class="card-header"> 
                        
                        <a href="{{ url("/$folder") }}" class="card-header-action">Operation Management</a>
                        <div class="card-header-actions">
                            {{-- <button class="btn btn-default btn-sm" id="sort" data-text="Sort">Sort</button> --}}
                            {{-- <a class="btn btn-sm btn-primary" href="{{url("/$folder/create")}}"> Create</a> --}}
                            {{-- <button class="btn btn-sm btn-danger" type="reset" id="delSelect" disabled> Delete</button> --}}
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
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important" width="100%">
                                <thead>
                                    <tr role="">
                                        {{-- <th width="5%" style="text-align:center;">#</th> --}}
                                        <th width="5%">Code</th>
                                        <th width="5%">TTREF</th>
                                        <th width="10%">Name</th>
                                        {{-- <th width="15%">Create by</th> --}}
                                        <th width="5%">Load Date</th>
                                        <th width="5%">Load Time</th>
                                        <th width="5%">Status</th>
                                        <th width="10%">Actions</th>
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
                                                <span>{{@$row->tt_ref}}</span>
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
                                            {{-- <td data-label="Create By">
                                                @php($user = \App\UserModel::where('id',$row->user)->first())
                                                {{ $user->name }}
                                            </td> --}}
                                            <td data-label="Date">
                                               <span>@if($row->load_date){{date('d-M-Y',strtotime($row->load_date))}}@endif</span> 
                                            </td>
                                            <td data-label="Ship Date">
                                                <span>@if($row->load_time){{date('H:i',strtotime($row->load_time))}}@endif</span> 
                                             </td>
                                            <td data-label="status">
                                                <?php if($row->packedStatus == '0'){ $color = 'blue';}elseif($row->packedStatus == '1'){ $color = 'green'; } ?>
                                                <span style="color:{{ $color }}">@if($row->packedStatus=='1') Completed @else Pending @endif</span>
                                            </td>
                                            <td data-label="Action">
                                                <a href="{{url("$folder/oparetion/$row->id")}}" target="_blank" class="btn btn-secondary oparetion" title="oparetion"><i class="far fa-file-pdf"></i></a>
                                                @if($row->status == 'pending')
                                                <a href="{{url("$folder/$row->id")}}" class="btn btn-secondary" title="Edit"><i class="far fa-edit"></i></a>
                                                @endif
                                                @if($row->status == 'confirm')
                                                <a href="javascript:" class="btn btn-success truck_load" data-id="{{$row->id}}" title="truck_load"><i class="fas fa-truck-loading"></i></a>
												{{-- <a href="javascript:" class="btn btn-danger cancel" data-id="{{$row->id}}" title="Cancel"><i class="fas fa-times-circle"></i></a> --}}
												
                                                @endif
												<!-- Comment open by Malti --->
											<a href="{{url('order/cancel_order/?url=2&id='.$row->id)}}" class="btn btn-danger cancel" data-id="{{$row->id}}" title="Cancel" onclick="return confirm('Are you sure?')"><i class="fas fa-times-circle"></i></a>
                                                <a href="javascript:" class="btn btn-success confirm" data-id="{{$row->id}}" title="Confirm"><i class="fas fa-check-circle"></i></a>
                                             
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