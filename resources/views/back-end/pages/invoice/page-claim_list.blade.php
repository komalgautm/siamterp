<div>
    <div class="fade-in"> 
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">                
                    <div class="card-header"> 
                        
                        <a href="{{ url("/$folder") }}" class="card-header-action">Claim Management</a>
                        <div class="card-header-actions">
                            
                        </div>                            
                    </div>
                    <div class="card-body">
                        @csrf
                        <form action="" method="get">                            
                            <div class="row">
                                <div class="col-lg-2">
                                    <div class="form-group">    
                                        <label for="view">View : </label> 
                                        @php $numrows=10 @endphp
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
                                 
                                        
                                        <th width="5%">Claim Code.</th>
                                        <th width="5%">Invoice Code</th>
                                        
                                     <!--    <th width="5%">Claim Qty.</th> -->
                                       <th width="5%">Total</th>
                                     
                                        <th width="5%">Created At</th>
                                     
                                        <th width="5%">Status</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(@$rows)
                                    @foreach($rows as $key => $row)
                                        <tr role="row" class="odd" data-row="{{$key+1}}" data-id="{{$row->id}}">
                                            @php $invoice = DB::table('invoices')->where('id',$row->invoice_id)->first()->code @endphp
                                        
                                          
                                        <td data-label="Claim Code">
                                               {{$row->claim_code}}
                                            </td>
                                            <td data-label="Invoice Code">
                                                <a href="{{url('invoice/view/'.$row->invoice_id)}}" title="Invoice details">
                                               {{$invoice}}
                                               </a>
                                            </td>
                                            
                                       <!--      <td data-label="claim_qty">
                                               <span>{{$row->claim_qty}}</span>
                                            </td> -->
                                             <td data-label="Total Price">
                                               <span>{{$row->total_price}}{{ $row->currency }}</span>
                                            </td>
                                            <td data-label="Created">
                                             
                                                {{date('d-M-Y',strtotime($row->created_at))}}
                                               
                                            </td>
                                          
                                            <td data-label="status">
                                                @php if($row->status == '0'){ $color = 'blue';} else if($row->status=='1') { $color = 'green'; } else {$color = 'red';} @endphp

                                               
                                                <span style="color:{{ $color }}"> @if($row->status=='0') Pending @elseif($row->status=='1') Approved @else Rejected  @endif</span>
                                               

                                               
                                            </td>
                                            <td data-label="Action">
                                                <a href="{{url("$folder/claim_detail/$row->id")}}" class="btn btn-secondary" title="View"><i class="fas fa-eye"></i></a>

                                                <a href="{{url("$folder/claim_print/$row->id")}}" target="_blank" class="btn btn-secondary print" data-id="{{$row->id}}" title="Print"><i class="far fa-file-pdf"></i></a>
                                              

                                               @if($row->status=='0')
                                                <a href="{{url("$folder/claim_status?status=1&token=".$row->id)}}" class="btn btn-success" onclick="return confirm('Are You sure?')" title="Approve"><i class="fas fa-check"></i> </a>

                                                <a href="javascript:void(0)"  onclick="reject('{{url("$folder/claim_status?status=2&token=".$row->id)}}')" class="btn btn-danger" title="reject" ><i class="fas fa-ban"></i> </a>
                                                 @endif

                                            
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

<script type="text/javascript">
function reject(url)
{
    $("#rejectBtn").click()
    $("#rurl").val(url);
}

function rejectC()
{
    var remarks=$("#remarks").val();
    if(!remarks)
    {
        $("#remarks").css('border','1px solid red');
        return false;
    }
    var url=$("#rurl").val();
    url = url+"&remarks="+encodeURI(remarks);
    window.location.href=url
}
</script>

 <button type="button" id="rejectBtn" data-toggle="modal" data-target="#Edit" style="display: none;"></button>

<div class="modal fade" id="Edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Rejection-from</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="/invoice/editTotal" id="Form" name="Form" method="post" enctype="multipart/form-data">
            <input type="hidden" id="rurl"  value="">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <textarea class="form-control" id="remarks" placeholder="Enter remarks" rows="5"></textarea>
                    </div>
                   
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                <button class="btn btn-primary "  onclick="rejectC()" type="button">Confirm</button>
            </div>
            </form>
        </div>
    </div>
</div>