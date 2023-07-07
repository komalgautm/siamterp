<div>
    <div class="fade-in"> 
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">                
                    <div class="card-header"> 
                        
                        <a href="{{ url("/$folder") }}" class="card-header-action">HPL Management</a>
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
                                        <input type="text" name="keyword" class="form-control" id="search" value="{{Request::get('keyword')}}" placeholder="Code/EAN/Name">
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
                                        <th width="10%">Code</th>
                                        <th width="5%">EAN</th>
                                        <th width="10%">Name</th>
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Unit</th>
                                        <th width="10%">Price/Unit</th>
                                        <th width="10%">AVG Weight</th>
                                        <th width="10%">Wastage</th>
                                        <th width="10%">Packing Date</th>
                                        @if(Auth::user()->role != 'staff')
                                        <th width="10%">Actions</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rows)
                                    @foreach($rows as $key => $row)
                                        <tr role="row" class="odd" data-row="{{$key+1}}" data-id="{{$row->id}}">
                                            {{-- <td data-label="No.">
                                                <span class="no">{{$key+1}}</span>
                                                <i class="fas fa-bars handle" style="display:none;"></i>
                                            </td> --}}
                                            <td data-label="Code">
                                               <span>{{ $row->code }}</span>
                                            </td>
                                            <td data-label="EAN">
                                                <span>{{ $row->ean_code }}</span>
                                            </td>
                                            <td data-label="Name">
                                               <span>{{ $row->name }}</span>
                                            </td>
                                            <td data-label="Quantity">
                                                <span>{{ $row->number_pack }}</span>
                                            </td>
                                            <td data-label="Unit">
                                                <span>{{ $row->unit_th." / ".$row->unit_en }}</span>
                                            </td>
                                            <td data-label="Price/Unit">
                                                <span>{{ $row->cost_ean }}</span>
                                            </td>
                                            <td data-label="AVG Weight">
                                                <span>{{ $row->net_weight }}</span>
                                            </td>
                                            <td data-label="Wastage">
                                                <span>{{ $row->wastage_percent*100 }}%</span>
                                            </td>
                                            <td data-label="Packing Date">
                                                <span>{{date('d-M-Y',strtotime($row->created))}}</span> 
                                             </td>
                                            @if(Auth::user()->role != 'staff')
                                            <td data-label="Action">
                                                {{-- <a href="javascript:" class="btn btn-secondary restore" title="restore" data-id="{{ $row->pack_id }}"><i class="fas fa-undo"></i></a> --}}

                                                  <a href="javascript:void(0)" onclick="getForm('{{$row->pack_id}}')" class="btn btn-secondary" title="View"><i class="fas fa-eye"></i></a>
                                            </td>
                                            @endif
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


















         <style>
/* The Modal (background) */
.modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
 z-index: 99999; /* Sit on top */
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
.modal-content {
  background-color: #fefefe;
  margin: auto;
  padding: 20px;
  border: 1px solid #888;
  width: 80%;
}

/* The Close Button */
.close {
  color: #aaaaaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close:hover,
.close:focus {
  color: #000;
  text-decoration: none;
  cursor: pointer;
}
#myBtn{display: none;}
</style>
</head>


<!-- Trigger/Open The Modal -->
<button id="myBtn" ></button>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div style="
    float: right;
"><span style="loat: right; " class="close">×</span></div>
    <h3>Packing Details (Dummy text)</h3>
  <div>
     
<form id="viewForm" method="post" action="" enctype="multipart/form-data"> 
                <input type="hidden" name="_token" value="I7Runi4x4W56bE31zLQuTlsT5XAr36kkMB7Cjori">                <input type="hidden" name="_method" value="PUT">                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="#">Packing Management</a></span>
                        <span class="breadcrumb-item active">View Form</span>
                        <div class="card-header-actions"><small class="text-muted"><a href="https://getbootstrap.com/docs/4.0/components/input-group/#custom-file-input">docs</a></small></div>
                    </div>
                    <div class="card-body">
                        <input type="hidden" name="_token" value="I7Runi4x4W56bE31zLQuTlsT5XAr36kkMB7Cjori">                        
                        <!-- <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#TH">TH</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#EN">EN</a></li>
                        </ul> -->
                        <div class="tab-content" id="myTabContent">
                            <br>
                            <div class="tab-pane fade show active" id="TH" aria-labelledby="home-tab">
                                <div class="row"> 
                                    <div class="form-group col-lg-3">
                                        <h6>Item : <span class="item">/</span> </h6>
                                        <h6>Unit Count :<span class="aslUnit"></span></h6>
                                        <h6>Quantity : <span class="takenQty"></span></h6>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <b>Code :</b> <span class="code"></span> <br>
                                        <b>Quantity :</b> <span class="aslQty"></span> <br>
                                        <b>Blue crate :</b> <span class="crate"></span>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Number of Staff : <span class="staff"></span></h6>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Start : <span class="start"></span></h6>
                                        <h6>Finish : <span class="finish"></span></h6>
                                    </div>
                                </div>   
                            </div>
                           <script type="text/javascript">

                            
                              

                           </script>
                        </div>
                        <br>
                        <div class="row">
                            
                        </div>
                        <hr>
                        <div class="table-responsive EANLIST">
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important" width="100%">
                                <thead>
                                    <tr role="">
                                        <th width="5%">#</th>
                                        <th width="10%">EAN</th>
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Unit</th>
                                    </tr>
                                </thead>
                                <tbody> </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-danger" onclick="$('.close').click()" href="javascript:void(0)">Back</a>                    
                    </div>
                
            </div></form>



  </div>
  </div>

</div>

<script>

// Get the modal
var modal = document.getElementById("myModal");

// Get the button that opens the modal
var btn = document.getElementById("myBtn");

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks the button, open the modal 
btn.onclick = function() {
  modal.style.display = "block";
}

// When the user clicks on <span> (x), close the modal
span.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>
