@extends('web::layouts.grids.12')

@section('title', trans('web::seat.home'))
@section('page_header', trans('web::seat.home'))
@section('page_description', trans('web::seat.dashboard'))

@section('full')

  <div class="row">
    <div class="col-md-4 col-sm-6">

      <!-- Online Badge -->
      <div class="info-box">
        <span class="info-box-icon bg-aqua elevation-1"><i class="fa fa-server"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.online_players') }}</span>
          <span class="info-box-number">
            {{ $server_status['players'] ?? trans('web::seat.unknown') }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->

    </div>

    <div class="col-md-4 col-sm-6">

      <!-- Characters Badge -->
      <div class="info-box">
        <span class="info-box-icon bg-green elevation-1"><i class="fa fa-key"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.linked_characters') }}</span>
          <span class="info-box-number">
            {{ count(auth()->user()->associatedCharacterIds()) }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->

    </div>
    
    <div class="col-md-4 col-sm-6">

      <!-- Wallet Badge -->
      <div class="info-box">
        <span class="info-box-icon bg-blue elevation-1"><i class="far fa-money-bill-alt"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.total_character_isk') }}</span>
          <span class="info-box-number">
            {{ number_format($total_character_isk)  }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->

    </div>

    <div class="col-md-4 col-sm-6">

      <!-- Ore Badge -->
      <div class="info-box">
        <span class="info-box-icon bg-purple elevation-1"><i class="far fa-gem"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.total_character_mined_isk') }} <small class="text-muted">({{ trans('web::seat.current_month') }})</small></span>
          <span class="info-box-number">
            {{ number_format($total_character_mining) }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->

    </div>

    <div class="col-md-4 col-sm-6">

      <!-- Ore Badge -->
      <div class="info-box">
        <span class="info-box-icon bg-yellow elevation-1"><i class="fas fa-coins"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.total_character_ratted_isk') }} <small class="text-muted">({{ trans('web::seat.current_month') }})</small></span>
          <span class="info-box-number">
            {{ number_format($total_character_ratting) }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->

    </div>
    
    <div class="col-md-4 col-sm-6">

      <!-- Skills Badge -->
      <div class="info-box">
        <span class="info-box-icon bg-black elevation-1"><i class="fa fa-graduation-cap"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.total_character_skillpoints') }}</span>
          <span class="info-box-number">
            {{ number_format($total_character_skillpoints, 0)  }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->

    </div>

    <div class="col-md-4 col-sm-6">

      <!-- Kills Badge -->
      <div class="info-box">
        <span class="info-box-icon bg-red elevation-1"><i class="fa fa-space-shuttle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.total_killmails') }} <small class="text-muted">({{ trans('web::seat.current_month') }})</small></span>
          <span class="info-box-number">
            {{ $total_character_killmails }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->

    </div>
  </div>

  <div class="row">
    
    <!-- player count -->
    <div class="col-xs-12 col-sm-6">

      <div class="card">
        <div class="card-header border-0">
          <div class="d-flex justify-content-between">
            <h3 class="card-title">{{ trans('web::seat.concurrent_player_count') }}</h3>
          </div>
        </div>
        <div class="card-body">
          <div class="position-relative mb-4">
            <canvas id="serverstatus" height="150" width="1110"></canvas>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>

    <!-- response times -->
    <div class="col-xs-12 col-sm-6">

      <div class="card">
        <div class="card-header border-0">
          <div class="d-flex justify-content-between">
            <h3 class="card-title">{{ trans('web::seat.esi_response_time') }}</h3>
          </div>
        </div>
        <div class="card-body">
          <div class="position-relative mb-4">
            <canvas id="serverresponse" height="150" width="1110"></canvas>
          </div>
        </div>
        <!-- /.box-body -->
      </div>
    </div>
    

    <!-- skills graphs -->
    @if(auth()->user()->name != 'admin')
      <div class="col-xs-12 col-sm-6">

        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title">
                {!! img('characters', 'portrait', auth()->user()->main_character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ trans('web::seat.main_char_skills', ['character_name' => auth()->user()->name]) }}
              </h3>
              <span class="text-bold text-lg">{{ trans('web::seat.main_char_skills_per_level') }}</span>
            </div>
          </div>
          <div class="card-body">
            <div class="position-relative mb-4">
              <canvas id="skills-level" height="600" width="1110"></canvas>
            </div>
          </div>
        </div>
      </div>

      <div class="col-xs-12 col-sm-6">

        <div class="card">
          <div class="card-header border-0">
            <div class="d-flex justify-content-between">
              <h3 class="card-title">
                {!! img('characters', 'portrait', auth()->user()->main_character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ trans('web::seat.main_char_skills', ['character_name' => auth()->user()->name]) }}
              </h3>
              <span class="text-bold text-lg">{{ trans('web::seat.main_char_skills_coverage') }}</span>
            </div>
          </div>
          <div class="card-body">
            <div class="position-relative mb-4">
              <canvas id="skills-coverage" height="600" width="1110"></canvas>
            </div>
          </div>
        </div>

      </div><!-- /.col -->
    @endif

  </div>

@stop

@push('javascript')
  <script type="text/javascript">

    // Player Count
    $.get("{{ route('seatcore::home.chart.serverstatus') }}", function (data) {

      new Chart($("canvas#serverstatus"), {
        type   : 'line',
        data   : data,
        options: {
          legend: {
            display: false
          },
          scales: {
            xAxes: [{
              display: false
            }]
          }
        }
      });
    });

    // Esi Response Times
    $.get("{{ route('seatcore::home.chart.serverresponse') }}", function (data) {

      new Chart($("canvas#serverresponse"), {
        type   : 'line',
        data   : data,
        options: {
          legend: {
            display: false
          },
          scales: {
            xAxes: [{
              display: false
            }]
          }
        }
      });
    });

    if ($('canvas#skills-level').length)
      $.get("{{ route('seatcore::character.view.skills.graph.level', ['character' => auth()->user()->main_character]) }}", function (data) {
        new Chart($("canvas#skills-level"), {
          type: 'pie',
          data: data
        });
      });

    if ($('canvas#skills-coverage').length)
      $.get("{{ route('seatcore::character.view.skills.graph.coverage', ['character' => auth()->user()->main_character]) }}", function (data) {
        new Chart($('canvas#skills-coverage'), {
          type   : 'radar',
          data   : data,
          options: {
            scale : {
              ticks: {
                beginAtZero: true,
                showLabelBackdrop: false,
                max        : 100
              }
            },
            legend: {
              display: false
            }
          }
        });
      });
  </script>

@endpush
