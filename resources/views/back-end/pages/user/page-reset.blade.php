<div class="fade-in">
    <div class="row">
        <div class="col-lg-6 col-md-12">
            <div class="card">
                <div class="card-header">
                    <span class="breadcrumb-item "><a href="{{url("/$folder")}}">User Mangement</a></span>
                    <span class="breadcrumb-item active">Reset User Form</span>
                    <div class="card-header-actions"><small class="text-muted">docs</small></div>
                </div>
                <div class="card-body">                                 
                    <form id="resetForm" method="post" action="">
                        @csrf

                        <div class="form-group">
                            <label class="col-form-label" for="old_username">Username</label>
                            <input class="form-control" id="old_username" type="text" name="old_username" placeholder="Username" autocomplete="old-username" value="{{$row->email}}" readonly>
                        </div>
                        <div class="form-group">
                            <label class="col-form-label" for="new_username">
                                <input id="new_username" type="checkbox" name="new_username"> New Username
                            </label>
                            <input class="form-control" id="username" type="text" name="username" placeholder="Username" autocomplete="new-username" disabled>
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
                            <button class="btn btn-primary" type="submit" name="Reset">Reset</button>
                            <a class="btn btn-danger" href="{{url("/$folder")}}">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>            
        </div>
    </div>              
</div>         

        