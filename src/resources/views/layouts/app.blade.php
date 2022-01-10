<!DOCTYPE html>
<html lang="{{ setting('language') ?: 'en' }}">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- favicons -->
    @include('web::includes.favicon')

    <title>SeAT | @yield('title', 'Eve Online API Tool')</title>

    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('web/css/all.min.css') }}">
    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('web/css/select2.min.css') }}">
    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('web/css/dataTables.bootstrap5.min.css') }}">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('web/css/tabler.min.css') }}">
    <!-- SeAT CSS -->
    <link rel="stylesheet" href="{{ asset('web/css/seat.css') }}">
    <!-- SeAT Skins CSS -->
    @if(setting('skin') && file_exists(public_path('web/css/skins/' . setting('skin') . '.css')))
      <link rel="stylesheet" href="{{ asset('web/css/skins/' . setting('skin') . '.css') }}" />
    @endif
    <!-- Custom layout CSS -->
    @if(file_exists(public_path('custom-layout.css')))
      <link rel="stylesheet" href="{{ asset('custom-layout.css') }}"/>
    @endif

    <!-- view specific head content -->
    @stack('head')
  </head>
  <body class="theme-light">

    <div class="page">

      <!-- Left side column. contains the logo and sidebar -->
      @include('web::includes.sidebar')

      <!-- Main Header -->
      @include('web::includes.header')

      <!-- Content Wrapper. Contains page content -->
      <div class="page-wrapper">

        <!-- Content Header (Page header) -->
        <div class="container-xl">
          <!-- Page title -->
          <div class="page-header d-print-none">
            <div class="row align-items-center">
              <div class="col">
                <!-- Page section -->
                <div class="page-pretitle">@yield('page_header')</div>
                <!-- Page name -->
                <div class="page-title">@yield('page_description')</div>
              </div>
            </div>
          </div>
        </div>

        <!-- Main content -->
        <div class="page-body">
          <div class="container-xl">

            <!-- Disclaimer Configuration Alerts -->
            @include('web::includes.security-alert')

            <!-- Notifications -->
            @include('web::includes.notifications')

            <!-- Page Content Here -->
            @yield('content')

          </div>
        </div>
        <!-- /.content -->

      </div>
      <!-- /.content-wrapper -->

      <!-- Main Footer -->
      @include('web::includes.footer')

      <!-- Right Sidebar Content -->
      {{-- @include('web::includes.right_sidebar') --}}
    </div>
    <!-- ./wrapper -->

    <!-- jQuery -->
    <script src="{{ asset('web/js/jquery.min.js') }}"></script>
    <!-- Select2 -->
    <script src="{{ asset('web/js/select2.full.min.js') }}"></script>
    <!-- Bootbox -->
    <script src="{{ asset('web/js/bootbox.min.js') }}"></script>
    <!-- jQuery Unveil -->
    <script src="{{ asset('web/js/jquery.unveil.js') }}"></script>
    <!-- DataTables -->
    <script src="{{ asset('web/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('web/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('web/js/dataTables.rowGroup.min.js') }}"></script>
    <script src="{{ asset('web/js/rowGroup.bootstrap5.min.js') }}"></script>
    <!-- MomentJS -->
    <script src="{{ asset('web/js/moment-with-locales.min.js') }}"></script>
    <!-- ChartJS -->
    <script src="{{ asset('web/js/chart.min.js') }}"></script>
    <!-- Theme JS -->
    <script src="{{ asset('web/js/tabler.min.js') }}"></script>
    <!-- SeAT JS -->
    <script src="{{ asset('web/js/seat.js') }}"></script>

    {{-- This script is here as we need Laravel to generate the route --}}
    @can('global.queue_manager')
      <script>
        // Periodic Queue Status Updates
        (function worker() {
          $.ajax({
            type    : "get",
            url     : "{{ route('seatcore::queue.status.short') }}",
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
    @endcan

    <!-- view specific scripts -->
    @stack('javascript')
  </body>
</html>
