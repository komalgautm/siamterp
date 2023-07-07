<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="editForm" method="post" action="" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Packaging Management</a></span>
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
                                    <div class="form-group col-lg-3">
                                        <h6>Pack</h6>
                                        <input type="text" id="pack" name="pack" class="form-control" placeholder="pack" value="{{ $row->name_th }}" required/>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Weight</h6>
                                        <div class="input-group">
                                            <input class="form-control" id="weight" type="text" name="weight" placeholder="weight" value="{{ $row->weight }}" required>
                                            <div class="input-group-append"><span class="input-group-text">g</span></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Wrap Cost</h6>
                                        <div class="input-group">
                                            <input class="form-control wrap_cost" step="0.01" type="number" name="wrap_cost" placeholder="wrap cost" value="{{ $row->wrap_cost }}" required>
                                            <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Wrap Weight</h6>
                                        <div class="input-group">
                                            <input class="form-control" id="wrap_weight" type="text" name="wrap_weight" placeholder="wrap weight" value="{{ $row->wrap_weight }}" required>
                                            <div class="input-group-append"><span class="input-group-text">g</span></div>
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
                            {{-- <div class="row"> 
                                <div class="form-group col-lg-3">
                                    <h6>Unit Cost</h6>
                                    <div class="input-group">
                                        <input class="form-control unit_cost" step="0.01" type="number" name="unit_cost" placeholder="unit cost">
                                        <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                    </div>
                                </div>
                            </div> --}}
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

        