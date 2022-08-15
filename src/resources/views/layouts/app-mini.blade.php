<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- favicons -->
    @include('web::includes.favicon')

    <title>SeAT | @yield('title', 'Eve Online API Tool')</title>


    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('web/css/all.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('web/css/tabler.min.css') }}">
    <!-- SeAT style -->
    <link rel="stylesheet" href="{{ asset('web/css/seat.css') }}">

    <!-- Custom layout CSS -->
    @if(file_exists(public_path('custom-layout-mini.css')))

      <link rel="stylesheet" href="{{ asset('custom-layout-mini.css') }}"/>

    @endif

    <!-- view specific head content -->
    @stack('head')
  </head>
  <body class="border-top-wide border-primary d-flex flex-column theme-dark">

    <div class="page page-center">

      <div class="container-tight py-4">

        @include('web::includes.notifications')

        @yield('content')

      </div>

    </div>

    <!-- Theme JS -->
    <script src="{{ asset('web/js/tabler.min.js') }}"></script>

    <!-- view specific scripts -->
    @stack('javascript')

  </body>
</html>
