<div>

    <div class="fade-in"> 
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">                
                    <div class="card-header"> 
                        <a href="{{url("/$controller")}}" class="card-header-action">User Management</a>
                        <div class="card-header-actions">
                            <a class="btn btn-sm btn-primary" href="{{url("/$controller/create")}}"> Create</a>
                            <button class="btn btn-sm btn-danger" type="button" id="delSelect" disabled> Delete</button>
                            @csrf
                        </div>                            
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-striped no-footer" id="DataTables_Table_0" role="grid" aria-describedby="DataTables_Table_0_info" style="border-collapse: collapse !important">
                                <thead>
                                    <tr role="">
                                        <th width="5%" style="text-align:center;">#</th>
                                        <th>
                                            <div class="custom-control custom-checkbox">
                                                <input type="checkbox" name="select" class="custom-control-input selectAll" id="selectAll">
                                                <label class="custom-control-label" for="selectAll"></label>
                                            </div>
                                        </th>
                                        <th width="30%">Username</th>
                                        <th>Date registered</th>
                                        <th width="10%">Role</th>
                                        <th width="10%">Status</th>
                                        <th width="20%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rows)
										
                                    @foreach($rows as $key => $row)
                                        <tr role="row" class="odd">
                                            <td style="width:5%; text-align:center;">{{$key+1}}</td>
                                            <td>
                                                <div class="custom-control custom-checkbox">
                                                    <input type="checkbox" name="select" class="custom-control-input ChkBox" id="ChkBox{{$row->id}}" value="{{$row->id}}">
                                                    <label class="custom-control-label" for="ChkBox{{$row->id}}"></label>
                                                </div>
                                            </td>
                                            <td class="sorting_1">{{$row->name}}</td>
                                            <td>{{date('d-F-Y',strtotime($row->created_at))}}</td>
                                            <td>{{$row->role}}</td>
                                            <td>
                                                @switch($row->status)
                                                    @case('pending')
                                                        <span class="badge badge-warning">{{$row->status}}</span>
                                                        @break
                                                    @case('inactive')
                                                        <span class="badge badge-dark">{{$row->status}}</span>
                                                        @break
                                                    @case('active')
                                                        <span class="badge badge-success">{{$row->status}}</span>
                                                        @break
                                                    @default   
                                                        <span class="badge badge-danger">{{$row->status}}</span>                                                     
                                                @endswitch
                                            </td>
                                            <td>
                                                <a href="{{url("/$controller/$row->id")}}" class="btn btn-success" title="Edit"><i class="far fa-edit"></i></a>
                                                <a href="{{url("/$controller/reset/$row->id")}}" class="btn btn-info" title="Reset Password"><i class="fas fa-sync-alt"></i></a>
                                                <a href="javascript:" class="btn btn-danger deleteItem" data-id="{{$row->id}}" title="Delete"><i class="far fa-trash-alt"></i></a>
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
                        <strong>ทั้งหมด </strong>{{$rows->count()}} : <strong>จาก</strong> {{$rows->firstItem()}} - {{$rows->lastItem()}}
                    </div>
                </div>                
            </div>
        </div>                
    </div>         
</div>
    