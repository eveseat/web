<div class="row">
    <div class="col-md-4 col-sm-6">

        <!-- Online Badge -->
        <div class="info-box">
            <span class="info-box-icon bg-aqua elevation-1"><i class="fa fa-server"></i></span>
            <div class="info-box-content">
                <span class="info-box-text">{{ trans('web::seat.online_layers') }}</span>
                <span class="info-box-number">
            {{ $server_status['players'] ?? trans('web::seat.unknown') }}
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
</div>

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
    </script>
@endpush
