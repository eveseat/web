<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

  <!-- favicos -->
  @include('web::includes.favico')

  <title>SeAT | @yield('title', 'Eve Online API Tool')</title>

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="{{ asset('web/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('web/css/font-awesome.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('web/css/adminlte.min.css') }}">
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- Custom layout CSS -->
  @if(file_exists(public_path('custom-layout-mini.css')))

    <link rel="stylesheet" href="{{ asset('custom-layout-mini.css') }}"/>

@endif

<!-- view specific head content -->
@stack('head')

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body>

<div class="login-box">

  @include('web::includes.notifications')

  @yield('content')

</div>
<!-- Le-JavaScript -->

<!-- jQuery -->
<script src="{{ asset('web/js/jquery.min.js') }}"></script>
<!-- Bootstrap 3.3.5 -->
<script src="{{ asset('web/js/bootstrap.min.js') }}"></script>

<!-- view specific scripts -->
@stack('javascript')

</body>
</html>
