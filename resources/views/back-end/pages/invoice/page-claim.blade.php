<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="viewForm" method="post" action="{{url('invoice/saveClaim')}}" enctype="multipart/form-data" onsubmit="return confirm('Are you sure?')"> 
                @csrf
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Invoice Management</a></span>
                        <span class="breadcrumb-item active">Claim Form</span>
                        <div class="card-header-actions"><small class="text-muted"><a href="https://getbootstrap.com/docs/4.0/components/input-group/#custom-file-input">docs</a></small></div>
                    </div>
                    <div class="card-body">
                        @csrf
                        
                      
                        <div class="tab-content" id="myTabContent">
                            <br>
                            <div class="tab-pane fade show active" id="TH" aria-labelledby="home-tab">
                                <div class="row"> 
                                    <div class="form-group col-lg-4">
                                        <h6>Code</h6>
                                        <input type="text" id="code" name="code" class="form-control" placeholder="" value="{{ $row->code }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Setup Date</h6>
                                    <input type="text" id="created" name="created" class="form-control" placeholder="" value="{{ date('d-M-Y',strtotime($row->created)) }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Create By</h6>
                                    <input type="hidden" id="user" name="user" value="{{ $row->user_id }}">
                                    @php $user = \App\UserModel::where('id',$row->user_id)->first(); @endphp 
                                    <input type="text" id="user_name" name="user_name" class="form-control" value="{{ $user->name }}" readonly>
                                    </div>
                                </div>
                             
                           
                            </div>
                          
                          
                        </div>
                        <hr>
                        <div class="table-responsive">
                         
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important" width="100%">
                                <thead>
                                    <tr role="">
                                        <th width="5%">#</th>
                                        <th width="15%">ITF</th>
                                        <th width="10%">Order Unit</th>
                                        <th width="10%">Box</th>
                                        <th width="10%">Order Unit Price</th>
                                     
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Unit</th>
                                        <th width="10%">Line Total</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($details as $key => $detail)
                                       @if($detail->claim==null)
                                    <tr id="row{{ $key+1 }}" data-id="{{ $key+1 }}">
                                        <td data-label="#">{{ $key+1 }}</td>
                                        <td data-label="ITF">
                                            @php $itf = \App\ITFModel::where('id',$detail->itf_id)->first(); @endphp
                                            {{ $itf->name }}
                                            <input type="hidden" name="itf_id[]" value="{{$detail->itf_id}}">
                                            <input type="hidden" id="itf_cal_selling{{$key+1}}" value="{{$detail->itf_cal_selling}}">
                                            <input type="hidden" id="net_weightNew{{$key+1}}" value="{{$detail->net_weightNew}}">
                                            <input type="hidden" id="ean_qty{{$key+1}}" value="{{$detail->ean_qty}}">

                                        </td>
                                          <td data-label="Order Unit">
                                            @php $unit = \App\UnitCountModel::where('id',$detail->unitcount_id)->first(); @endphp
                                            {{ $unit->name_th.'/'.$unit->name_en }}
                                        </td>
                                        <td data-label="Box">
                                            
                                                {{ $detail->number_box }}
                                            
                                        </td>


                                         <td data-label="Unit Price">
                                           
                                                {{ $detail->itf_fx_price }} {{ $currencys->currency }}
                                                <input type="hidden" name="unit_price[]" value="{{$detail->itf_fx_price}}">
                                           
                                        </td>
                                        
                                     
                                        <td data-label="Quantity">

                                            @php 
                                               $fixPrice=$detail->itf_fx_price/$row->ex_rate;
                                            
                                            
                                              $inKG= $fixPrice /$detail->net_weightNew;

                                              $inPC = $fixPrice/$detail->ean_qty;

                                            



                                            @endphp
                                          <input type="hidden" id="inKG" value="{{$inKG}}">
                                          <input type="hidden" id="inPC" value="{{$inPC}}">
                                        
                                         <input type="number" class="form-control lqty{{$key+1}} qty" name="quantity[]" max="{{$inPC}}" min="0" placeholder="Enter quantity" value="0" onchange="myFunction({{$key+1}},this,{{ $detail->itf_fx_price }},{{$detail->itf_cal_selling}},{{$detail->net_weightNew}},{{$detail->ean_qty}})"  step='0.1'  required>
                                        </td>
                                      

                                          <td data-label="Unit">
                                            <select onchange="changeUnit({{$key+1}},this)" name="unitcount[]" id="unitcount{{ $key+1 }}"  class="form-control unitcount unt{{$key+1}}" for="{{$detail->unitcount_id}}" style="width:100%" required>
                                              
                                                @foreach ($units as $u)
                                               
                                                  
                                                 <option value="{{ $u->id }}" @if($detail->unitcount_id == $u->id) selected @endif>{{ $u->name_th.' / '.$u->name_en }}</option>
                                             
                                              
                                                @endforeach
                                            </select>
                                        </td>

                                        <td data-label="line Total">
                                      
                                         <input type="text" onkeyup="totalCalculate()" min="0" onchange="totalCalculate()" class="form-control lineTotal{{$key+1}} lineTotal" name="lineTotal[]"  placeholder="Line Total" value="0">
                                        </td>

                                       
                                     
                                      
                                       
                                     
                                    </tr>

                                     @else
                                      <tr>
                                        <td data-label="#">{{ $key+1 }}</td>
                                        <td data-label="ITF">
                                            @php $itf = \App\ITFModel::where('id',$detail->itf_id)->first(); @endphp
                                            {{ $itf->name }}
                                           
                                        </td>
                                          <td data-label="Unit">
                                            @php $unit = \App\UnitCountModel::where('id',$detail->unitcount_id)->first(); @endphp
                                            {{ $unit->name_th.'/'.$unit->name_en }}
                                        </td>

                                         <td data-label="Unit Price">
                                            <div class="input-group">
                                                {{ $detail->itf_fx_price }} {{ $currencys->currency }}
                                              
                                            </div>
                                        </td>
                                    

                                         <td data-label="Quantity">
                                          
                                        
                                         <input type="number" class="form-control" value="xxxx" disabled>
                                        </td>
                                      

                                          <td data-label="Unit">
                                          <input type="number" class="form-control" value="xxxx" disabled>
                                        </td>

                                        <td data-label="line Total">
                                        <input type="text" class="form-control" value="Already claimed" disabled>
                                        </td>
                                    </tr>
                                        @endif
                                    @endforeach
                                    <tr>
                                        <td colspan="5">&nbsp;</td>
                                        <td colspan="2" class="claim_qty"> &nbsp; &nbsp; <b><span>0</span></b></td>
                                        <td class="totals">Total: <b><span>0</span>{{ $currencys->currency }}</b></td>
                                       

                                    </tr>  

                                </tbody>
                            </table>
                             <div class="input-group">
                                <input type="file" name="image[]" multiple>
                            </div>
                            
                        </div>
                        <hr>
                    
                       
                    </div>
                    <div class="card-footer">
                     <button class="btn btn-primary" type="submit" name="signup">Claim Submit</button>
                        <a class="btn btn-danger" href="{{url("/$folder")}}">Back</a>                    
                    </div>

                    <input type="hidden" name="claim_qty" id="claim_qty">
                    <input type="hidden" name="total_price" id="total_price">
                    <input type="hidden" name="code" value="{{$row->code}}">
                    <input type="hidden" name="invoice_id" value="{{$row->id}}">
                    <input type="hidden" name="order_id" value="{{$row->order_id}}">
                    <input type="hidden" id="ex_rate" value="{{$row->ex_rate}}">
                    <input type="hidden" name="currency" value="{{$currencys->currency}}">
                    <input type="hidden" name="fullUrl" value="invoice">
                </form>
            </div>            
        </div>
    </div>              
