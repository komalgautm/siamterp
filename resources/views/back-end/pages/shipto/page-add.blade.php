<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="createForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Ship To Management</a></span>
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
                                    <div class="form-group col-lg-10">
                                        <h6>Code</h6>
                                        <input type="text" id="code" name="code" class="form-control" style="width:20%" placeholder="" value=""/>
                                    </div>

                                    <div class="form-group col-lg-4">
                                        <h6>Client</h6>
                                        @php($clients = \App\ClientModel::where('status','on')->get())
                                        <select class="form-control select2" name="client" id="client" required>
                                            <option value="">Please select client</option>
                                            @foreach($clients as $client)
                                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Name</h6>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Name/Company" value=""/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Tax Number</h6>
                                        <input type="text" id="tax_number" name="tax_number" class="form-control" placeholder="Tax Number" value="">
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="form-group col-lg-4">
                                        <h6>Email</h6>
                                        <input type="text" name="email" class="form-control" placeholder="exmaple@mail.com" value=""/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Phone Number</h6>
                                        <input type="text" name="phone" class="form-control" placeholder="098-7654321" value=""/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Airport</h6>
                                        @php($airports = \App\AirportModel::where('status','on')->get())
                                        <select class="form-control select2" name="airport" id="airport" required>
                                            <option value="">Please select airport</option>
                                            @foreach($airports as $airport)
                                                <option value="{{ $airport->id }}">{{ $airport->country.' - '.$airport->city.' ['.$airport->airport_code.']' }}</option>
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
                        
                        
                        <div class="row" id="address-content">
                            <div class="col-lg-12 col-md-6">
                                <div class="form-group">
                                    <textarea name="address" id="address" type="text" rows="3" autocomplete="off" class="form-control" placeholder="Address" required></textarea>
                                </div>
                            </div>
                            {{-- <div class="col-lg-6 col-md-6">
                                
                                <div class="form-group">
                                    <select name="provinces" class="form-control provinces" id="provinces0" data-id="0" required>
                                        <option value=""hidden>กรุณาเลือกจังหวัด</option>
                                        @foreach($provinces as $p)	
                                        <option value="{{$p->id}}">{{$p->name_th}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                
                            </div>
                            <div class="col-lg-6 col-md-5">
                                <div class="form-group">
                                    <select name="district" class="form-control district" id="district0" data-id="0" required>
                                        <option value="">กรุณาเลือกอำเถอ</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-5">
                                <select name="subdistrict" class="form-control subdistrict" id="subdistrict0" data-id="0" required>
                                    <option value="">กรุณาเลือกตำบล</option>
                                </select>
                            </div>
                            <div class="col-lg-6 col-md-5">
                                <div class="form-group">
                                    <input name="postcode" id="postcode0"  type="text" class="form-control" placeholder="รหัสไปรษณีย์">
                                </div>
                            </div> --}}
                           
                        </div>

                        <div class="header">
                            <div class="form-group">
                                <strong style="font-size:18px">Notify :</strong>
                                {{-- <button type="button" class="btn btn-primary" id="add-address">เพิ่มที่อยู่</button> --}}
                            </div>
                        </div>
                        <hr>
                        <div class="row"> 
                            <div class="form-group col-lg-4">
                                <h6>Name</h6>
                                <input type="text" id="notify_name" name="notify_name" class="form-control" placeholder="Name/Company" value=""/>
                            </div>
                            <div class="form-group col-lg-4">
                                <h6>Tax Number</h6>
                                <input type="text" id="notify_tax_number" name="notify_tax_number" class="form-control" placeholder="Tax Number" value="">
                            </div>
                        </div>
                        <div class="row"> 
                            <div class="form-group col-lg-4">
                                <h6>Email</h6>
                                <input type="text" name="notify_email" class="form-control" placeholder="exmaple@mail.com" value=""/>
                            </div>
                            <div class="form-group col-lg-4">
                                <h6>Phone Number</h6>
                                <input type="text" name="notify_phone" class="form-control" placeholder="098-7654321" value=""/>
                            </div>
                        </div>
                        <div class="row" id="address-content">
                            <div class="col-lg-12 col-md-6">
                                <div class="form-group">
                                    <textarea name="notify_address" id="notify_address" type="text" rows="3" autocomplete="off" class="form-control" placeholder="Address"></textarea>
                                </div>
                            </div>
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

        