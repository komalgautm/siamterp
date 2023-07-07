<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="createForm" method="post" action="{{url('/hourlyrate/store')}}" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("/$folder")}}">Hourly Rate Management</a></span>
                      
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
                                    <div class="form-group col-lg-6">
                                        <h6>Wages Hourly Rate</h6>
                                        <input type="number" id="wages" value="{{$v}}" name="wages" class="form-control" placeholder="Wages Hourly Rate" value=""/>
                                    </div>
									<div class="form-group col-lg-6">
                                        <div style="padding-top:30px;">
											<h6>THB / Hour</h6>
										</div>
                                        
                                    </div>
                                  
                                </div>   
                            </div>
                           
                        </div>

                    </div>
                    <div class="card-footer">
                        <button class="btn btn-primary" type="submit" name="signup">Update</button>
                                       
                    </div>
                </form>
            </div>            
        </div>
    </div>              
</div>         

        