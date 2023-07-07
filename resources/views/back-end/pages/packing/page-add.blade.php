<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
			<input type="hidden" class="form-control dy_wage" value="{{$wage}}"  required>
            <form id="createForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Packing Management</a></span>
                        <span class="breadcrumb-item active">Create Form</span>
                        <div class="card-header-actions"><small class="text-muted"><a href="https://getbootstrap.com/docs/4.0/components/input-group/#custom-file-input">docs</a></small></div>
                    </div>
                    <div class="card-body">
                        @csrf
                        
                        <!-- <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#TH">TH</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#EN">EN</a></li>
                        </ul> -->
                        <div class="tab-content" id="myTabContent">
                            <br>
                            <div class="tab-pane fade show active" id="TH" aria-labelledby="home-tab">
                                <div class="row"> 
                                    <div class="form-group col-lg-3">
                                        <h6>Item</h6>
                                        <select class="form-control select_item select2" name="item" id="item" width="100%" required>
                                            <option value="">Select Item</option>
                                            @foreach ($items as $item)
                                                <option value="{{ $item->id }}">{{ $item->name_th." / ".$item->name_en }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Unit Count</h6>
                                        <select class="form-control unitcountPO select2" id="unit" name="unit" width="100%" required>
                                            <option value="">Select Unit</option>
                                             @foreach ($unit as $u)
                                                <option value="{{ $u->id }}">{{ $u->unit_th.' / '.$u->unit_en }}</option>
                                                @endforeach
                                                
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <b>Code :</b> <span id="code"></span> <br>
                                        <b>Quantity :</b> <span id="quantity"></span> <br>
                                        <b>Unit :</b> <span id="unitT"></span> <br>
                                     
                                        <b>Blue crate :</b> <span id="blue_crate"></span>
                                        <input type="hidden" id="sorting_id" name="sorting_id">
                                        <input type="hidden" id="balance" name="balance">
                                        <input type="hidden" id="cost_asl" name="cost_asl">
                                        <input type="hidden" id="aslPrice" name="aslPrice">





                                        <input type="hidden" id="avg_weight" name="avg_weight" value="">
                                     
                                       
                                       

                                   <!--      <input type="hidden" id="item" name="item" value=""> -->
                                        
                                        <input type="hidden" id="unitEAN"  value="2">




                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Quantity</h6>
                                        <input type="text" class="form-control" id="qty" name="qty" required>
                                        <input type="hidden" id="wastage" name="wastage" placeholder="wastage">
                                        <input type="hidden" id="wastage_weight" name="wastage_weight" placeholder="wastage_weight">
                                        <input type="hidden" id="cost" name="cost" placeholder="cost">
                                        <input type="hidden" class="form-control" id="ean_cost" name="ean_cost" placeholder="Auto Calculate" value="" readonly>




                                    <input type="hidden" id="packingQty" name="packingQty" value="">
                                    </div>
                                </div>   
                            </div>
                            <!-- <div class="tab-pane fade" id="EN" aria-labelledby="home-tab">
                                <div class="row"> 
                                    <div class="form-group col-lg-8">
                                        <h6>Title</h6>
                                        <input type="text" name="title_en" class="form-control" value=""/>
                                    </div>
                                </div>       
                                <div class="row"> 
                                        <div class="form-group col-lg-12">
                                            <h6>Caption</h6>
                                            <textarea type="text" name="caption_en" class="form-control" rows="6"></textarea>
                                        </div>
                                    </div> 
                                <div class="row"> 
                                    <div class="form-group col-lg-12">
                                        <h6>Detail</h6>
                                        <textarea type="text" name="detail_en" class="form-control tiny" rows="9"></textarea>
                                    </div>
                                </div>   
                            </div> -->
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <h6>Number of Staff</h6>
                                <input type="text" class="form-control" id="number_staff" name="number_staff" required>
                                <input type="hidden" class="form-control" id="wages" name="wages" placeholder="Auto Calculate" readonly>
                            </div>
                            <div class="col-lg-4">
                                <h6>Start</h6>
                                <input type="time" class="form-control" id="start_time" name="start" required>
                            </div>
                            <div class="col-lg-4">
                                <h6>Finish</h6>
                                <input type="time" class="form-control" id="finish_time" name="finish" required>
                            </div>
                        </div>
                        <br>
                        <div class="row">
                            {{-- <div class="form-group col-lg-6">
                                <small style="color:red"><b>**ทุกครั้งที่มีการเปลี่ยนแปลงข้อมูลกรุณากดปุ่ม Calculate**</b></small><br>
                                <small style="color:red"><b>**Every time information is changed, please press the button Calculate**</b></small><br>
                                <button type="button" class="btn btn-xs btn-success" >Calculate</button>
                            </div> --}}
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important" width="100%">
                                <thead>
                                    <tr role="">
                                        <th width="10%">EAN</th>
                                        <th width="10%">Quantity</th>
                                        <th width="5%">Unit</th>
                                        <th width="5%">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="row1" data-id="1">
                                        <td data-label="EAN">
                                            <select name="ean[]" id="ean1" class="form-control select_ean select2" style="width:100%" required>
                                                <option value="">Select EAN</option>
                                                {{-- @foreach ($eans as $ean)
                                                <option value="{{ $ean->id }}">{{ $ean->name }}</option>
                                                @endforeach --}}
                                            </select>
                                        </td>
                                        <td data-label="Quantity">
                                          <input type="number" class="form-control qty" for='1' name="quantity[]" min="0" laceholder="Enter quantity" value="" required>
                                            <input type="hidden" class="weight" name="weight[]">
                                            <input type="hidden" class="cost_packaging" name="cost_packaging[]">
                                            <input type="hidden" class="qty_packaging" name="qty_packaging[]">
                                            <input type="hidden" class="wrap_cost" name="wrap_cost[]">
                                            <input type="hidden" class="plus_cost" name="plus_cost[]">

                                            <input type="hidden" class="cost_cal" name="cost_cal[]">
                                            <input type="hidden" class="cost_packing" name="cost_packing[]">
                                            <input type="hidden" class="cost_wages" name="cost_wages[]">
                                            <input type="hidden" class="timeStaff" name="timeStaff[]">
                                            <input type="hidden" class="cost_ean" name="cost_ean[]">
                                            <input type="hidden" class="aslValue" name="aslValue[]">
                                            <input type="hidden" class="net_weight" name="net_weight[]" >
                                            <input type="hidden" class="ean_ppkg" name="ean_ppkg[]" >
                                            <input type="hidden" class="ean_unit" name="ean_unit[]" >
                                            <input type="hidden" class="balanceENA" name="balanceENA[]" >
                                                 <input type="hidden" class="eanQty" name="eanQty[]" >
                                            <input type="hidden" class="avgWeight" name="avgWeight[]" >
                                                 <input type="hidden" class="ean_uw" name="ean_uw[]" >
                                            <input type="hidden" class="netWeight" name="netWeight[]" >
                                            <input type="hidden" class="ean_uc" name="ean_uc[]" >
                                        </td>

                                          <td data-label="Unit">
                                            <select for="1" onchange="cal_ean(this,1)" name="unitcount[]" id="unitcount1" class="form-control unitcount select2" style="width:100%" required>
                                                <option value="">Select Unit</option>
                                                @foreach ($unit as $u)
                                                <option value="{{ $u->id }}">{{ $u->unit_th.' / '.$u->unit_en }}</option>
                                                @endforeach 
                                            </select>
                                        </td>
                                        <td data-label="Action">&nbsp;</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="header">
                                <div class="col-lg-12">
                                    <strong style="font-size:18px">Items :</strong>
                                    <button type="button" class="btn btn-xs btn-success add-row" style="display:">add</button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                       <button class="btn btn-primary" type="button" name="signup" onclick="calTime()">Create</button>
                        <a class="btn btn-danger" href="{{url("/$folder")}}">Cancel</a>                    
                    </div>
                </form>
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
</style>
</head>


<!-- Trigger/Open The Modal -->
<button id="myBtn" ></button>

<!-- The Modal -->
<div id="myModal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <span style=" float: right;" class="close">&times;</span>
    <h3>Only for test calculation</h3>
  <div>
      <table class="table" id="Tbl">
        <thead>
             <tr>
              <td colspan="7" >(wages+time) total:<b class="wtt"></b> </td>
             
          </tr>
              <tr>
          <td>Unit</td>
               <td>Complete QTY</td>
              <td>Net weight</td>
              <td>Ean ppkg </td>
              <td>Cal cost </td>
              <td>packing  </td>
              <td>Wages</td>
              <td>Ean cost </td>
              <td>Convert to asl Unit </td>
               <td>UC</td>
               <td>UW</td>
          </tr>
        </thead>
        <tbody>
            
        </tbody>

      </table>

       <table class="table">
        <thead>
              <tr>
              <td>Packed qty</td>
              <td>Wastage </td>
              <td>Wastage % </td>
             
          </tr>
        </thead>
        <tbody>
            <tr>
              <td class="pqty"></td>
              <td class="wt"></td>
              <td class="wtp"></td>
             
          </tr>
        </tbody>

      </table>
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
