<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="editForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Operation Management</a></span>
                        <span class="breadcrumb-item active">Edit Form</span>
                        <div class="card-header-actions"><small class="text-muted"><a href="https://getbootstrap.com/docs/4.0/components/input-group/#custom-file-input">docs</a></small></div>
                    </div>
                    <div class="card-body">
                        @csrf
                        
                        {{-- <ul class="nav nav-pills">
                            <li class="nav-item"><a class="nav-link active" data-toggle="pill" href="#TH">TH</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="pill" href="#EN">EN</a></li>
                        </ul> --}}
                        <div class="tab-content" id="myTabContent">
                            <br>
                            <div class="tab-pane fade show active" id="TH" aria-labelledby="home-tab">
                                <div class="row"> 
                                    <div class="form-group col-lg-2">
                                        <h6>Order Code</h6>
                                        <input type="text" id="code" name="order_code" class="form-control" placeholder="" value="{{ $row->code }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <h6>Quotation Code</h6>
                                        @php $quotation = \App\QuotationModel::where('id',@$row->quotation_id)->first(); @endphp
                                        <input type="text" name="quotation_code" class="form-control" placeholder="" value="{{ @$quotation->code }}" readonly/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <h6>Load Date</h6>
                                    <input type="text" id="load_date" name="load_date" class="form-control" placeholder="" value="@if($row->load_date != ''){{ date('d-M-Y',strtotime($row->load_date)) }}@endif" readonly/>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <h6>Load Time</h6>
                                    <input type="text" id="load_time" name="load_time" class="form-control" value="@if($row->load_time != ''){{ date('H:i',strtotime($row->load_time)) }}@endif" readonly>
                                    </div>
                                    <div class="form-group col-lg-2">
                                        <h6>AWB</h6>
                                        <input type="text" name="awb_no" class="form-control" placeholder="" value="{{ @$row->awb_no }}" readonly/>
                                    </div>
                                </div>
                                

                               
                            {{-- <div class="row">
                                <div class="form-group col-lg-6">
                                    <button type="button" class="btn btn-xs btn-success" onclick="cal()">Calculate</button><br>
                                    <small style="color:red"><b>**ทุกครั้งที่มีการเปลี่ยนแปลงข้อมูลกรุณากดปุ่ม Calculate**</b></small><br>
                                    <small style="color:red"><b>**Every time information is changed, please press the button Calculate**</b></small>
                                </div>
                            </div> --}}
                            {{-- <div class="tab-pane fade" id="EN" aria-labelledby="home-tab">
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
                            </div> --}}
                        </div>
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important" width="100%">
                                <thead>
                                    <tr role="">
                                        <th width="5%">#</th>
                                        <th width="15%">สินค้า/Product</th>
                                        <th width="10%">แพ็ค/pack</th>
                                        <th width="10%">ต่อกล่อง/per box</th>
                                        <th width="10%">กก./kg</th>
                                        <th width="10%">กล่อง/box</th>
                                        <th width="10%">แพ็ค/ชิ้น/pack/piece</th>
                                        <th width="10%">กล่อง/box</th>
                                        <th width="10%">แพ็คเก็จจิ้ง/packaging</th>
                                        <th width="10%">Adjust(g)</th>
                                        <th width="10%">action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php   $cnt=0; @endphp
                                    @foreach($details as $key => $detail)
                                    @php
                                        $ean_qty = \App\ITFdetailModel::where(['type'=>'ean','itf'=>$detail->itf_id])->sum('ean_qty');
                                      

                                        if($detail->packaging!= '')
                                        {
                                            $cnt++;
                                        }
                                    @endphp
                                    <tr id="row{{ $key+1 }}" data-id="{{ $key+1 }}">
                                        <td data-label="#">{{ $key+1 }}  
                              
                            </td>
                                        <td data-label="สินค้า">
                                            {{ $detail->name }}
                                            <input type="hidden" class="operation_id" name="operation_id[]" value="{{ $detail->id }}">
                                        </td>
                                        <td data-label="แพ็ค">
                                            {{ round($detail->hpl_avg_weight,0) }}
                                        </td>
                                        <td data-label="ต่อกล่อง">
                                            {{ round($detail->ean_qty,0) }}
                                        </td>
                                        <td data-label="กก.">
                                         <!--    {{ $detail->nw }} -->
                                         @if(isset($detail->over_nw)&&$detail->box!=null)
                                                 <input type="number" min="0" class="form-control nw" name="nw[]" value="{{ @$detail->over_nw }}" @if($detail->packaging != '') readonly @endif>
                                                 @else
                                                  <input type="number" min="0" class="form-control nw" name="nw[]" value="{{ @$detail->nw }}" @if($detail->packaging != '') readonly @endif>
                                                 @endif

                                        </td>
                                        <td data-label="กล่อง">
                                            {{ $detail->number_box }}

                                        </td>
                                        <td data-label="แพ็ค/ชิ้น">
                                            {{ @($detail->ean_qty*$detail->number_box) }}
                                        </td>
                                        <td data-label="กล่อง">
                                            <input type="number" min="0" class="form-control box" name="box[]" value="{{ @$detail->box }}" @if($detail->box != '') readonly @endif>
                                        </td>
                                        <td data-label="พาเลท">
                                            <input type="number" min="0" class="form-control packaging" name="packaging[]" value="{{ @$detail->packaging }}" @if($detail->packaging != '') readonly @endif>
                                        </td> 
                                        <td data-label="Adjust(g)">
                                            <input type="number" min="0" class="form-control adjust" name="adjust[]" value="{{ @$detail->adjust }}" @if($detail->packaging != '') readonly @endif>

                                              <input type="hidden" name="packing_weight[]" class="packing_weight" value="{{($detail->new_weight)-($detail->net_weight)}}" readonly/>
                                          

                                        </td>
                                        <td data-label="action">
                                        @if($row->packedStatus == '0')
                                            @if($detail->box == '' && $detail->packaging == '')
                                            <button class="btn btn-danger pack" type="button"><i class="fas fa-tape"></i></button>
                                            @else
                                            <button class="btn btn-success unpack" type="button"><i class="fas fa-tape"></i></button>
                                            @endif
                                        @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    <div class="card-footer">
                        @if($row->packedStatus == '0')
                        <!-- <button class="btn btn-primary packed"  data-id="{{$row->id}}"  type="button">Packed</button> -->
                        <!-- <button class="btn btn-primary confirm" data-id="{{$row->id}}"  type="button">Packed</button> -->
                        @endif
                   
                        <a class="btn btn-danger cancel" href="{{url("/$folder")}}">Back</a>
                    </div>
                </form>
            </div>            
        </div>
    </div>              
</div>         

        
