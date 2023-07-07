<div class="fade-in">
    <div class="row">
        <div class="col-lg-12 col-md-12">
            <form id="editForm" method="post" action="" enctype="multipart/form-data"> 
                @csrf
               
                <div class="card">
                    <div class="card-header">
                      
                        <span class="breadcrumb-item active">Edit Form</span>
                      
                    </div>
                    <div class="card-body">
                       @csrf
                        <div class="tab-content" id="myTabContent">
                            <br>
            
                        <div class="row">
                           
                       <form method="post" action="">
                             <div class="form-group col-lg-6">
                                <h6>Bank Code</h6>
                                <input type="text" class="form-control" id="bank_code" name="bank_code" value="{{$row->bank_code}}" >
                            </div>
                            <div class="form-group col-lg-6">
                                <h6>Bank Name</h6>
                                <input type="text" class="form-control" id="name" name="name" value="{{$row->name}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <h6>Bank Branch</h6>
                                <input type="text" class="form-control" id="branch" name="branch" value="{{$row->branch}}" >
                            </div>
                             <div class="form-group col-lg-6">
                                <h6>Bank Account</h6>
                                <input type="text" class="form-control" id="account" name="account" value="{{$row->account}}">
                            </div>
                            <div class="form-group col-lg-6">
                                <h6>Account Type</h6>
                                <input type="text" class="form-control" id="account_type" name="account_type" value="{{$row->account_type}}">
                            </div>
                       </form>

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

        