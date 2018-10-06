<!DOCTYPE html>
<html lang="{{ setting('language') ?: 'en' }}">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <!-- favicos -->
  @include('web::includes.favico')

  <title>SeAT | @yield('title', 'Eve Online API Tool')</title>

  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="{{ asset('web/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('web/css/font-awesome.min.css') }}">
  <!-- Select2 -->
  <link rel="stylesheet" href="{{ asset('web/css/select2.min.css') }}">
  <!-- DataTables -->
  <link rel="stylesheet" href="{{ asset('web/css/datatables.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('web/css/adminlte.min.css') }}">
  <!-- Skin -->
  <link rel="stylesheet" href="{{ asset('web/css/skins/' . setting('skin') . '.min.css') }}">
  <!-- Google Font -->
  <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
  <!-- SeAT CSS -->
  <link rel="stylesheet" href="{{ asset('web/css/seat.css') }}">
  <!-- Custom layout CSS -->
  @if(file_exists(public_path('custom-layout.css')))

    <link rel="stylesheet" href="{{ asset('custom-layout.css') }}"/>

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
<body class="hold-transition {{ setting('skin') }} sidebar-mini {{ setting('sidebar') }}">

<div class="wrapper">

  <!-- Main Header -->
@include('web::includes.header')

<!-- Left side column. contains the logo and sidebar -->
@include('web::includes.sidebar')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        @yield('page_header')
        <small>@yield('page_description')</small>
      </h1>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Notifications -->
    @include('web::includes.notifications')

    <!-- Page Content Here -->
      @yield('content')

    </section>
    <!-- /.content -->

  </div>
  <!-- /.content-wrapper -->

  <!-- Main Footer -->
  @include('web::includes.footer')

  <!-- Right Sidebar Content -->
  @include('web::includes.right_sidebar')
</div>
<!-- ./wrapper -->

<!-- Le-JavaScript -->

<!-- jQuery -->
<script src="{{ asset('web/js/jquery.min.js') }}"></script>
<!-- Bootstrap -->
<script src="{{ asset('web/js/bootstrap.min.js') }}"></script>
<!-- Select2 -->
<script src="{{ asset('web/js/select2.full.min.js') }}"></script>
<!-- Bootbox -->
<script src="{{ asset('web/js/bootbox.min.js') }}"></script>
<!-- jQuery Unveil -->
<script src="{{ asset('web/js/jquery.unveil.js') }}"></script>
<!-- DataTables -->
<script src="{{ asset('web/js/datatables.min.js') }}"></script>
<!-- MomentJS -->
<script src="{{ asset('web/js/moment-with-locales.min.js') }}"></script>
<!-- ChartJS -->
<script src="{{ asset('web/js/chart.min.js') }}"></script>
<!-- Theme JS -->
<script src="{{ asset('web/js/app.min.js') }}"></script>
<!-- SeAT JS -->
<script src="{{ asset('web/js/seat.js') }}"></script>

{{-- This script is here as we need Laravel to generate the route --}}
<script>
  // Periodic Queue Status Updates
  (function worker() {
    $.ajax({
      type    : "get",
      url     : "{{ route('queue.status.short') }}",
      success : function (data) {
        $("span#queue_count").text(data.queue_count);
        $("span#error_count").text(data.error_count);
      },
      complete: function () {
        // Schedule the next request when the current one's complete
        setTimeout(worker, {{ config('web.config.queue_status_update_time') }});
      }
    });
  })();
</script>

<!-- view specific scripts -->
@stack('javascript')

</body>
</html>
