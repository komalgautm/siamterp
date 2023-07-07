<div>
    <div class="fade-in">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div class="card">
                    <div class="card-header">

                        <a href="{{ url("/$folder") }}" class="card-header-action">Statement</a>

                       
                    </div>

                    <div class="card-body">
                
                <div class="row">
                    <div class="form-group col-lg-6" style="float: right">
                        @if(isset($rows[0]))
                         <a href="{{ url("/$folder/statementsExp?vendor=$vendors->id&from=$from&to=$to") }}" class="btn btn-danger" >Statement Export</a>
                         @endif
                          </div>
                </div>
                <div class="row">
                  
                    <div class="form-group col-lg-6">
                        <h6>Vendor Name</h6>
                      
                        <input type="text" class="form-control" id="staff_press" name="staff_press" value="{{ $vendors->name }}" readonly>
                    </div>
                    <div class="form-group col-lg-6">
                        <h6>From</h6>
                      <input type="date" class="form-control"  value="{{ $from }}" readonly>
                    </div>

                     <div class="form-group col-lg-6">
                        <h6>Vendor Address</h6>
                      
                        <input type="text" class="form-control"  value="{{ $vendors->address }}" readonly>
                    </div>
                    <div class="form-group col-lg-6">
                        <h6>To</h6>
                    <input type="date" class="form-control"  value="{{ $to }}" readonly>
                    </div>
                </div>

                <style type="text/css">
td .table{border: 1px solid #000;}
                </style>
                        <br class="d-block d-sm-none"/>
                        <div class="table-responsive">
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important">
                                <thead>
                                    <tr role="">
                                       
                                        <th width="20%">Date</th>
                                        <th width="15%">Transaction</th>
                                      
                                        <th width="10%">Number</th>
                                        <th width="10%">Ammount</th>
                                        <th width="15%">Notes</th>
                                       
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($rows)
										<?php
											$total =0;
										?>
                                    @forelse($rows as $key => $row)
                                        <tr role="row" class="odd" data-row="{{$key+1}}" data-id="{{$row->id}}">
                                          
                                          <td data-label="Date">
                                       
                                               <span>{{date('d-M-Y',strtotime($row->txn_date))}}</span><br>
                                            </td>

                                            <td data-label="Transaction">
                                         
                                               <span>{{ucFirst($row->transaction)}}</span>
                                            </td>
                                             <td data-label="Number">
                                            
                                               <span>{{$row->numbers}}</span>
                                            </td>
                                            
                                            <td data-label="Amount">
                                           
                                                <span>@if($row->txn_mode=='dr') - @endif {{number_format($row->amount,2)}} THB</span><br>
                                            </td>
                                            <td data-label="Notes">
                                          
                                              <span>{{$row->notes}}</span>
                                            </td>
                                        </tr>
                                      <?php
										if($row->txn_mode=='dr')
										{
											$total = $total-$row->amount;
										}
										else
										{
											$total = $total+$row->amount;
										}
										
										?>
                                    @empty
                                    <tr class="text-center">
                                        <td colspan="6"> No Result Data.</td>
                                    </tr>
                                    @endforelse
                                      <tr class="">
                                        <td colspan="2"></td>
                                        <td > Total Amount:</td>
                                        <!--<td > <b>@if(isset($rows[0])) {{$rows[count($rows)-1]->total}} THB @else 0.00 THB @endif</b></td>-->
                                        <td > <b>@if(isset($rows[0])) {{number_format($total)}} THB @else 0.00 THB @endif</b></td>
                                        <td> </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            @if(Request::get('view')!='all') {{$rows->appends(request()->query())->links()}} @endif
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


