@extends('web::layouts.grids.12')

@section('title', trans('web::seat.home'))
@section('page_header', trans('web::seat.home'))
@section('page_description', trans('web::seat.dashboard'))

@section('full')

  <div class="row row-deck row-cards">

    @can('global.superuser')
      <!-- Player Count Chart -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title">{{ trans('web::seat.concurrent_player_count') }}</h3>
            <div id="player-count-chart" class="chart-lg" data-url="{{ route('seatcore::home.chart.serverstatus') }}"></div>
          </div>
        </div>
      </div>

      <!-- ESI Response Time Chart -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title">{{ trans('web::seat.esi_response_time') }}</h3>
            <div id="esi-response-chart" class="chart-lg" data-url="{{ route('seatcore::home.chart.serverresponse') }}"></div>
          </div>
        </div>
      </div>
    @endcan

    <!-- System Badges -->
    <div class="col-12">

      <div class="row row-cards">

        <!-- Online Badge -->
        <div class="col-sm-6 col-lg-3">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="bg-aqua avatar">
                    <i class="fa fa-server"></i>
                  </span>
                </div>
                <div class="col">
                  <div class="font-weight-medium">{{ $server_status['players'] ?? trans('web::seat.unknown') }} {{ trans('web::seat.online_players') }}</div>
                  <div class="text-muted">Currently</div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

    <!-- Character Badges -->

    <div class="col-12">

      <div class="row row-cards">

        <!-- Characters Badge -->
        <div class="col-sm-6 col-lg-3">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="bg-green text-white avatar">
                    <i class="fa fa-key"></i>
                  </span>
                </div>
                <div class="col">
                  <div class="font-weight-medium">{{ count(auth()->user()->associatedCharacterIds()) }} {{ trans('web::seat.linked_characters') }}</div>
                  <div class="text-muted">Currently</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Skills Badge -->
        <div class="col-sm-6 col-lg-3">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="bg-black text-white avatar">
                    <i class="fa fa-graduation-cap"></i>
                  </span>
                </div>
                <div class="col">
                  <div class="font-weight-medium">{{ number_format($total_character_skillpoints, 0)  }} {{ trans('web::seat.total_character_skillpoints') }}</div>
                  <div class="text-muted">Last Hour</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Kills Badge -->
        <div class="col-sm-6 col-lg-3">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="bg-red text-white avatar">
                    <i class="fa fa-space-shuttle"></i>
                  </span>
                </div>
                <div class="col">
                  <div class="font-weight-medium">{{ $total_character_killmails }} {{ trans('web::seat.total_killmails') }}</div>
                  <div class="text-muted">{{ trans('web::seat.current_month') }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

    <div class="col-12">

      <div class="row row-cards">

        <!-- Wallet Badge -->
        <div class="col-sm-6 col-lg-3">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="bg-blue text-white avatar">
                    <i class="far fa-money-bill-alt"></i>
                  </span>
                </div>
                <div class="col">
                  <div class="font-weight-medium">{{ number_format($total_character_isk) }} {{ trans('web::seat.total_character_isk') }}</div>
                  <div class="text-muted">Last Hour</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Ore Badge -->
        <div class="col-sm-6 col-lg-3">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="bg-blue text-white avatar">
                    <i class="far fa-gem"></i>
                  </span>
                </div>
                <div class="col">
                  <div class="font-weight-medium">{{ number_format($total_character_mining) }} {{ trans('web::seat.total_character_mined_isk') }}</div>
                  <div class="text-muted">{{ trans('web::seat.current_month') }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Ratting Badge -->
        <div class="col-sm-6 col-lg-3">
          <div class="card card-sm">
            <div class="card-body">
              <div class="row align-items-center">
                <div class="col-auto">
                  <span class="bg-yellow text-white avatar">
                    <i class="fas fa-coins"></i>
                  </span>
                </div>
                <div class="col">
                  <div class="font-weight-medium">{{ number_format($total_character_ratting) }} {{ trans('web::seat.total_character_ratted_isk') }}</div>
                  <div class="text-muted">{{ trans('web::seat.current_month') }}</div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

    @if(auth()->user()->name != 'admin')

      <!-- Main Character Skills Graph -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title">{{ trans('web::seat.main_char_skills', ['character_name' => auth()->user()->name]) }}</h3>
            <div id="skills-coverage-chart" class="chart-lg" data-url="{{ route('seatcore::character.view.skills.graph.level', ['character' => auth()->user()->main_character]) }}"></div>
          </div>
        </div>
      </div>

      <!-- Main Character Profile Graph -->
      <div class="col-lg-6">
        <div class="card">
          <div class="card-body">
            <h3 class="card-title">{{ trans('web::seat.main_char_skills', ['character_name' => auth()->user()->name]) }}</h3>
            <div id="profile-coverage-chart" class="chart-lg" data-url="{{ route('seatcore::character.view.skills.graph.profile', ['character' => auth()->user()->main_character]) }}"></div>
          </div>
        </div>
      </div>

    @endif

  </div>

@stop

@push('javascript')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script type="text/javascript">
    // player count chart
    let playerCountNode = document.querySelector('#player-count-chart');
    let playerCountConfig = {
      chart: {
        height: 240,
        toolbar: {
          show: false
        },
        type: 'line',
        zoom: {
          enabled: false
        },
        animations: {
          enabled: true,
          easing: 'linear',
          dynamicAnimation: {
            speed: 1000
          }
        }
      },
      stroke: {
        curve: 'smooth',
        width: 2
      },
      colors: ["#206bc4"],
      markers: {
        size: 6,
        strokeWidth: 0,
        hover: {
          size: 9
        }
      },
      grid: {
        padding: {
          top: -20,
          right: 0,
          bottom: -4,
          left: -4
        },
        strokeDashArray: 4,
        xaxis: {
          lines: {
            show: true
          }
        }
      },
      xaxis: {
        labels: {
          padding: 0
        },
        tooltip: {
          enabled: false
        },
        axisBorder: {
          show: false,
        },
        type: 'datetime'
      },
      yaxis: {
        labels: {
          padding: 4,
          formatter: (value) => { return new Intl.NumberFormat().format(value); },
        }
      },
      legend: {
        show: false,
      },
    };

    if (playerCountNode) {
      fetch(playerCountNode.dataset.url, {
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-Token': document.head.querySelector('[name=csrf-token][content]').content
        }
      }).then((response) => {
          return response.json();
        })
        .then((data) => {
          playerCountConfig.series = data.datasets;
          playerCountConfig.labels = data.labels;

          (new ApexCharts(playerCountNode, playerCountConfig)).render();
        })
        .catch((err) => {
                console.error('Unable to load chart ' + playerCountNode.id, err);
              });
    }

    // esi response time chart
    let esiResponseNode = document.querySelector('#esi-response-chart');
    let esiResponseConfig = {
      chart: {
        height: 240,
        type: 'area',
        stacked: false,
        toolbar: {
          show: false
        }
      },
      colors: ["#206bc4"],
      dataLabels: {
        enabled: false,
      },
      grid: {
        padding: {
          top: -20,
          right: 0,
          bottom: -4,
          left: -4
        },
        strokeDashArray: 4,
        xaxis: {
          lines: {
            show: true
          }
        }
      },
      stroke: {
        width: 2,
        lineCap: "round",
        curve: "smooth",
      },
      tooltip: {
        followCursor: true
      },
      fill: {
        opacity: .16,
        type: 'solid'
      },
      point: {
        show: false
      },
      legend: {
        show: false,
      },
      xaxis: {
        labels: {
          padding: 0,
        },
        tooltip: {
          enabled: false
        },
        axisBorder: {
          show: false,
        },
        type: 'datetime',
      },
      yaxis: {
        decimalsInFloat: 3,
        labels: {
          padding: 4
        }
      }
    };

    if (esiResponseNode) {
      fetch(esiResponseNode.dataset.url, {
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-Token': document.head.querySelector('[name=csrf-token][content]').content
        }
      }).then((response) => {
                return response.json();
              })
        .then((data) => {
          esiResponseConfig.series = data.datasets;
          esiResponseConfig.labels = data.labels;

          (new ApexCharts(esiResponseNode, esiResponseConfig)).render();
        })
        .catch((err) => {
                console.error('Unable to load chart ' + esiResponseNode.id, err);
              });
    }

    let skillsCoverageNode = document.querySelector('#skills-coverage-chart');
    let skillsCoverageConfig = {
      xaxis: {
        categories: ['Level I', 'Level II', 'Level III', 'Level IV', 'Level V']
      },
      colors: ['#d63939', '#f59f00', '#2fb344', '#206bc4', '#4299e1'],
      plotOptions: {
        bar: {
          distributed: true
        }
      },
      chart: {
        type: 'bar',
        height: 250,
        toolbar: {
          show: false
        }
      },
      tooltip: {
        enabled: false
      },
    };

    if (skillsCoverageNode) {
      fetch(skillsCoverageNode.dataset.url, {
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-Token': document.head.querySelector('[name=csrf-token][content]').content
        }
      }).then((response) => {
          return response.json();
        })
        .then((data) => {
          console.debug(data);
          skillsCoverageConfig.series = data.datasets;
          skillsCoverageConfig.labels = data.labels;

          (new ApexCharts(skillsCoverageNode, skillsCoverageConfig)).render();
        })
        .catch((err) => {
          console.error('Unable to load chart ' + skillsCoverageNode.id, err);
        });
    }

    let profileCoverageNode = document.querySelector('#profile-coverage-chart');
    let profileCoverageConfig = {
      labels: ['Core', 'Leadership', 'Fighter', 'Industrial'],
      colors: ['#f59f00', '#74b816', '#d63939', '#4263eb'],
      chart: {
        type: 'donut',
        height: 250,
        toolbar: {
          show: false
        }
      },
      tooltip: {
        enabled: false
      },
    };

    if (profileCoverageNode) {
      fetch(profileCoverageNode.dataset.url, {
        headers: {
          'Content-Type': 'application/json',
          'X-Requested-With': 'XMLHttpRequest',
          'X-CSRF-Token': document.head.querySelector('[name=csrf-token][content]').content
        }
      }).then((response) => {
          return response.json();
        })
        .then((data) => {
          profileCoverageConfig.series = [
            data.core.stats,
            data.leadership.stats,
            data.fighter.stats,
            data.industrial.stats,
          ];

          (new ApexCharts(profileCoverageNode, profileCoverageConfig)).render();
        })
        .catch((err) => {
          console.error('Unable to load chart ' + profileCoverageNode.id, err);
        });
    }
  </script>
@endpush
