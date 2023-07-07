<div>
    <div class="fade-in"> 
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">                
                    <div class="card-header"> 
                        
                        <a href="{{url("/$folder")}}" class="card-header-action">Inventory Management</a>
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
                                        <input type="text" name="keyword" class="form-control" id="search" value="{{Request::get('keyword')}}" placeholder="Name">
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
                                        <th width="15%">Name</th>
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Unit</th>
                                        <th width="10%">Value</th> 
                                        <th width="10%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rows)
                                    @foreach($rows as $key => $row)
                                        <tr role="row" class="odd" data-row="{{$key+1}}" data-id="{{$row->id}}">
                                            <td data-label="Name">
                                                @if($row->type == 'item')
                                                {{$row->name_th}} @if($row->name_en != null) / {{ $row->name_en }} @endif
                                                @else
                                                {{ $row->name_th }}
                                                @endif
                                            </td>
                                            <td data-label="Quantity">
                                                @php($waste=DB::table('transection')->where(['item_id'=>$row->id,'unit'=>$row->unit_id,'transection_type'=>'waste'])->sum('waste_qty'))
                                                @php($qty=DB::table('transection')->where(['item_id'=>$row->id,'unit'=>$row->unit_id])->where('transection_type','!=','waste')->sum('qty'))
                                                @if($qty != "" || $qty != 0){{ number_format(($qty+$waste),2) }}@else 0 @endif
                                            </td>
                                            <td data-label="Unit">
                                                {{ $row->unit_th }} @if($row->unit_en != null) / {{ $row->unit_en }} @endif

                                            </td>   
                                             <td data-label="Unit">
                                                {{number_format(($row->totalTr),2)}}
                                                                                          </td>
                                            <td data-label="Action">
                                                @if($qty != "" || $qty != 0)
                                                <a href="{{url("$folder/view/$row->id/$row->unit_id")}}" target="_blank" class="btn btn-secondary" title="View"><i class="fas fa-eye"></i></a>
                                                @endif

                                                <a href="javascript:" class="btn btn-secondary " onclick="adjustQty({{$row->id}},{{$row->unit_id}},'{{$row->type}}')" title="Adjustment"><i class="fas fa-adjust"></i></a>
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




<style type="text/css">
/* The Modal (background) */
.modalWaste {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
 z-index: 999999; /* Sit on top */
  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-contentWaste {
     background-color: #fefefe;
    margin: auto;
    padding: 16px;
    border: 1px solid #888;
    width: 39%;
  height: 296px;

}

/* The Close Button */
.closeWaste {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

@media only screen and (max-width: 600px) {
.modal-contentWaste {
    width: 100%;
}
}



/* Add Animation */
@-webkit-keyframes animatetop {
  from {top:-300px; opacity:0} 
  to {top:0; opacity:1}
}

@keyframes animatetop {
  from {top:-300px; opacity:0}
  to {top:0; opacity:1}
}

/* The Close Button */
.closeWaste {
  color: white;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.closeWaste:hover,
.closeWaste:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}

.modal-headerWaste {
  padding: 2px 16px;
  background-color: #4b4394;
  color: white;
}

.modal-bodyWaste {padding: 2px 16px;}

.modal-footerWaste {
  padding: 2px 16px;
  background-color: #4b4394;
  color: white;
}

.qties span{
        float: right;
    margin-right: 32px;
    color: blue;
    font-size: 24px;
}
.qties span b{
      
    color: #000;
}
</style>

<!-- Trigger/Open The Modal -->
<div id="myModalWaste" class="modalWaste">



<div id="myBtnWaste" style="display: none;"> </div>

  <!-- Modal content -->
  <div class="modal-contentWaste">
    <div class="modal-headerWaste">
      <span class="closeWaste">&times;</span>
      <h2>Adjust Quantity</h2>
    </div>
    <div class="modal-bodyWaste">
     <form class="submitfrm" method="post" action="{{url('inventory/adjust')}}">
          @csrf
     
      
     
         <div class="form-group">
                <label>Enter Quantity</label>
                <input type="number" name="qty" id="qty"  class="form-control" value="" placeholder="0" required>
            
                <input type="hidden" id="item_id" name="item_id" >
                <input type="hidden" id="type" name="type" >
                <input type="hidden" id="unit_id" name="unit_id" >
  
             
                
               
         </div>

         <div class="form-group" style="float: right;">
               <input type="submit" class="btn btn-info "  value="Sumbit">
         </div>

     </form>
    </div>
  
  </div>

</div>

<script>
// Get the modal
var modal = document.getElementById("myModalWaste");

// Get the button that opens the modal
var btn = document.getElementById("myBtnWaste");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("closeWaste")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}


function adjustQty(item_id,unit_id,type)
{

    $("#myBtnWaste").click();
    $("#qty").focus();
    $("#item_id").val(item_id);
    $("#unit_id ").val(unit_id );
    $("#type").val(type);
}


</script>