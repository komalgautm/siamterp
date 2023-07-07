<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="createForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Freight Management</a></span>
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
                                        <h6>Vendor</h6>
                                        <select class="form-control select2" name="vendor" id="vendor" style="width:100%" required>
                                            <option value="">Select vendors</option>
                                            @foreach ($vendors as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Destination</h6>
                                        <select class="form-control select2" name="destination" id="destination" style="width:100%" required>
                                            <option value="">Select destinations</option>
                                            @foreach ($airports as $airport)
                                                <option value="{{ $airport->id }}">{{ $airport->country.' - '.$airport->city.' ['.$airport->airport_code.']' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Airline</h6>
                                        <select class="form-control select2" name="airline" id="airline" style="width:100%" required>
                                            <option value="">Select airlines</option>
                                            @foreach ($airlines as $airline)
                                                <option value="{{ $airline->id }}">{{ $airline->name.' ['.$airline->airline_code.']' }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Currency</h6>
                                        <select class="form-control select2" name="currency" id="currency" style="width:100%" required>
                                            <option value="">Select currencys</option>
                                            @foreach ($currencys as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->currency }}</option>
                                            @endforeach
                                        </select>
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
                        <hr>
                        <div class="table-responsive">
                            <table class="table table-striped no-footer table-res" id="sorted_table" style="border-collapse: collapse !important" width="100%">
                                <thead>
                                    <tr role="">
                                        <th width="5%">&nbsp;</th>
                                        <th width="10%">45+</th>
                                        <th width="10%">100+</th>
                                        <th width="10%">250+</th>
                                        <th width="10%">500+</th>
                                        <th width="10%">1000+</th>
                                        {{-- <th width="8%">Crate</th>
                                        <th width="12%">Total</th> --}}
                                        <th width="10%">2000+</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr id="row1" data-id="1">
                                        <td data-label="">
                                            <h6>Rate</h6>
                                        </td>
                                        <td data-label="45+">
                                            <div class="input-group">
                                                <input class="form-control rate_45" type="text" name="rate_45" placeholder="">
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                        <td data-label="100+">
                                            <div class="input-group">
                                                <input class="form-control rate_100" type="text" name="rate_100" placeholder="">
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                        <td data-label="250+">
                                            <div class="input-group">
                                                <input class="form-control rate_250" type="text" name="rate_250" placeholder="">
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                        <td data-label="500+">
                                            <div class="input-group">
                                                <input class="form-control rate_500" type="text" name="rate_500" placeholder="">
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                        <td data-label="1000+">
                                            <div class="input-group">
                                                <input class="form-control rate_1000" type="text" name="rate_1000" placeholder="">
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                        <td data-label="2000+">
                                            <div class="input-group">
                                                <input class="form-control rate_2000" type="text" name="rate_2000" placeholder="">
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr id="row2" data-id="2">
                                        <td data-label="">
                                            <h6>Negotiated Rate</h6>
                                        </td>
                                        <td data-label="45+">
                                            <div class="input-group">
                                                <input class="form-control nego_rate_45" type="text" name="nego_rate_45" placeholder="">
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                        <td data-label="100+">
                                            <div class="input-group">
                                                <input class="form-control nego_rate_100" type="text" name="nego_rate_100" placeholder="">
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                        <td data-label="250+">
                                            <div class="input-group">
                                                <input class="form-control nego_rate_250" type="text" name="nego_rate_250" placeholder="">
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                        <td data-label="500+">
                                            <div class="input-group">
                                                <input class="form-control nego_rate_500" type="text" name="nego_rate_500" placeholder="">
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                        <td data-label="1000+">
                                            <div class="input-group">
                                                <input class="form-control nego_rate_1000" type="text" name="nego_rate_1000" placeholder="">
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                        <td data-label="2000+">
                                            <div class="input-group">
                                                <input class="form-control nego_rate_2000" type="text" name="nego_rate_2000" placeholder="">
                                                <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit" name="signup">Create</button>
                        <a class="btn btn-danger" href="{{url("/$folder")}}">Cancel</a>                    
                    </div>
                </form>
            </div>            
        </div>
    </div>              
</div>         

        