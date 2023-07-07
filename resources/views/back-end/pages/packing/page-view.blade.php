<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="viewForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Packing Management</a></span>
                        <span class="breadcrumb-item active">View Form</span>
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
                                        <h6>Item : {{ $row->name_th." / ".$row->name_en }}</h6>
                                        <h6>Unit Count : {{ $row->unit_th." / ".$row->unit_en }}</h6>
                                        <h6>Quantity : {{ $row->qty }}</h6>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <b>Code :</b> <span id="code">{{ $row->code }}</span> <br>
                                        <b>Quantity :</b> <span id="quantity">{{ $row->num_qty }}</span> <br>
                                        <b>Blue crate :</b> <span id="blue_crate">{{ $row->blue_crate }}</span>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Number of Staff : {{ $row->number_staff }}</h6>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Start : {{ date('H:i',strtotime($row->start)) }}</h6>
                                        <h6>Finish : {{ date('H:i',strtotime($row->finish)) }}</h6>
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
                                        <th width="5%">#</th>
                                        <th width="10%">EAN</th>
                                        <th width="10%">Quantity</th>
                                        <th width="10%">Unit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($details as $key => $detail)
                                    <tr id="row1" data-id="1">
                                        <td data-label="#">{{ $key+1 }}</td>
                                        <td data-label="EAN">
                                            {{$detail->name}}
                                        </td>
                                        <td data-label="Quantity">
                                            {{$detail->number_pack}}
                                        </td>
                                        <td data-label="Quantity">
                                           @if($detail->ean_unit==1)
                                           PC
                                           @else
                                           KG
                                           @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer">
                        <a class="btn btn-danger" href="{{url("/$folder")}}">Back</a>                    
                    </div>
                </form>
            </div>            
        </div>
    </div>              
</div>         

        