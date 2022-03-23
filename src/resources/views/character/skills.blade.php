@extends('web::layouts.character', ['viewname' => 'skills', 'breadcrumb' => trans('web::seat.skills')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.skills'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="row row-cols-1 row-cols-lg-2 row-cols-xl-3 mb-3">
    <div class="col">
      <div class="card h-100">
        <div class="card-header">
          <h3 class="card-title">Skills</h3>
        </div>
        <div class="card-body">
          <div id="skills-level-chart" data-level-zero="{{ $character->skills->where('trained_skill_level', 0)->count() }}" data-level-one="{{ $character->skills->where('trained_skill_level', 1)->count() }}" data-level-two="{{ $character->skills->where('trained_skill_level', 2)->count() }}" data-level-three="{{ $character->skills->where('trained_skill_level', 3)->count() }}" data-level-four="{{ $character->skills->where('trained_skill_level', 4)->count() }}" data-level-five="{{ $character->skills->where('trained_skill_level', 5)->count() }}"></div>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card h-100">
        <div class="card-header">
          <h3 class="card-title">Profile</h3>
        </div>
        <div class="card-body">
          <div id="training-profile-chart" data-core-coverage="{{ $training_profiles->core->stats }}" data-leadership-coverage="{{ $training_profiles->leadership->stats }}" data-fighter-coverage="{{ $training_profiles->fighter->stats }}" data-industrial-coverage="{{ $training_profiles->industrial->stats }}"></div>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card h-100">
        <div class="card-header">
          <h3 class="card-title">Coverage</h3>
        </div>
        <div class="card-body">
          <div id="skills-coverage-chart"></div>
        </div>
      </div>
    </div>
  </div>
  <div class="d-flex align-items-start mb-3">
    <div class="card me-3">
      <ul class="nav nav-pills seat-nav-vertical-pills flex-sm-column" role="tablist" aria-orientation="vertical">
        @foreach($skill_categories as $category)
          <li class="nav-item" role="presentation">
            <a href="#" @class(['nav-link', 'w-100', 'active' => $loop->first]) data-bs-toggle="pill" data-bs-target="#skills-category-{{ $category->groupID }}" type="button" role="tab" aria-selected="true">
              <div class="col">
                <div class="text-start mb-2">{{ $category->groupName }}</div>
                <div class="progress progress-sm mb-2">
                  <div class="progress-bar" role="progressbar" style="width: {{ round($character->skills->where('type.group.groupID', $category->groupID)->count() / $category->types->where('published', true)->count() * 100) }}%;" aria-valuenow="38" aria-valuemin="0" aria-valuemax="0">
                    <span class="visually-hidden">{{ number_format($character->skills->where('type.group.groupID', $category->groupID)->count() / $category->types->where('published', true)->count() * 100, 2) }}% Complete</span>
                  </div>
                </div>
              </div>
            </a>
          </li>
        @endforeach
      </ul>
    </div>
    <div class="tab-content flex-fill">
      @foreach($skill_categories as $category)
        <div @class(['tab-pane', 'fade', 'show' => $loop->first, 'active' => $loop->first]) role="tabpanel" id="skills-category-{{ $category->groupID }}">
          <div class="row">
            <div class="col">
              <div class="card mb-3">
                <div class="card-header">
                  <h3 class="card-title">{{ $category->groupName }}</h3>
                </div>
                <ul class="list-group list-group-flush">
                  @foreach($character->skills->where('type.groupID', $category->groupID)->sortBy('type.typeName') as $skill)
                    <li class="list-group-item d-flex align-items-center">
                      <div class="col">
                        <div class="d-flex align-items-center">
                          <span class="avatar me-1">
                            <span class="h1 m-0">{{ $skill->trained_skill_level }}</span>
                          </span>
                          <strong class="ms-2">{{ $skill->type->typeName }}</strong>
                        </div>
                      </div>
                      <div class="col d-none d-xxl-block">
                        <div>
                          <b>{{ number_format($skill->skillpoints_in_skill) }} trained skillpoints</b>
                        </div>
                        <small class="text-muted fst-italic">{{ number_format($skill->type->maximum_skillpoints) }} total skillpoints</small>
                      </div>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <div class="col-auto">
              <!-- profile-coverage -->
              <div class="card card-sm mb-3">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      @switch(true)
                        @case(in_array($category->groupID, $training_profiles->core->categories))
                        <div class="chart-sparkline chart-sparkline-square profile-coverage" data-coverage-stats="{{ $training_profiles->core->stats }}"></div>
                        @break
                        @case(in_array($category->groupID, $training_profiles->leadership->categories))
                        <div class="chart-sparkline chart-sparkline-square profile-coverage" data-coverage-stats="{{ $training_profiles->leadership->stats }}"></div>
                        @break
                        @case(in_array($category->groupID, $training_profiles->fighter->categories))
                        <div class="chart-sparkline chart-sparkline-square profile-coverage" data-coverage-stats="{{ $training_profiles->fighter->stats }}"></div>
                        @break
                        @case(in_array($category->groupID, $training_profiles->industrial->categories))
                        <div class="chart-sparkline chart-sparkline-square profile-coverage" data-coverage-stats="{{ $training_profiles->industrial->stats }}"></div>
                        @break
                      @endswitch
                    </div>
                    <div class="col">
                      <div class="font-weight-bold">Profile coverage</div>
                      @if($character->skills)
                        @switch(true)
                          @case(in_array($category->groupID, $training_profiles->core->categories))
                            <div class="text-muted fst-italic">{{ trans($training_profiles->core->label) }} {{ number_format($training_profiles->core->stats, 2) }}%</div>
                          @break
                          @case(in_array($category->groupID, $training_profiles->leadership->categories))
                            <div class="text-muted fst-italic">{{ trans($training_profiles->leadership->label) }} {{ number_format($training_profiles->leadership->stats, 2) }}%</div>
                          @break
                          @case(in_array($category->groupID, $training_profiles->fighter->categories))
                            <div class="text-muted fst-italic">{{ trans($training_profiles->fighter->label) }} {{ number_format($training_profiles->fighter->stats, 2 ) }}%</div>
                          @break
                          @case(in_array($category->groupID, $training_profiles->industrial->categories))
                            <div class="text-muted fst-italic">{{ trans($training_profiles->industrial->label) }} {{ number_format($training_profiles->industrial->stats, 2) }}%</div>
                          @break
                        @endswitch
                      @else
                        <div class="text-muted fst-italic">0.00 %</div>
                      @endif
                    </div>
                  </div>
                </div>
              </div>
              <!-- ./profile-coverage -->
              <!-- section-completeness -->
              <div class="card card-sm mb-3">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <div class="chart-sparkline chart-sparkline-square section-completeness" data-trained-skills="{{ $character->skills->where('type.groupID', $category->groupID)->count() }}" data-overall-skills="{{ $category->types_count }}"></div>
                    </div>
                    <div class="col">
                      <div class="font-weight-bold">Section completeness</div>
                      <div class="text-muted fst-italic">{{ $character->skills->where('type.groupID', $category->groupID)->count() }} / {{ $category->types_count }} skills</div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ./section-completeness -->
              <!-- level-5 -->
              <div class="card card-sm mb-3">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-auto">
                    <span class="bg-azure text-white avatar">
                      <span class="h1 m-0">V</span>
                    </span>
                    </div>
                    <div class="col">
                      <div class="font-weight-bold">{{ $character->skills->where('type.groupID', $category->groupID)->where('trained_skill_level', 5)->count() }} skills</div>
                      <div class="text-muted fst-italic">{{ number_format($character->skills->where('type.groupID', $category->groupID)->where('trained_skill_level', 5)->sum('skillpoints_in_skill')) }} skillpoints</div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ./level-5 -->
              <!-- level-4 -->
              <div class="card card-sm mb-3">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-auto">
                    <span class="bg-blue text-white avatar">
                      <span class="h1 m-0">IV</span>
                    </span>
                    </div>
                    <div class="col">
                      <div class="font-weight-bold">{{ $character->skills->where('type.groupID', $category->groupID)->where('trained_skill_level', 4)->count() }} skills</div>
                      <div class="text-muted fst-italic">{{ number_format($character->skills->where('type.groupID', $category->groupID)->where('trained_skill_level', 4)->sum('skillpoints_in_skill')) }} skillpoints</div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ./level-4 -->
              <!-- level-3 -->
              <div class="card card-sm mb-3">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-auto">
                    <span class="bg-green text-white avatar">
                      <span class="h1 m-0">III</span>
                    </span>
                    </div>
                    <div class="col">
                      <div class="font-weight-bold">{{ $character->skills->where('type.groupID', $category->groupID)->where('trained_skill_level', 3)->count() }} skills</div>
                      <div class="text-muted fst-italic">{{ number_format($character->skills->where('type.groupID', $category->groupID)->where('trained_skill_level', 3)->sum('skillpoints_in_skill')) }} skillpoints</div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ./level-3 -->
              <!-- level-2 -->
              <div class="card card-sm mb-3">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-auto">
                    <span class="bg-yellow text-white avatar">
                      <span class="h1 m-0">II</span>
                    </span>
                    </div>
                    <div class="col">
                      <div class="font-weight-bold">{{ $character->skills->where('type.groupID', $category->groupID)->where('trained_skill_level', 2)->count() }} skills</div>
                      <div class="text-muted fst-italic">{{ number_format($character->skills->where('type.groupID', $category->groupID)->where('trained_skill_level', 2)->sum('skillpoints_in_skill')) }} skillpoints</div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ./level-2 -->
              <!-- level-1 -->
              <div class="card card-sm mb-3">
                <div class="card-body">
                  <div class="row align-items-center">
                    <div class="col-auto">
                    <span class="bg-red text-white avatar">
                      <span class="h1 m-0">I</span>
                    </span>
                    </div>
                    <div class="col">
                      <div class="font-weight-bold">{{ $character->skills->where('type.groupID', $category->groupID)->where('trained_skill_level', 1)->count() }} skills</div>
                      <div class="text-muted fst-italic">{{ number_format($character->skills->where('type.groupID', $category->groupID)->where('trained_skill_level', 1)->sum('skillpoints_in_skill')) }} skillpoints</div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- ./level-1 -->
            </div>
          </div>
        </div>
      @endforeach
    </div>
  </div>
@stop

@push('head')
  <style>
    .seat-nav-vertical-pills li {
      position: relative;
    }

    .seat-nav-vertical-pills li .active::after {
      position: absolute;
      content: "";
      top: 0;
      right: -1px;
      bottom: 0;
      border-right: 1px solid var(--tblr-blue);
    }
  </style>
@endpush

@push('javascript')
  <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
  <script>
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

    let widgetSectionConfig = {
      chart: {
        height: 40,
        width: 40,
        type: 'radialBar',
        animations: {
          enabled: false
        },
        sparkline: {
          enabled: true
        }
      },
      tooltip: {
        enabled: false
      },
      plotOptions: {
        radialBar: {
          hollow: {
            margin: 0,
            size: '75%'
          },
          track: {
            margin: 0
          },
          dataLabels: {
            show: false
          }
        }
      },
      colors: ['#206bc4'],
      series: [],
    };

    // profile coverage chart
    document.querySelectorAll('.profile-coverage').forEach((node) => {
      widgetSectionConfig.series = [node.dataset.coverageStats];
      (new ApexCharts(node, widgetSectionConfig)).render();
    });

    // section completeness charts
    document.querySelectorAll('.section-completeness').forEach((node) => {
      widgetSectionConfig.series = [node.dataset.trainedSkills / node.dataset.overallSkills * 100];
      (new ApexCharts(node, widgetSectionConfig)).render();
    });

    // skill per level chart
    [document.querySelector('#skills-level-chart')].forEach((node) => {
      skillsCoverageConfig.series = [{
        data: [
          parseInt(node.dataset.levelOne),
          parseInt(node.dataset.levelTwo),
          parseInt(node.dataset.levelThree),
          parseInt(node.dataset.levelFour),
          parseInt(node.dataset.levelFive),
        ]
      }];

      (new ApexCharts(node, skillsCoverageConfig)).render();
    });

    // training profile chart
    [document.querySelector('#training-profile-chart')].forEach((node) => {
      profileCoverageConfig.series = [
        parseFloat(node.dataset.coreCoverage),
        parseFloat(node.dataset.leadershipCoverage),
        parseFloat(node.dataset.fighterCoverage),
        parseFloat(node.dataset.industrialCoverage),
      ];

      (new ApexCharts(node, profileCoverageConfig)).render();
    });

    // skill coverage chart
    (new ApexCharts(document.querySelector('#skills-coverage-chart'), {
      series: [
        {
          name: 'Overall',
          data: [92.31,20.0,74.07,100.0,100.0,82.35,47.62,84.62,100.0,62.50,100.0,91.67,90.0,100.0,57.14,80.49,92.31,22.22,68.24,100.0,25.0,100.0,64.29]
        },
      ],
      xaxis: {
        categories: ['Armor', 'Corporation Management', 'Drones', 'Electronic Systems', 'Engineering', 'Fleet Support', 'Gunnery', 'Missiles', 'Navigation', 'Neural Enhancement', 'Planet Management', 'Production', 'Resource Processing', 'Rigging', 'Scanning', 'Science', 'Shields', 'Social', 'Spaceship Command', 'Structure Management', 'Subsystems', 'Targeting', 'Trade']
      },
      yaxis: {
        min: 0.0,
        max: 100.0
      },
      chart: {
        type: 'radar',
        height: 250,
        toolbar: {
          show: false
        }
      },
      grid: {
        padding: {
          top: -50,
          right: -50,
          bottom: -50,
          left: -50
        }
      }
    })).render();
  </script>
@endpush
