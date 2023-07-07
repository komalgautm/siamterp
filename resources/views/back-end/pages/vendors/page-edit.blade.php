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
                                        <h6>Type</h6>
                                        <select name="type" class="form-control" id="type">
                                            <option value="">กรุณาเลือก</option>
                                            <option value="person" @if($row->type == 'person') selected @endif>Person</option>
                                            <option value="company" @if($row->type == 'company') selected @endif>Company</option>
                                            <option value="shop" @if($row->type == 'shop') selected @endif>Shop</option>
                                            <option value="Goverment" @if($row->type == 'Goverment') selected @endif>Goverment</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-lg-4">
                                        <h6>ID Card</h6>
                                    <input type="text" id="id_card" name="id_card" class="form-control" placeholder="ID Number for individual" value="{{ $row->id_card }}" @if($row->type == 'person' || $row->type == 'company') required @endif/>
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
                                    <div class="form-group col-lg-4">
                                        <h6>Line ID</h6>
                                    <input type="text" name="line_id" class="form-control" placeholder="Line ID" value="{{ $row->line_id }}"/>
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
									<div class="col-lg-6 col-md-6">
										
										<div class="form-group">
											<select name="provinces" class="form-control provinces" id="provinces0" data-id="0" required>
												 <option value=""hidden>กรุณาเลือกจังหวัด</option>
												@foreach($provinces as $p)	
												<option value="{{$p->id}}" @if($row->provinces==$p->id) selected @endif>{{$p->name_th}}</option>
												@endforeach 
											</select>
										</div>							
									</div>
									<div class="col-lg-6 col-md-5">
										<div class="form-group">
											@php($district= \App\DistrictModel::where('_id',$row->provinces)->get())
											<select name="district" class="form-control district" id="district0" data-id="0" required>
												<option value="">กรุณาเลือกอำเถอ</option>
												 @foreach($district as $d)
												<option value="{{$d->id}}" @if($row->district==$d->id) selected @endif>{{$d->name_th}}</option>
												@endforeach 
											</select>
										</div>
									</div>
									<div class="col-lg-6 col-md-5">
									@php($subdistrict= \App\SubdistrictModel::where('_id',$row->district)->get())
										<select name="subdistrict" class="form-control subdistrict" id="subdistrict0" data-id="0" required>
											<option value="">กรุณาเลือกตำบล</option>
											 @foreach($subdistrict as $sd)
											<option value="{{$sd->id}}" @if($row->subdistrict==$sd->id) selected @endif>{{$sd->name_th}}</option>
											@endforeach 
										</select>
									</div>
									<div class="col-lg-6 col-md-5">
										<div class="form-group">
											<input name="postcode" id="postcode0" value="{{$row->postcode}}" type="text" class="form-control" placeholder="รหัสไปรษณีย์">
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

        