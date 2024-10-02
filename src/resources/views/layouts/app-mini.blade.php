<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- favicos -->
    @include('web::includes.favicon')

    <title>SeAT | @yield('title', 'Eve Online API Tool')</title>

    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="{{ asset('web/css/bootstrap.min.css') }}">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('web/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('web/css/seat.css') }}">
    <link rel="stylesheet" href="{{ asset('web/css/adminlte.min.css') }}">
    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro:ital,wght@0,300;0,400;0,700;1,400&display=swap" rel="stylesheet">

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
  <body class="hold-transition login-page">

    <div class="login-box">

      @include('web::includes.notifications')

      @yield('content')

    </div>

    <!-- jQuery -->
    <script src="{{ asset('web/js/jquery.min.js') }}"></script>
    <!-- Bootstrap -->
    <script src="{{ asset('web/js/bootstrap.bundle.min.js') }}"></script>
    <!-- Theme JS -->
    <script src="{{ asset('web/js/adminlte.min.js') }}"></script>

    <!-- view specific scripts -->
    @stack('javascript')

  </body>
</html>
