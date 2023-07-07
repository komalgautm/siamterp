<div class="fade-in">
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <span class="breadcrumb-item "><a href="{{url("$segment/$folder")}}">User Mangement</a></span>
                    <span class="breadcrumb-item active">Create User Form</span>
                    <div class="card-header-actions"><small class="text-muted">docs</small></div>
                </div>
                <div class="card-body">                                 
                    <form id="signupForm" method="post" action="">                        
                        @method('PUT')
                        @csrf
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit" name="signup" value="Create">Create</button>
                            <a class="btn btn-danger" href="{{url("/$folder")}}">Cancel</a>
                        </div>
                        <hr>   
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="role">Role</label>
                                        <select class="form-control" name="role" id="role">
                                            <option value="" hidden>Please Select</option>
                                            {{-- <option value="user">User</option> --}}
                                            <option value="staff">Staff</option>
                                            <option value="admin">Admin</option>
                                        </select>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label for="status">Status</label>
                                        <select class="form-control" name="status" id="status">
                                            <option value="" hidden>Please Select</option>
                                            <option value="pending">Pending</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="active">Active</option>
                                            <option value="banned">Banned</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                         <div class="form-group">
                            <label class="col-form-label" for="Permission">Permission</label>
                            <select class="form-control" name="permission" id="permission">
                                        
                                            <option value="2">Level 2</option>
                                            <option value="4">Level 4</option>
                                           
                                        </select>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="username">Name</label>
                            <input class="form-control" id="name" type="text" name="name" placeholder="name" autocomplete="new-name">
                        </div> 

                       
                        <div class="form-group">
                            <label class="col-form-label" for="username">Username</label>
                            <input class="form-control" id="username" type="text" name="username" placeholder="username" autocomplete="new-username">
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label class="col-form-label" for="password">Password</label>
                                        <div class="input-group col-mb-6">
                                            <input type="password" id="password" class="form-control" name="password" placeholder="Password" autocomplete="off">
                                            <div class="input-group-append">                                            
                                                <div class="input-group-text">
                                                    <span class="card-link"><i class="far fa-eye" data-id="password"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group col-md-6">
                                        <label class="col-form-label" for="confirm_password">Confirm password</label>
                                        <div class="input-group col-mb-6">
                                            <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="Confirm password" autocomplete="off">
                                            <div class="input-group-append">                                            
                                                <div class="input-group-text">
                                                    <span class="card-link"><i class="far fa-eye" data-id="confirm_password"></i></span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        <div class="form-group">
                            <button class="btn btn-primary" type="submit" name="signup" value="Create">Create</button>
                            <a class="btn btn-danger" href="{{url("/$folder")}}">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>            
        </div>
    </div>              
</div>         

        