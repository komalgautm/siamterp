<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="editForm" method="post" action="" enctype="multipart/form-data">
                @csrf
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Pallets Management</a></span>
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
                                        <h6>Name</h6>
                                        <input type="text" id="name" name="name" class="form-control" placeholder="Name"  value="{{ $row->name_th }}" required/>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Price</h6>
                                        <div class="input-group">
                                            <input class="form-control" id="price" type="text" name="price" placeholder="Price"  value="{{ $row->cost }}" required>
                                            <div class="input-group-append"><!-- <span class="input-group-text">cm</span> --></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Weight</h6>
                                        <div class="input-group">
                                            <input class="form-control" id="weight" type="text" name="weight" placeholder="Weight"  value="{{ $row->weight }}" required>
                                            <div class="input-group-append"><!-- <span class="input-group-text">cm</span> --></div>
                                        </div>
                                    </div>
                                    <div class="form-group col-lg-3">
                                        <h6>Volume</h6>
                                        <div class="input-group">
                                            <input class="form-control" id="volume" type="text" name="volume" placeholder="Volume" value="{{ $row->volume }}" required>
                                            <div class="input-group-append"><!-- <span class="input-group-text">cm</span> --></div>
                                        </div>
                                    </div>
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

        