<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="createForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Boxes Management</a></span>
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
                                        <h6>Name</h6>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Name" value="" required/>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Width</h6>
                                        <div class="input-group">
                                            <input class="form-control" id="width" type="text" name="width" placeholder="width" value="" required>
                                            <div class="input-group-append"><span class="input-group-text">cm</span></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Length</h6>
                                        <div class="input-group">
                                            <input class="form-control" id="length" type="text" name="length" placeholder="length" value="" required>
                                            <div class="input-group-append"><span class="input-group-text">cm</span></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Height</h6>
                                        <div class="input-group">
                                            <input class="form-control" id="height" type="text" name="height" placeholder="height" value="" required>
                                            <div class="input-group-append"><span class="input-group-text">cm</span></div>
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
                            <div class="row">
                                <div class="form-group col-lg-3">
                                    <h6>CBM</h6>
                                    <input type="text" id="cbm" name="cbm" class="form-control" placeholder="automatic calculation" value="" readonly/>
                                </div>
                                <div class="form-group col-lg-3">
                                    <h6>Weight</h6>
                                    <div class="input-group">
                                        <input class="form-control" id="weight" type="text" name="weight" placeholder="weight" value="" required>
                                        <div class="input-group-append"><span class="input-group-text">g</span></div>
                                    </div>
                                </div>
                                <div class="form-group col-lg-3">
                                    <h6>MinLoad</h6>
                                    <input type="text" id="minload" name="minload" class="form-control" placeholder="automatic calculation" value="" readonly/>
                                </div>
                                <div class="form-group col-lg-3">
                                    <h6>Box/Pallet</h6>
                                    <input type="text" id="box_pallet" name="box_pallet" class="form-control" placeholder="box/pallet" value="" required/>
                                </div>
                            </div>
                            {{-- <div class="row">
                                <div class="form-group col-lg-3">
                                    <h6>Cost</h6>
                                    <div class="input-group">
                                        <input class="form-control cost" step="0.01" type="number" name="cost" placeholder="cost">
                                        <div class="input-group-append"><span class="input-group-text">THB</span></div>
                                    </div>
                                </div>
                            </div> --}}
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

        