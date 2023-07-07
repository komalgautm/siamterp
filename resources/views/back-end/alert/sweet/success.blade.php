<!DOCTYPE html>
<html lang="en">
<head>
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
      <title>{{Config::get('app.name')}} | Webpanel</title>

      <link href="{{url('/back-end/css/style.css')}}" rel="stylesheet" />
      <link href="{{url('/back-end/css/sweetalert2.min.css')}}" rel="stylesheet" />
      <script src="{{url('/back-end/js/jquery.min.js')}}"></script>
      <script src="{{url('/back-end/js/bootstrap.min.js')}}"></script>
      <script src="{{url('/back-end/js/sweetalert2.all.min.js')}}"></script>

</head>
<body class="c-app flex-row">
      <script>var c=localStorage.getItem("theme"),tag=document.getElementsByTagName('body').item(0).classList;if(c.length>0){tag.add(c);}</script>
</body>

</html>

<script>
const url = '{{@$url}}';
$(function(){
      Swal.fire({
            title: "Good job!",
            text: "Successfully!",
            icon: "success",
            allowOutsideClick: false,
      }).then((result) => {
            if(url==''){
                  window.location=window.location.href;
            }else{
                  window.location=url
            }
      });
})
</script>