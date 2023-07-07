<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">

    <title>{{Config::get('app.name')}} | Webpanel</title>

    <base href="{{url('/TERP/')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon.ico">
    <link rel="stylesheet" href="public/back-end/fontawesome-5.11.2/css/all.css">    
    <link href="public/back-end/bootstrap-4.3.1/css/bootstrap.css" rel="stylesheet">
    <link href="public/back-end/css/style.css" rel="stylesheet">
    <link href="public/back-end/vendors/pace-progress/css/pace.min.css" rel="stylesheet">
    
    <style>
        .form-control.error,
        span.select2 .error
        {
            border-color: #e55353;
            background-repeat: no-repeat;
            background-size: calc(0.75em + 0.375rem) calc(0.75em + 0.375rem);
        }
        label.error,
        select.error,
        span.error
        {
            font-size: 12px;
            color: #e55353;
            margin-bottom: 0 !important;
        }
        select>option{
            font-size: 15px;
        }
        .password-show::after{
            content: "";
            position: absolute;
            top: 10px;
            height: 16px;
            right: 20px;
            width: 2px;
            background: #768192;
            -ms-transform: rotate(45deg);
            -webkit-transform: rotate(45deg);
            transform: rotate(45deg);
        }
        
    </style>
</head>
<body class="c-app flex-row">    
    <script>var c=localStorage.getItem("theme"),tag=document.getElementsByTagName('body').item(0).classList;if(c.length>0){tag.add(c);}</script>
    <div class="c-sidebar c-sidebar-light c-sidebar-fixed c-sidebar-lg-show" id="sidebar">
        @include("$prefix.layout.left-menu")
    </div>
    <div class="c-wrapper">
        @include("$prefix.layout.header")
        <div class="c-body">
            @if(@$css)
                @foreach($css as $css)
                    <link href="{{$css}}" rel="stylesheet">
                @endforeach
            @endif
            <main class="c-main">
                <div class="container-fluid">
                    @include("$prefix.pages.$folder.page-$page")
                </div>
            </main>
        </div>
        <footer class="c-footer">
            <div><a href="https://coreui.io">CoreUI</a> © 2019 creativeLabs.</div>
            <div class="mfs-auto">Powered by&nbsp;<a href="https://coreui.io/pro/">CoreUI Pro</a></div>
        </footer>          
    </div>
        
    <script src="public/back-end/vendors/pace-progress/js/pace.min.js"></script>
    <script src="public/back-end/vendors/@coreui/js/coreui.bundle.min.js"></script>
    <script>
        var tooltipEl = document.getElementById('header-tooltip');
        var tootltip = new coreui.Tooltip(tooltipEl);
    </script>
    @if(@$js)
    @foreach($js as $key => $val)
    <script @foreach($js[$key] as $k => $v){{$k}}="{{$v}}" @endforeach ></script>
    @endforeach
    @endif
    <script src="public/back-end/build/build.js"></script>
<script src="public/back-end/new/form_double_submission.js"></script>
</body>
</html>