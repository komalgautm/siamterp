<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="viewForm" method="post" action="" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Inventory Management</a></span>
                        <span class="breadcrumb-item active">View Form</span>
                        <div class="card-header-actions"><small class="text-muted"><a href="https://getbootstrap.com/docs/4.0/components/input-group/#custom-file-input">docs</a></small></div>
                    </div>
                    <div class="card-body">
                        @csrf
                        
                        {{-- <div class="row">
                            <div class="header">
                                <div class="col-lg-12">
                                    
                                </div>
                            </div>
                        </div>
                        <br> --}}
                        <div class="table-responsive">
                            <table class="table table-striped no-footer table-res"  id="sorted_table" style="border-collapse: collapse !important" width="100%">
                                <thead>
                                    <tr role="">
                                        {{-- <th width="5%">#</th> --}}
                                        <th width="15%">Name</th>
                                        <th width="10%">Code</th>
                                        <th width="10%">Transection Date</th>
                                        <th width="10%">Quantity</th>
                                        <th width="5%">Unit</th>
                                        <th width="8%">Unit Value </th>
                                        <th width="5%">Transaction</th>
                                        <th width="5%">Transaction Value</th>
                                        
                                        <th width="5%">view</th>
                                        {{-- <th width="10%">Price</th>
                                        <th width="10%">Crate</th>
                                        <th width="10%">Total</th> --}}
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $rc=0;

                                    $rqty=0;@endphp
                                    @foreach($rows as $key => $row)
                                    <tr>
                                        {{-- <td data-label="No.">
                                            {{ $key+1 }}
                                        </td> --}}
                                        <td data-label="Name">
                                            @if($row->type == 'item') {{ $row->name_th_item.' / '.$row->name_en_item }}
                                            @else {{ $row->name_th_item }} @endif
                                        </td>
                                        <td data-label="Code">
                                            {{ $row->code }}
                                        </td>
                                        <td data-label="Transection Date">
                                            {{ date('d-M-Y',strtotime($row->transection_date)) }}
                                        </td>
                                        <td data-label="Quantity">
                                            @if($row->transection_type == 'buy')
                                             @php 

                                             $rqty+=$row->qty ;

                                             @endphp


                                            {{ number_format($row->qty,2) }}

                                            @elseif($row->transection_type == 'adjustment'&&$row->qty>0)



                                             @php 

                                             $rqty+=$row->qty ;

                                             @endphp


                                            {{ number_format($row->qty,2) }}

                                            @else
                                            {{ number_format($row->waste_qty,2) }} 

                                             @php $rqty+=$row->waste_qty ;@endphp

                                            @endif
                                        </td>
                                        <td data-label="Unit">
                                           @if($row->name_th_unit != '') {{ $row->name_th_unit.'/'.$row->name_en_unit }} @endif
                                        </td>
                                         <td data-label="receiving_cost ">
                                         {{number_format($row->receiving_cost,2)}}

                                        
                                        </td>
                                        <td data-label="transection_type">
                                            {{ $row->transection_type }} @if($row->transection_type == 'waste') / {{$row->transection_menu}} @endif
                                        </td>

                                         <td data-label="transectionValue">
                                           
                                       
                                            @if($row->transection_type == 'waste')

                                            {{number_format($row->transactionValue,2)}} 
                                            @php $rc+=$row->transactionValue ;@endphp

                                            @else
                                            
                                              {{number_format($row->transactionValue,2)}} 
                                              @php $rc+=$row->transactionValue;@endphp
                                            @endif




                                        </td>
                                        <td data-label="View">
                                            @php
                                                if($row->transection_menu == 'sorting'){
                                                    $sorting = App\SortingModel::where('id',$row->ref_id)->first();
                                                }
                                            @endphp
                                            @if($row->transection_menu == 'sorting' && @$sorting->note != "")
                                                <a href="javascript:" class="btn btn-secondary viewnote" data-toggle="modal" data-target="#viewNote" data-id="{{$sorting->id}}" title="View"><i class="fas fa-eye"></i></a> 
                                            @endif
                                        </td>
                                        {{-- <td data-label="Crate">
                                            {{ $item->crate }}
                                        </td>
                                        <td data-label="Total">
                                            {{ 'THB '.$item->total_price }}
                                        </td> --}}
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3"><strong style="font-size:18px"></strong></td>
                                        <td colspan="4"><strong style="font-size:18px"> @php($total=DB::table('transection')->where(['item_id'=>Request::segment(3),'unit'=>Request::segment(4)])->sum('qty'))
                                            {{number_format($rqty,2)}}</strong></td>
                                        <td colspan="2">
                                           <strong style="font-size:18px">@if($rqty>0) {{number_format($rc,2)}} @else 0.00 @endif</strong>
                                        </td>
                                     
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        
                    {{-- <div class="card-footer">
                        <div class="row">
                            <button class="btn btn-primary" type="submit" name="signup">Update</button>
                            <a class="btn btn-danger" href="{{url("/$folder")}}">Cancel</a>
                        </div>
                    </div> --}}
                </form>
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