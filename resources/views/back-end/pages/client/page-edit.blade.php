<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="editForm" method="post" action="" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Vendor Management</a></span>
                        <span class="breadcrumb-item active">Edit Form</span>
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
                                    <div class="form-group col-lg-4">
                                        <h6>Name</h6>
                                    <input type="text" id="name" name="name" class="form-control" placeholder="Name/Company" value="{{ $row->name }}"/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Tax Number</h6>
                                    <input type="text" id="tax_number" name="tax_number" class="form-control" placeholder="Tax Number" value="{{ $row->tax_number }}"/>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="form-group col-lg-4">
                                        <h6>Email</h6>
                                    <input type="text" name="email" class="form-control" placeholder="exmaple@mail.com" value="{{ $row->email }}"/>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>Phone Number</h6>
                                    <input type="text" name="phone" class="form-control" placeholder="098-7654321" value="{{ $row->phone }}"/>
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
                        
                        <div class="header">
                            <div class="form-group">
                                <strong style="font-size:18px">Address :</strong>
                                {{-- <button type="button" class="btn btn-primary" id="add-address">เพิ่มที่อยู่</button> --}}
                            </div>
                        </div>
                        <hr>
                        <div class="row" id="address-content">
                            @php($provinces=BaseHelper::provinces())
									<div class="col-lg-12 col-md-6">
										<div class="form-group">
											<textarea name="address" id="address" type="text" rows="3" class="form-control" autocomplete="off" placeholder="ที่อยู่" required>{{$row->address}}</textarea>
										</div>
									</div>

								</div>

                        <div class="header">
                            <div class="form-group">
                                <strong style="font-size:18px">Bank information :</strong>
                                {{-- <button type="button" class="btn btn-primary" id="add-address">เพิ่มที่อยู่</button> --}}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="form-group col-lg-4">
                                <h6>Bank Name</h6>
                            <input type="text" name="bank_name" class="form-control" placeholder="ธนาคารกสิกร" value="{{ $row->bank_name }}"/>
                            </div>
                            <div class="form-group col-lg-4">
                                <h6>Account Name</h6>
                                <input type="text" name="bank_account" class="form-control" placeholder="Mr.Test Example" value="{{ $row->bank_account }}"/>
                            </div>
                            <div class="form-group col-lg-4">
                                <h6>Account Number</h6>
                                <input type="text" name="bank_number" class="form-control" placeholder="123-456-7890" value="{{ $row->bank_number }}"/>
                            </div>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit" name="signup">Update</button>
                        <a class="btn btn-danger" href="{{url("/$folder")}}">Cancel</a>                    
                    </div>
                </form>
            </div>            
        </div>
    </div>              
</div>         

        