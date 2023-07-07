<div class="fade-in">
        <div class="row">
            <div class="col-lg-6 col-md-12">
                <div class="card">
                    <div class="card-header">
                        <span class="breadcrumb-item "><a href="{{url("$folder")}}">Menu Mangement</a></span>
                        <span class="breadcrumb-item active">Create User Form</span>
                        <div class="card-header-actions"><small class="text-muted">docs</small></div>
                    </div>
                    <div class="card-body">                                 
                        <form id="menuForm" method="post" action="">
                            @csrf
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="signup" value="Create">Update</button>
                                <a class="btn btn-danger" href="{{url("$folder")}}">Cancel</a>
                            </div>
                            <hr>   
                            {{-- {{print_r($row)}} --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="row">
                                        <div class="form-group col-md-6">
                                            <label for="position">Position</label>
                                            <select class="form-control" name="position" id="position">
                                                <option value="" hidden>Please Select</option>
                                                <option value="main" @if($row->position=='main') selected @endif>Main</option>
                                                <option value="secondary" @if($row->position=='secondary') selected @endif>Secondary</option>
                                            </select>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="_id">Within the menu :</label>
                                                <select class="form-control" name="_id" id="_id" @if($row->position=='main') disabled @endif>
                                                    <option value="" hidden>Please Select</option>
                                                    @if($main)
                                                    @foreach($main as $i => $c)
                                                        <option value="{{$c->id}}" @if($row->_id==$c->id) selected @endif>{{$c->name}}</option>
                                                    @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-form-label" for="icon">Icon</label> :
                                    <small class="text-muted"><a href="https://fontawesome.com/icons?d=gallery">Fontawesome v5.0.3</a></small>
                                    <div class="input-group">
                                        <span class="input-group-prepend">
                                            <span class="input-group-text">
                                                <span id="icon-preview"><i class="{!!$row->icon!!}"></i></span>
                                            </span>
                                        </span>
                                        <input class="form-control" id="icon" name="icon" type="text" placeholder="icon" value="{!!$row->icon!!}" autocomplete="new-icon" @if($row->position=='secondary') disabled @endif>
                                    </div>                            
                                </div>
                            <div class="form-group">
                                <label class="col-form-label" for="username">Name</label>
                                <input class="form-control" id="name" type="text" name="name" placeholder="name" value="{{$row->name}}" autocomplete="new-name">
                            </div>
                            <div class="form-group">
                                <label class="col-form-label" for="url">URL</label>
                                <input class="form-control" id="url" type="text" name="url" placeholder="url" value='{{$row->url}}' autocomplete="new-url">
                            </div>
                            <div class="form-group"><strong class="col-form-label">Update : </strong>{{date('d-M-Y H:i:s',strtotime($row->created))}}</div>
                            <hr>
                            <div class="form-group">
                                <button class="btn btn-primary" type="submit" name="signup" value="Create">Update</button>
                                <a class="btn btn-danger" href="{{url("$folder")}}">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>            
            </div>
        </div>              
    </div>         
    
            