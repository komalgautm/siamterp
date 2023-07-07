<div class="fade-in">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <form id="signupForm" method="post" action=""> 
                    <div class="card">
                        <div class="card-header">
                            <span class="breadcrumb-item "><a href="{{url("/$folder")}}">User Mangement</a></span>
                            <span class="breadcrumb-item active">Edit User Form</span>
                            <div class="card-header-actions"><small class="text-muted">docs</small></div>
                        </div>
                        <div class="card-body">
                            @csrf
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="role">Role</label>
                                            <select class="form-control" name="role" id="role">
                                                <option value="" hidden>Please Select</option>
                                                {{-- <option value="user" @if($row->role=='user') selected @endif>User</option> --}}
                                                <option value="staff" @if($row->role=='staff') selected @endif>Staff</option>
                                                <option value="admin" @if($row->role=='admin') selected @endif>Admin</option>
                                            </select>
                                        </div>
                                        <div class="form-group col-md-6">
                                            <label for="status">Status</label>
                                            <select class="form-control" name="status" id="status">
                                                <option value="" hidden>Please Select</option>
                                                <option value="pending" @if($row->status=='pending') selected @endif>Pending</option>
                                                <option value="inactive" @if($row->status=='inactive') selected @endif>Inactive</option>
                                                <option value="active" @if($row->status=='active') selected @endif>Active</option>
                                                <option value="banned" @if($row->status=='banned') selected @endif>Banned</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>   
                            <div class="form-group">
                            <label class="col-form-label" for="Permission">Permission</label>
                            <select class="form-control" name="permission" id="permission">
                                        
                                            <option value="2"  @if($row->permission=='2') selected @endif >Level 2</option>
                                            <option value="4"  @if($row->permission=='4') selected @endif >Level 4</option>
                                           
                                        </select>
                        </div>                         
                            <div class="form-group">
                                <label class="col-form-label" for="username">Name</label>
                                <input class="form-control" id="name" type="text" name="name" placeholder="name" autocomplete="new-name" value="{{$row->name}}">
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
    
            