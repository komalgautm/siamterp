<div>
    <div class="fade-in"> 
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">                
                    <div class="card-header"> 
                        
                        <a href="{{url("/$folder")}}" class="card-header-action">ASL Management</a>
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
                                        <input type="text" name="keyword" class="form-control" id="search" value="{{Request::get('keyword')}}" placeholder="Code/Name">
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
                                    <tr role="" >
                                        <th width="10%">Code</th>
                                        <th width="10%">Name</th>
                                        <th width="5%">Quantity</th>
                                        <th width="10%">Unit</th>
                                        @if(Auth::user()->role != 'staff')
                                        <th width="10%">Price/Unit</th>
                                        @endif
                                        <th width="5%">Blue Crate</th>
                                        @if(Auth::user()->role != 'staff')
                                        <th width="5%">AVG Weights</th>
                                        @endif
                                        <th width="10%">Sorting Date</th>
                                        <th width="15%">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rows)
                                    @foreach($rows as $key => $row)
                                        <tr role="row" class="odd rw{{$key+1}}" data-row="{{$key+1}}" data-id="{{$row->id}}">
                                            <td data-label="Code" class="cd">
                                             {{ $row->code }}
                                            </td>
                                            <td data-label="Name" class="nm">
                                                @if($row->type == 'item')
                                                {{$row->name_th_item}} @if($row->name_en_item != null) / {{ $row->name_en_item }} @endif
                                                @else
                                                {{ $row->name_th_item }}
                                                @endif
                                            </td>
                                            <td data-label="Quantity" for="{{$row->balance}}" class="qt">
                                                {{ number_format($row->balance,2) }}
                                            </td>
                                            <td data-label="Unit" class="un">
                                                {{$row->name_th_unit}} @if($row->name_en_unit != null) / {{ $row->name_en_unit }} @endif
                                            </td>
                                            @if(Auth::user()->role != 'staff')
                                            <td data-label="Price/Unit">
                                                {{$row->total_price}} THB
                                            </td>
                                            @endif
                                            <td data-label="Crate">
                                                {{ $row->blue_crate }}
                                            </td>
                                            @if(Auth::user()->role != 'staff')
                                            <td data-label="AVG Weights">
                                                {{ $row->avg_weight }}
                                            </td>
                                            @endif
                                            <td data-label="Sorting Date">
                                                {{date('d-M-Y H:i:s',strtotime($row->sorting_date))}}
                                            </td>
                                            <td data-label="Action">
                                                @if($row->note != "")
                                                <a href="javascript:" class="btn btn-secondary viewnote" data-toggle="modal" data-target="#viewNote" data-id="{{$row->id}}" title="View"><i class="fas fa-eye"></i></a> 
                                                @endif
                                                 @if(Session::get('permission')!='2')
                                                @if(Auth::user()->role == 'admin' || Auth::user()->role == 'superadmin')
                                                <a class="btn" for="javascript:void(0)"  href="{{url("/$folder/create/$row->id")}}"><i class="fas fa-tape"></i></a>
                                                @endif
                                                @endif
                                                <a href="javascript:" class="btn btn-secondary " onclick="wasteCall('{{$key+1}}',{{$row->item_id}},{{$row->unit}},{{$row->receiving_cost}},{{$row->id}})" title="waste"><i class="fas fa-trash"></i></a>
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

<div class="modal fade" id="viewNote" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Note</h4>
                <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <form action="" id="Form" name="Form" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="row">
                    <div class="form-group col-lg-12">
                        <textarea name="note" id="note" class="form-control" cols="30" rows="3" readonly></textarea>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-dismiss="modal">Close</button>
            </div>
            </form>
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
</head>
<body>


</style>
</head>
<body>



<!-- Trigger/Open The Modal -->
<div id="myModalWaste" class="modalWaste">



<div id="myBtnWaste" style="display: none;"> </div>

  <!-- Modal content -->
  <div class="modal-contentWaste">
    <div class="modal-headerWaste">
      <span class="closeWaste">&times;</span>
      <h2>Register Damage/Wastage </h2>
    </div>
    <div class="modal-bodyWaste">
     <form class="submitfrm" method="post" action="{{url('asl/registerWaste')}}">
          @csrf
     
        <p>Code:<b class="Rcode"></b></p>
        <p>Name:<b class="Rname"></b></p>
        <p class="qties">Total Qty:<b class="totalQty"></b> &nbsp; <span> Rest Qty:<b class="goodQty" >auto cal.</b></span></p>
     
         <div class="form-group">
                <label>Enter Quantity</label>
                <input type="number" name="wasteQty" id="wasteQty" onkeyup="checkQty(this.value)" min="0" class="form-control" placeholder="0" required>
                <input type="hidden" id="totalQty" name="totalQty" >
                <input type="hidden" id="code" name="code" >
                <input type="hidden" id="goodQty" name="goodQty" >
                <input type="hidden" id="item_id" name="item_id" >
                <input type="hidden" id="unit" name="unit" >
                <input type="hidden" id="receiving_cost" name="receiving_cost" >
                <input type="hidden" id="rowID" name="rowID" >
                
               
         </div>

         <div class="form-group" style="float: right;">
               <input type="button" class="btn btn-info " onclick="submitWaste()" value="Sumbit">
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

// When the user clicks anywhere outside of the modal, close it
// window.onclick = function(event) {
//   if (event.target == modal) {
//     modal.style.display = "none";
//   }
// }

function checkQty(v)
{
    v=parseFloat(v);
    const av=parseFloat($("#totalQty").val());
 if(isNaN(v)) 
        v=0;
    const bal=av-v

    $(".goodQty").text(bal)
    $("#goodQty").val(bal);
    if(av<v)
    {
        $("#wasteQty").val(0);
        $(".goodQty").text(av);
        $("#goodQty").val(av);
        alert("สินค้ามีจำนวนไม่พอ / Not enough products.");
         $("#wasteQty").focus();
    }

}


function wasteCall(n,item_id,unit_id,receiving_cost,rowID)
{

    const cl=".rw"+n;
    const name=$(cl+" .nm").text();
    const code=$(cl+" .cd").text();
    const unit=$(cl+" .un").text();
    const qty=parseFloat($(cl+" .qt").attr('for'));

    

    $("#wasteQty").val('');
    $(".Rname").text(name);
    $(".Rcode").text(code);
    $("#code").val(code);
    $(".totalQty").text(qty+unit);
    $("#totalQty").val(qty);
    $("#item_id").val(item_id);
    $("#unit").val(unit_id);
    $("#rowID").val(rowID);
    $("#receiving_cost").val(receiving_cost);
    $("#wasteQty").attr("max",qty);


    $("#myBtnWaste").click();
    $("#wasteQty").focus();
}


function submitWaste()
{
   const a=parseFloat($("#goodQty").val());
    const d=parseFloat($("#wasteQty").val());

    if(d<0)
    {
         alert("สินค้ามีจำนวนไม่พอ / Not enough products.");
       
    }
    else
    {
        $(".submitfrm").submit();
    }
}
</script>



