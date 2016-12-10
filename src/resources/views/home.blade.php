@extends('web::layouts.grids.12')

@section('title', trans('web::seat.home'))
@section('page_header', trans('web::seat.home'))
@section('page_description', trans('web::seat.dashboard'))

@section('full')

  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">

      <div class="info-box">
        <span class="info-box-icon bg-aqua"><i class="fa fa-server"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.online_layers') }}</span>
          <span class="info-box-number">
            {{ $server_status['onlinePlayers'] or trans('web::seat.unknown') }}
          </span>
          <span class="text-muted">
            {{ trans('web::seat.last_update') }}: {{ human_diff($server_status['created_at']) }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->

    </div><!-- /.col -->
    <div class="col-md-6 col-sm-6 col-xs-12">

      <div class="info-box">
        <span class="info-box-icon bg-green"><i class="fa fa-key"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.owned_api_keys') }}</span>
          <span class="info-box-number">
            {{ count(auth()->user()->keys) }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->

    </div><!-- /.col -->
  </div>

  <div class="row">
    <div class="col-md-6 col-sm-6 col-xs-12">

      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Concurrent Player Count</h3>
        </div>
        <div class="box-body">
          <div class="chart">

            <canvas id="serverstatus" style="height: 249px; width: 555px;" height="265" width="1110"></canvas>

          </div>
        </div>
        <!-- /.box-body -->
      </div>

    </div><!-- /.col -->
    <div class="col-md-6 col-sm-6 col-xs-12">

      <div class="info-box">
        <span class="info-box-icon bg-blue"><i class="fa fa-money"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.total_character_isk') }}</span>
          <span class="info-box-number">
            {{ number($total_character_isk)  }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->

      <div class="info-box">
        <span class="info-box-icon bg-black"><i class="fa fa-graduation-cap"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.total_character_skillpoints') }}</span>
          <span class="info-box-number">
            {{ number($total_character_skillpoints, 0)  }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->

    </div><!-- /.col -->
  </div>

  <div class="row">

    <!-- skills graphs -->
    <div class="col-md-6 col-sm-6 col-xs-12">

      @if (setting('main_character_id') != 1 && !is_null(setting('main_character_name')))

        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">
              {!! img('character', setting('main_character_id'), 64, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ trans('web::seat.main_char_skills', ['character_name' => setting('main_character_name')]) }}
            </h3>
          </div>
          <div class="box-body">

            <h4 class="box-title">{{ trans('web::seat.main_char_skills_per_level') }}</h4>
            <div class="chart">
              <canvas id="skills-level" height="230" width="1110"></canvas>
            </div>

            <h4 class="box-title">{{ trans('web::seat.main_char_skills_coverage') }}</h4>
            <div class="chart">
              <canvas id="skills-coverage" height="600" width="1110"></canvas>
            </div>
          </div>
        </div>

      @else

        <div class="box">
          <div class="box-body">

            Set your main character in your
            <a href="{{ route('profile.view') }}">{{ trans('web::seat.profile') }}</a>
            after adding an API key to view the skills graph!

          </div>
        </div>

      @endif

    </div><!-- /.col -->
    <div class="col-md-6 col-sm-6 col-xs-12">

      <div class="info-box">
        <span class="info-box-icon bg-red"><i class="fa fa-space-shuttle"></i></span>
        <div class="info-box-content">
          <span class="info-box-text">{{ trans('web::seat.total_killmails') }}</span>
          <span class="info-box-number">
            {{ $total_character_killmails }}
          </span>
        </div><!-- /.info-box-content -->
      </div><!-- /.info-box -->


      @if($newest_mail->count() > 0))

        <div class="box">
          <div class="box-header with-border">
            <h3 class="box-title">Newest EVEMail</h3>
          </div>
          <!-- /.box-header -->
          <div class="box-body">

            <table class="table compact table-condensed table-hover table-responsive">
              <thead>
              <tr>
                <th>From</th>
                <th>Title</th>
                <th></th>
              </tr>
              </thead>
              <tbody>

              @foreach($newest_mail as $message)

                <tr>
                  <td>
                    {!! img('auto', $message->senderID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                    {{ $message->senderName }}
                  </td>
                  <td>{{ $message->title }}</td>
                  <td>
                    <a href="{{ route('character.view.mail.timeline.read', ['message_id' => $message->messageID]) }}"
                       class="btn btn-primary btn-xs">
                      <i class="fa fa-envelope"></i>
                      {{ trans('web::seat.read') }}
                    </a>
                  </td>
                </tr>

              @endforeach

              </tbody>
            </table>

          </div>
          <!-- /.box-body -->
        </div>

      @endif

    </div><!-- /.col -->

  </div>

@stop

@push('javascript')
<script>

  $.get("{{ route('home.chart.serverstatus') }}", function (data) {

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
  })

  {{-- only request the graph data if there is a main character! --}}
  @if (setting('main_character_id') != 1 && !is_null(setting('main_character_name')))

  $.get("{{ route('character.view.skills.graph.level', ['character_id' => setting('main_character_id')]) }}", function (data) {
    new Chart($("canvas#skills-level"), {
      type: 'pie',
      data: data
    });
  });

  $.get("{{ route('character.view.skills.graph.coverage', ['character_id' => setting('main_character_id')]) }}", function (data) {
    new Chart($('canvas#skills-coverage'), {
      type   : 'radar',
      data   : data,
      options: {
        scale : {
          ticks: {
            beginAtZero: true,
            max        : 100
          }
        },
        legend: {
          display: false
        }
      }
    });
  });

  @endif

</script>
@endpush