</div> 

<script type="text/javascript">

function totalCalculate()
{
    var t,lq,qty=0,sum=0;
    for(var i=1;i<={{$key+1}}; i++)
    {
        if($(".lqty"+i).val())
        {
           
          t=0;
          lq=0;
          t=parseFloat($(".lineTotal"+i).val());
          lq=parseFloat($(".lqty"+i).val());
          sum=sum+t;
          qty=qty+lq;
       }
    }

     $(".totals b span").text(sum.toFixed(2))
     $("#claim_qty").val(qty);
     $(".claim_qty b span").text(qty);
     $("#total_price").val(sum.toFixed(2));
} 
function myFunction(n,th,price,selling,net_weight,eanqty)
{

     var maxPC= (parseFloat($("#inPC").val())).toFixed(0);
     var maxKG= (parseFloat($("#inKG").val())).toFixed(3);
 var qty=parseFloat($(th).val());
 var itfUnit=$(".unt"+n).val();
 var itf_cal_selling=parseFloat(selling);
 var net_weightNew=parseFloat(net_weight);
 var ean_qty=parseFloat(eanqty);
var ex_rate=parseFloat($("#ex_rate").val());
var fixPrice=parseFloat(itf_cal_selling/ex_rate).toFixed(2);

if(itfUnit == '2')
{
    fixPrice = (parseFloat(fixPrice) / parseFloat(net_weightNew));

} 
else if (itfUnit == '1')
{
    fixPrice = (parseFloat(fixPrice) / parseFloat(ean_qty));

}

if(itfUnit=='1')
{ 
    //alert("maxPC="+maxPC+" qty:"+qty);
    
     if(maxPC<qty)
     {
        $(th).val(maxPC);
        qty=maxPC;
        alert("Max quantity is "+maxPC+" PC");
     }
}

if(itfUnit=='2')
{
     // alert("maxKG="+maxKG+" qty:"+qty);
   if(maxKG<qty)
     {
        $(th).val(maxKG);
        qty=maxKG;
        alert("Max quantity is "+maxKG+" KG");
     } 
}

 $(".lineTotal"+n).val((qty*fixPrice).toFixed(2))
  totalCalculate();
}

function changeUnit(n,th)
{
      var max=(parseFloat($("#inPC").val())).toFixed(0);
     var unt=$(th).val();
     if(unt=='2')
     {
        max= (parseFloat($("#inKG").val())).toFixed(3);
     }
    

    $(".lqty"+n).attr("max",max);
    $(".lqty"+n).change();
}
</script>        

        