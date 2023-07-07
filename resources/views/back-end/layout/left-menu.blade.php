<div class="c-sidebar-brand">
    <img class="c-sidebar-brand-minimized"><i class=" fas fa-toolbox fa-lg">&nbsp;</i>
    <h5 class="c-sidebar-brand-ful" style="margin-bottom:0px;">Webpanel</h5>
</div>                
<ul class="c-sidebar-nav">
    {{-- <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{url("/dashboard")}}"><i class="c-sidebar-nav-icon fas fa-tachometer-alt fa-fw"></i>Dashboard<span class="badge badge-info">NEW</span></a></li> --}}
    <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{url("/dashboard")}}"><i class="c-sidebar-nav-icon fas fa-tachometer-alt fa-fw"></i>Dashboard</a></li>
    <li class="c-sidebar-nav-title">System</li>
     @php 

     $vl=Session::get('permission');

       
            $vl2=['Purchase order','Receiving','Sorting','Packing'];
            $vl2h=['HPL'];
            $vl4=['Vendor Management','Product SetUp','Purchase order','Receiving','Sorting','Packing','ASL','Inventory'];
        
       

     @endphp

    @php($menu = \App\MenuModel::where(['position'=>'main','status'=>'on'])->orderBy('sort')->get())
    @foreach($menu as $i => $m)

   
        
        @php($second = \App\MenuModel::where('_id',$m->id)->where('status','on')->get())  

     
        @if($vl=='2')
    
            @if(!in_array($m->name,$vl2))
            @continue
            @endif
            @elseif($vl=='4')
          @if(!in_array($m->name,$vl4))
            @continue
           @endif

           @endif
        
          


        <li class="c-sidebar-nav-item @if($second) c-sidebar-nav-dropdown @endif">
            <a class="c-sidebar-nav-link @if(count($second)>0) c-sidebar-nav-dropdown-toggle @endif" href="{{url($m->url)}}"><i class="c-sidebar-nav-icon {!!$m->icon!!}"></i> {{$m->name}}</a>
            @if(count($second)>0)
                <ul class="c-sidebar-nav-dropdown-items ">
                    @foreach($second as $i => $s)

                      @if($vl=='2')
                       @if(in_array($s->name,$vl2h))
                       @continue
                       @endif
                      @endif



                    <li><a class="c-sidebar-nav-link" href="{{url($s->url)}}"><span class="c-sidebar-nav-icon"></span> {{$s->name}}</a></li>
                    @endforeach
                </ul>
            @endif
        </li>        
    @endforeach
     @if($vl!='2')
    @if(@Auth::user()->role == 'superadmin' || @Auth::user()->role == 'admin')
    <li class="c-sidebar-nav-title">Administrator</li>
    <li class="c-sidebar-nav-item c-sidebar-nav-dropdown">
        <a class="c-sidebar-nav-link c-sidebar-nav-dropdown-toggle" ><i class="c-sidebar-nav-icon fas fa-sliders-h"></i> Setting</a>
        <ul class="c-sidebar-nav-dropdown-items">
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{url('/user')}}"><span class="c-sidebar-nav-icon"></span> User</a></li>
            @if(@Auth::user()->role == 'superadmin')
            <li class="c-sidebar-nav-item"><a class="c-sidebar-nav-link" href="{{url('/menu')}}"><span class="c-sidebar-nav-icon"></span> Menu</a></li>
            @endif
        </ul>
    </li>
    @endif
    @endif


    <li class="c-sidebar-nav-divider"></li>
</ul>
<button class="c-sidebar-minimizer c-class-toggler" type="button" data-target="_parent" data-class="c-sidebar-minimized"></button>