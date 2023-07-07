  
             
           
       
<div>
    <div class="fade-in"> 
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">                
                    <div class="card-header"> 
                        
                        <a href="{{ url("/$folder") }}" class="card-header-action">Invoice Management</a>
                        <div class="card-header-actions">
                            {{-- <button class="btn btn-default btn-sm" id="sort" data-text="Sort">Sort</button> --}}
                            {{-- <a class="btn btn-sm btn-primary" href="{{url("/$folder/create")}}"> Create</a> --}}
                            {{-- <button class="btn btn-sm btn-danger" type="reset" id="delSelect" disabled> Delete</button> --}}
                        </div> 
                        <a style="float:right;" href="{{ url("/$folder/myClaims") }}" class="btn btn-info">Claim List</a>

                    </div>
                    <div class="card-body">
                        @csrf
                        <form action="" method="get"> 
							 <!--<div class="row">
                                <div class="col-lg-4">
                                   
                                </div>
                                <div class="col-lg-8 col-xs-12">
                                    <div class="input-group">
                                        <span class="input-group-append">
                                             @if(Session::get('permission')!='2')
                                            <button class="btn btn-secondary" type="button"  onclick="compPay()">Combined Payment</button>&nbsp;
											<button class="btn btn-secondary" type="button"  data-toggle="modal" data-target="#statementsMDL">Statement</button>

                                            @endif
                                        </span>
                                    </div>
                                </div>

                              
                            </div>-->
							
                            <!--<div class="row">
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
                            </div>-->
                        </form>
                        <br class="d-block d-sm-none"/>
                        <div class="table-responsive">
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important">
                                <thead>
                                    <tr role="">
                                        {{-- <th width="5%" style="text-align:center;">#</th> --}}
                                        <th width="5%">Code</th>
                                        <th width="5%">Order No.</th>
                                        <th width="5%">TT REF</th>
                                        <th width="10%">Name</th>
                                        {{-- <th width="15%">Create by</th> --}}
                                        <th width="5%">Ship Date</th>
                                        <th width="5%">Load Date</th>
                                        <th width="5%">Load time</th>
                                        <th width="5%">Status</th>
										<th width="5%">Payment Status</th>
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(@$rows)
                                    @foreach($rows as $key => $row)
                                        <tr role="row" class="odd" data-row="{{$key+1}}" data-id="{{$row->id}}">
                                            @php $ship = \App\ShipToModel::where('id',$row->shipto_id)->first() @endphp
                                            @php $client = \App\ClientModel::where('id',$row->client_id)->first() @endphp
                                            @php $airport = \App\AirportModel::where('id',$row->airport_id)->first() @endphp
                                            @php $ocode = \App\OrderModel::where('id',$row->order_id)->first();

                                            if(isset($ocode->code))
                                            {
                                               $ocode= $ocode->code;
                                            }
                                            else
                                            {
                                                $ocode="";
                                            }

                                             @endphp
                                          
                                            {{-- <td data-label="No.">
                                                <span class="no">{{$key+1}}</span>
                                                <i class="fas fa-bars handle" style="display:none;"></i>
                                            </td> --}}
											
                                            <td data-label="Code">
												
                                               <span>{{$row->code}}</span>
                                            </td>
                                            <td data-label="orderCode">
                                               <span>{{$ocode}}</span>
                                            </td>
                                            <td data-label="TT REF">
                                                <span>{{$row->tt_ref}}</span>
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
                                            <td data-label="Ship Date">
                                               <span>{{date('d-M-Y',strtotime($row->ship_date))}}</span> 
                                            </td>
                                            <td data-label="Load Date">
                                                <span>{{date('d-M-Y',strtotime($row->load_date))}}</span> 
                                             </td>
                                             <td data-label="Load Time">
                                                <span>{{date('H:i',strtotime($row->load_time))}}</span> 
                                             </td>
                                            <td data-label="status">
                                                @php if($row->ship_status == 'pending'){ $color = 'blue';}elseif($row->ship_status == 'carry'){ $color = '#FFBF00'; }elseif($row->ship_status == 'shipping'){ $color = 'orange'; }elseif($row->status == 'complete'){ $color = 'green'; }elseif($row->ship_status == 'Voided'){ $color = 'red'; }elseif($row->ship_status == 'restored'){ $color = '#AA336A'; } @endphp
                                                <span style="color:{{$color}}">{{ $row->ship_status }}</span>
                                            </td>
											 <td data-label="status">
                                                @php if($row->payment_status == 'PAID'){ $color = 'green';}elseif($row->payment_status == 'Partial Payment'){ $color = '#FFBF00'; }elseif($row->payment_status == 'Over Paid'){ $color = 'orange'; }elseif($row->payment_status == 'Not Paid'){ $color = 'red'; }elseif($row->payment_status == ''){ $color = 'blue'; } @endphp
                                                <span style="color:{{$color}}">{{ $row->payment_status }}</span>
                                            </td>
                                            <td data-label="Action">
											@if($row->ship_status!='Voided')
                                                <a href="{{url("$folder/view/$row->id")}}" class="btn btn-secondary" title="View"><i class="fas fa-eye"></i></a>
                                                <a href="{{url("$folder/report/$row->id")}}" target="_blank" class="btn btn-secondary print" data-id="{{$row->id}}" title="Print"><i class="far fa-file-pdf"></i></a> 
                                                <a href="{{url("$folder/invoice_report/$row->id")}}" target="_blank" class="btn btn-secondary print1" data-id="{{$row->id}}" title="Print Invoice"><i class="far fa-file-pdf"></i></a>
                                                {{-- <a href="javascript:" class="btn btn-secondary edit" data-toggle="modal" data-target="#Edit" data-id="{{$row->id}}" title="Edit"><i class="fas fa-edit"></i></a> --}}
                                                @if($row->ship_status == 'pending')
                                                 <a href="{{url("$folder/$row->id")}}" class="btn btn-secondary edit" title="Edit"><i class="fas fa-edit"></i></a>

                                                <a href="javascript:" class="btn btn-primary carry" data-order_id="{{$row->order_id}}" data-id="{{$row->id}}" title="Shipped"><i class="fas fa-plane"></i></a>
                                               

                                                @elseif($row->ship_status == 'carry')
                                                <a href="javascript:" class="btn btn-primary shipping" data-id="{{$row->id}}" title="Shipping"><i class="fas fa-plane"></i></a>
                                                @endif
												
                                                <a href="javascript:" class="btn btn-success Update-GW" onclick="updateGW('{{$row->id}}','{{$row->AWB_GW}}')" title="Update GW"><i class="fas fa-weight"></i></a>
                                                <!--@if($row->ship_status== 'pending')
                                                <a href="{{url("$folder/restore/$row->id")}}" class="btn btn-danger restore" title="Restore"  onclick="return confirm('Are You Sure?')"><i class="fas fa-undo"></i></a>
                                                @endif-->
                                                <a href="javascript:" class="btn btn-success note" onclick="set_invoice_note('{{$row->id}}')" title="Set Invoice Note"><i class="fas fa-comment"></i></a>
												
												
												@if($row->ship_status== 'pending')
													@if($key!='0')
														<a href="{{url("$folder/restore/$row->id/$key")}}" class="btn btn-danger restore" title="Restore"  onclick="return confirm('Are You Sure?')"><i class="fas fa-undo"></i></a>
													@else
														<a href="{{url("$folder/restore/$row->id/$key")}}" class="btn btn-danger restore" title="Restore"  onclick="return confirm('Are You Sure?')"><i class="fas fa-undo"></i></a>
													@endif
													@if($key!='0')
													<a href="javascript:void(0)" class="btn btn-danger restore"  title="Cancel" onclick=" undoo({{ $row->id }})" ><i class="fas fa-times-circle" ></i></a>
													
													@endif
												
                                                @endif
												@else
													
                                                <a href="javascript:void(0)" class="btn btn-info "  title="Info" onclick=" info('{{$row->code}}','{{ $ocode }}','{{$row->reason}}')" ><i class="fas fa-eye" ></i></a>
												@endif
												
												@if($row->ship_status != 'Voided')
												 <a href="{{url("$folder/claim/$row->id")}}" class="btn btn-secondary" title="Claim"><i class="fas fa-exchange-alt"></i></a>
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

<div class="modal fade" id="Edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Edit</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="/invoice/editTotal" id="Form" name="Form" method="post" enctype="multipart/form-data">
            <input type="hidden" id="id" name="id" value="">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-4" id="info">

                    </div>
                </div>
                <div class="row">
                    <div class="form-group col-lg-6">
                        <h6>Total GW</h6>
                        <input type="number" class="form-control" id="total_gw" name="total_gw" step="0.01" min="0" value="" required>
                    </div>
                    <div class="form-group col-lg-6">
                        <h6>Total Packages</h6>
                        <input type="number" class="form-control" id="total_package" name="total_package" step="0.01" min="0" value="" required>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                <button class="btn btn-primary submit" type="submit">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="restore" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Insert Reason</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="{{url('/invoice/restore1')}} " enctype="multipart/form-data" method="post">
            <input type="hidden" id="inv_id" name="inv_id" value="">
            @csrf
            <div class="modal-body">
                
                <div class="row">
                    <div class="form-group col-lg-12">
                        <h6>Reason</h6>
                        <input type="text" class="form-control" id="invoice_reason" name="invoice_reason" placeholder="Reason" required>
                    </div>
                    
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                <button class="btn btn-primary submit" type="submit">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>
<button class="open_mdl_r" data-toggle="modal" data-target="#restore" type="button" style="display:none;"></button>

<div class="modal fade" id="reason" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Reason</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <table class="table table-striped no-footer table-res">
				<tr>
					<th>Code</th><th>Order No.</th><th>Reason</th>
				</tr>
				<tr>
					<td class="inv_id"></td>
					<td class="or_id"></td>
					<td class="rea_id"></td>
				</tr>
			</table>
			<div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
                
            </div>
        </div>
    </div>
</div>

<button class="open_mdl_reason" data-toggle="modal" data-target="#reason" type="button" style="display:none;"></button>
<script type="text/javascript">
function updateGW(id,val)
{
    var c=prompt("Please enter gross weight(AWB_GW)",val);


    var AWB_GW=parseFloat(c);
    if(AWB_GW>0)
    {

        var url="{{url('/invoice/updateGW')}}?id="+id+"&AWB_GW="+AWB_GW;
        window.location.href=url;
    }
    else
    {
        if(c!=null)
        alert("Please enter correct number")
    }
}

function undoo(id)
{
	$("#inv_id").val(id)
	$(".open_mdl_r").click();
					
                 
          
}
function info(inv_id,Order_id,reason)
{

	$(".inv_id").html(inv_id)
	$(".or_id").html(Order_id)
	$(".rea_id").html(reason)
	$(".open_mdl_reason").click()
	
}
//var tot=0;
//var myArray[];
function calculateamt(id)
{	





	var tot = 0;
	$('.paid_amts').each(function() {
		//var currentElement = $(this).val();
		
		var combat = $(this).val();
		 if (!isNaN(combat) && combat.length !== 0) {
                tot += parseFloat(combat);
            }
		/*tot+=parseFloat($(this).val());
		alert(tot);
		var c=(tot).toFixed(2);
		$(".tamount").text(c+"THB")
		$("#total").val(c);*/
    
	});
	var c=(tot).toFixed(2);
	$('#total').val(c);
	$('.tamount').text(c);
}
function compPay(cls)
{
    $(".compayBtn").click();
    $(".ttr").hide();
}
function getDue(cls)
{
	//alert(cls);
    $(".ttr").hide();
    $("."+cls).show();
   // $("checkboxs").prop("checked",false);
}

</script>





