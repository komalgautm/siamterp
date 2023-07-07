<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="createForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Clearance Management</a></span>
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
                                        @php($vendors = \App\VendorModel::where('status','on')->get())
                                        <select class="form-control select2" name="vendor" id="vendor" required>
                                            <option value="">Please select vendor</option>
                                            @foreach($vendors as $vendor)
                                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="row"> 
                                    <div class="form-group col-lg-3">
                                        <h6>Total Clearance Charge</h6>
                                        <div class="input-group">
                                            <input class="form-control price" type="text" name="charge" placeholder="Price" required>
                                            <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Certificates</h6>
                                        <div class="input-group">
                                            <input class="form-control price" type="text" name="certificate" placeholder="Price" required>
                                            <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Chamber of Commerce</h6>
                                        <div class="input-group">
                                            <input class="form-control price" type="text" name="chamber" placeholder="Price" required>
                                            <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Extras</h6>
                                        <div class="input-group">
                                            <input class="form-control price" type="text" name="extras" placeholder="Price" required>
                                            <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                        </div>
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

        