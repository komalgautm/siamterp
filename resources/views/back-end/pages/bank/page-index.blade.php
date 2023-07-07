<div>
    <div class="fade-in"> 
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">                
                    <div class="card-header"> 
                        
                        <a href="{{ url("/$folder") }}" class="card-header-action">Bank Management</a>
                        <div class="card-header-actions">
                            <!-- <button class="btn btn-default btn-sm" id="sort" data-text="Sort">Sort</button> -->
                            <a class="btn btn-sm btn-primary" href="{{url("/$folder/create")}}"> Create</a>
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
                                <div class="col-lg-4 col-xs-12">
                                    <label for="search">Keyword :</label>
                                    <div class="input-group">                                        
                                        <input type="text" name="keyword" class="form-control" id="search" value="{{Request::get('keyword')}}" placeholder="Name/Code">
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
                                       
                                        <th width="20%">Bank Code</th>
                                        <th width="15%">Name</th>
                                        
                                        <th width="10%">Branch</th>
                                        <th width="10%">Account</th>
                                        <th width="10%">Account Type</th>
                                        <th width="15%">Status</th>
                                        <th width="25%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rows)
                                    @forelse($rows as $key => $row)
                                        <tr role="row" class="odd" data-row="{{$key+1}}" data-id="{{$row->id}}">
                                           
                                            <td data-label="Bank Code">
                                               <span>{{$row->bank_code}}</span>
                                            </td>
                                             <td data-label="Bank Code">
                                               <span>{{$row->name}}</span>
                                            </td>
                                             <td data-label="Branch">
                                               <span>{{$row->branch}}</span>
                                            </td>

                                            <td data-label="Account">
                                               <span>{{$row->account}}</span>
                                            </td>
                                             <td data-label="Account Type">
                                               <span>{{$row->account_type}}</span>
                                            </td>

                                           
                                            <td data-label="status">
                                               
                                                   <label class="c-switch c-switch-label c-switch-pill c-switch-success">
                                                    <input class="c-switch-input status" type="checkbox" data-id="{{$row->id}}" @if($row->status=='on') checked @endif><span class="c-switch-slider" data-checked="On" data-unchecked="Off"></span>
                                                </label>
                                            </td>
                                           

                                            <td data-label="Action">
                                       
                                                
                                                <a href="{{url("bank/$row->id")}}" class="btn btn-secondary" title="Edit"><i class="far fa-edit"></i></a>
                                               
                                               
                                                <a href="{{url("bank/delete/$row->id")}}" onclick="return confirm('Are you sure?')" class="btn btn-danger return" title="Delete" data-id="{{$row->id}}"><i class="fas fa-trash"></i></a>
                                             
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
    
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script type="text/javascript">
var fullUrl = window.location.origin;
        $(document).ready(function()
        {
            $('.status').on('click',function(){
    const $this = $(this), id = $(this).data('id');
    $.ajax({type:'get',url:'{{url("bank/status")}}/'+id,success:function(res){if(res==false){$(this).prop('checked',false)}}});
})
        })
    </script>