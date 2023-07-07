<div>
    <div class="fade-in"> 
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">                
                    <div class="card-header"> 
                        
                        <a href="{{ url("/$folder") }}" class="card-header-action">Quotation Management</a>
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
                                        {{-- <th width="5%" style="text-align:center;">#</th> --}}
                                        <th width="5%">Code</th>
                                        <th width="10%">Name</th>
                                        {{-- <th width="15%">Create by</th> --}}
                                        <th width="5%">Date</th>
                                        <th width="5%">Ship Date</th>
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
                                            {{-- <td data-label="No.">
                                                <span class="no">{{$key+1}}</span>
                                                <i class="fas fa-bars handle" style="display:none;"></i>
                                            </td> --}}
                                            <td data-label="Code">
                                               <span>{{$row->code}}</span>
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
                                               <span>{{date('d-M-Y',strtotime($row->created))}}</span> 
                                            </td>
                                            <td data-label="Ship Date">
                                                <span>{{date('d-M-Y',strtotime($row->ship_date))}}</span> 
                                             </td>
                                            <td data-label="status">
                                                <?php if($row->status == 'pending'){ $color = 'blue';}elseif($row->status == 'approve'){ $color = 'green'; }else{ $color = 'red'; } ?>
                                                <span style="color:{{ $color }}">{{ $row->status }}</span>
                                            </td>
                                            <td data-label="Action">
                                                <a href="{{url("$folder/view/$row->id")}}" class="btn btn-secondary" title="View"><i class="fas fa-eye"></i></a>
                                                <a href="{{url("$folder/quotation/$row->id")}}" target="_blank" class="btn btn-secondary report_quotation" title="report_quotation"><i class="far fa-file-pdf"></i></a>
                                                @if($row->status == 'pending')
                                                <a href="{{url("$folder/$row->id")}}" class="btn btn-secondary" title="Edit"><i class="far fa-edit"></i></a>
                                                <a href="javascript:" class="btn btn-success approve" data-id="{{$row->id}}" title="Approve"><i class="fas fa-check-circle"></i></a>
                                                <a href="javascript:" class="btn btn-danger cancel" data-id="{{$row->id}}" title="Cancel"><i class="fas fa-times-circle"></i></a>
                                                @endif
                                                {{-- @if($row->status == 'approve' && $row->get_order == 'no')
                                                <a href="javascript:" class="btn btn-success getOrder" data-id="{{$row->id}}" title="GetOrder"><i class="fas fa-file-signature"></i></a>

                                                @endif --}}
                                                <!-- sourabh -->
                                                @if($row->status == 'approve')
                                                <a href="{{url("$folder/copy/$row->id")}}" class="btn btn-primary"><i class="fa fa-copy" title="Copy"></i></a>
                                                <a href="javascript:void(0)" data-id="{{$row->id}}" class="btn btn-success sendOrder"><i class="fab fa-first-order" title="Send order"></i></a>
                                                @endif
                                                <!-- sourabh -->
                                                <a href="{{url("quotation/$row->id/csv")}}" target="_blank" class="btn btn-success " title="Download CSV Data">CSV</a>
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

