<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta name="description" content="CoreUI - Open Source Bootstrap Admin Template">
    <meta name="author" content="Łukasz Holeczek">
    <meta name="keyword" content="Bootstrap,Admin,Template,Open,Source,jQuery,CSS,HTML,RWD,Dashboard">

    <title>{{Config::get('app.name')}} | Siam Eats</title>

    <base href="{{url('/')}}">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon.ico">
    <link rel="stylesheet" href="{{ env('IMAGE_URL') }}/back-end/fontawesome-5.11.2/css/all.css">
    <meta name="msapplication-TileColor" content="#ffffff">
    <meta name="theme-color" content="#ffffff">
    <link href="{{ env('IMAGE_URL') }}/back-end/css/style.css" rel="stylesheet">
    <link href="{{ env('IMAGE_URL') }}/back-end/vendors/pace-progress/css/pace.min.css" rel="stylesheet">

  </head>
  <body class="c-app flex-row align-items-center">
    <div class="container">
      <div class="row justify-content-center">
        <div class="col-md-5">
          <div class="card-group">
            <div class="card p-4">
              <div class="card-body">
              <form action="<?php echo url('login'); ?>" method="post">
                  @csrf
                  <h1>Login</h1>
                  <p class="text-muted">Sign In to your account</p>
                  @if(Session('error'))
                    <div class="alert alert-danger">
                      {{Session('error')}}
                    </div>
                  @endif
                  <div class="input-group mb-3">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-user"></i></span></div>
                      <input class="form-control" type="text" name="username" placeholder="Username">
                  </div>
                  <div class="input-group mb-4">
                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-lock"></i></span></div>
                    <input class="form-control" type="password" name="password" placeholder="Password">
                  </div>
                  <div class="form-group">
                    <div class="checkbox">
                      <label>
                        <input id="remember" type="checkbox" name="remember" value="on" class="is-invalid" aria-describedby="agree-error"> Remember Me
                      </label>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-6">
                      <button class="btn btn-primary px-4" type="submit">Login</button>
                    </div>
                    {{-- <div class="col-6 text-right">
                      <button class="btn btn-link px-0" type="reset">Forgot password?</button>
                    </div> --}}
                  </div>
                </form>
              </div>
            </div>
            {{--
            <div class="card text-white bg-primary py-5 d-md-down-none" style="width:44%">
              <div class="card-body text-center">
                <div>
                  <h2>Sign up</h2>
                  <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.</p>
                  <button class="btn btn-lg btn-outline-light mt-3" type="button">Register Now!</button>
                </div>
              </div>
            </div>
            --}}
          </div>
        </div>
      </div>
    </div>
    <!-- CoreUI and necessary plugins-->
    <script src="{{ env('IMAGE_URL') }}/back-end/vendors/pace-progress/js/pace.min.js"></script>
    <script src="{{ env('IMAGE_URL') }}/back-end/vendors/@coreui/js/coreui.bundle.min.js"></script>
  </body>
</html>