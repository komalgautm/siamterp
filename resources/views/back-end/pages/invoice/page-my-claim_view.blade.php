<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="viewForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder/myClaims")}}">Claim Management</a></span>
                        <span class="breadcrumb-item active">View Form</span>
                        <div class="card-header-actions"><small class="text-muted"><a href="https://getbootstrap.com/docs/4.0/components/input-group/#custom-file-input">docs</a></small></div>
                    </div>
                    <div class="card-body">
                        @csrf
                        
                       
                        <div class="tab-content" id="myTabContent">
                            <br>
                            <div class="tab-pane fade show active" id="TH" aria-labelledby="home-tab">
                                <div class="row"> 
                                    <div class="form-group col-lg-4">
                                        <h6>Claim Code</h6>
                                        <input type="text" id="code" name="code" class="form-control" placeholder="" value="{{ $row->claim_code }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6> Date</h6>
                                    <input type="text" id="created" name="created" class="form-control" placeholder="" value="{{ date('d-M-Y',strtotime($row->created_at)) }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Create By</h6>
                                    <input type="hidden" id="user" name="user" value="{{ $row->user_id }}">
                                    @php $user = \App\UserModel::where('id',$row->user_id)->first(); @endphp 
                                    <input type="text" id="user_name" name="user_name" class="form-control" value="{{ $user->name }}" readonly>
                                    </div>
                                </div>
                                <hr>
                              
                            </div>
                          
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important" width="100%">
                                <thead>
                                    <tr role="">
                                        
                                        <th width="15%">ITF</th>
                                        <th width="10%">Box</th>
                                        <th width="10%">Order Unit Price</th>
                                        <th width="10%">Claim Unit</th>
                                        <th width="10%">Claim Qty</th>
                                        
                                        <th width="10%">Line Total</th>
                                        
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $key => $detail)
                                    <tr id="row{{ $key+1 }}" data-id="{{ $key+1 }}">
                                 
                                        <td data-label="ITF">
                                            @php $itf = \App\ITFModel::where('id',$detail->itf_id)->first(); @endphp
                                            {{ $itf->name }}
                                        </td>
                                         <td data-label="Box">
                                            
                                                @php $number_box = \App\InvoiceDetailModel::where('invoice_id',$detail->invoice_id)->where('itf_id',$detail->itf_id)->first()->number_box; @endphp
                                                {{ $number_box }}
                                         
                                        </td>
                                        <td data-label="Unit Price">
                                            
                                                {{ $detail->unit_price }} {{ $row->currency }}
                                           
                                        </td>
                                        <td data-label="Claim Unit">
                                            @php $unit = \App\UnitCountModel::where('id',$detail->claim_unit)->first(); @endphp
                                            {{ $unit->name_th.'/'.$unit->name_en }}
                                        </td>
                                     
                                        <td data-label="Quantity">
                                            {{ $detail->claim_qty }}
                                        </td>
                                      
                                     
                                        <td data-label="Line Total">
                                            {{ $detail->line_total }}
                                        </td>
                                    </tr>
                                    @endforeach
                                      <tr>
                                        <td colspan="3">&nbsp;</td>
                                        <td colspan="1" class="claim_qty"> <b><span>{{$row->claim_qty}}</span></b></td>
                                        <td class="totals">Total: <b><span>{{$row->total_price}}</span>{{ $row->currency }}</b></td>
                                       

                                    </tr> 
                                </tbody>
                            </table>
                        </div>
                        <hr>
                        @if(strlen($row->remarks)>1)
                         <div class="row">
                           <p><b>Remarks:</b>  {{$row->remarks}}</p>
                         </div>
                         @endif



                       <div class="row clImg">
                        @if($row->image!='na')
                        @foreach(explode(',',$row->image) as $im)
                       <div class="col-md-1 col-lg-1 col-sm-1 col-3">
                        <img src="{{url($im)}}" onclick="showImg(this)" width="100">
                       </div>
                       @endforeach
                       @endif

                   
                     </div>




                    </div>
                    <div class="card-footer">
            
                        <a class="btn btn-danger" href="{{url("/$folder/myClaims")}}">Back</a>                    
                    </div>
                </form>
            </div>            
        </div>
    </div>      

<img style="display:none" id="myImg">

<!-- The Modal -->
<div id="myModal" class="modal">
  <span class="close">&times;</span>
  <img class="modal-content" id="img01">
  <div id="caption"></div>
</div>

<script>

    function showImg(th)
    {
        var src=$(th).attr("src");
        $("#myImg").attr("src",src);
        $("#myImg").click();
    }
// Get the modal
var modal = document.getElementById("myModal");

// Get the image and insert it inside the modal - use its "alt" text as a caption
var img = document.getElementById("myImg");
var modalImg = document.getElementById("img01");
var captionText = document.getElementById("caption");
img.onclick = function(){
  modal.style.display = "block";
  modalImg.src = this.src;
  captionText.innerHTML = this.alt;
}

// Get the <span> element that closes the modal
var span = document.getElementsByClassName("close")[0];

// When the user clicks on <span> (x), close the modal
span.onclick = function() { 
  modal.style.display = "none";
}
</script>

<!-- </div>  -->       

<style type="text/css">
.clImg img{
    cursor: pointer;
}



#myImg {
  border-radius: 5px;
  cursor: pointer;
  transition: 0.3s;
}

#myImg:hover {opacity: 0.7;}


.modal {
    z-index: 999999!important;
  display: none; /* Hidden by default */
  position: fixed; 

  padding-top: 100px; /* Location of the box */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgb(0,0,0); /* Fallback color */
  background-color: rgba(0,0,0,0.6); /* Black w/ opacity */
}

/* Modal Content (image) */
.modal-content {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
}

/* Caption of Modal Image */
#caption {
  margin: auto;
  display: block;
  width: 80%;
  max-width: 700px;
  text-align: center;
  color: #ccc;
  padding: 10px 0;
  height: 150px;
}

/* Add Animation */
.modal-content, #caption {  
  -webkit-animation-name: zoom;
  -webkit-animation-duration: 0.6s;
  animation-name: zoom;
  animation-duration: 0.6s;
}

@-webkit-keyframes zoom {
  from {-webkit-transform:scale(0)} 
  to {-webkit-transform:scale(1)}
}

@keyframes zoom {
  from {transform:scale(0)} 
  to {transform:scale(1)}
}

/* The Close Button */
.close {
  position: absolute;
  top: 15px;
  right: 35px;
  color: #f1f1f1;
  font-size: 40px;
  font-weight: bold;
  transition: 0.3s;
}

.close:hover,
.close:focus {
  color: #bbb;
  text-decoration: none;
  cursor: pointer;
}

/* 100% Image Width on Smaller Screens */
@media only screen and (max-width: 700px){
  .modal-content {
    width: 100%;
  }
}
</style>






        