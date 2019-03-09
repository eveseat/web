@extends('web::character.layouts.view', ['viewname' => 'skills', 'breadcrumb' => trans('web::seat.skills')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.skills'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.skills_graph') }}
      </h3>
    </div>
    <div class="panel-body">

      <h4 class="box-title">{{ trans('web::seat.main_char_skills_per_level') }}</h4>
      <div class="chart">
        <canvas id="skills-level" height="230" width="1110"></canvas>
      </div>

      <h4 class="box-title">{{ trans('web::seat.main_char_skills_coverage') }}</h4>
      <div class="chart">
        <canvas id="skills-coverage" height="400" width="1110"></canvas>
      </div>

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans('web::seat.skills') }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.skills']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_skills') }}"></i>
            </a>
          </span>
        @endif
      </h3>
    </div>
    <div class="panel-body no-padding">

      @foreach($skill_groups as $skill_group)

        @if(count($skills->where('groupID', $skill_group->groupID)) > 0)

          <div class="box box-solid">
            <div class="box-header with-border">
              <button type="button" class="btn btn-box-tool" data-widget="collapse">
                <i class="fa fa-minus"></i>
              </button>
              <h3 class="box-title">
                {{ $skill_group->groupName }}
              </h3>
              <span class="pull-right">
                {{ count($skills->where('groupID', $skill_group->groupID)) }} skills
              </span>
            </div>
            <div class="box-body">
              <table class="table table-striped table-hover table-condensed table-responsive">
                @foreach($skills->where('groupID', $skill_group->groupID) as $skill)
                  <tr>
                    <td><i class="fa fa-book"></i> {{ $skill->typeName }}</td>
                    <td class="text-right">
                      @if($skill->trained_skill_level == 0)
                        <i class="fa fa-star-o"></i>
                      @elseif($skill->trained_skill_level == 5)
                        <span class="text-green">
                      @for($i = 1; $i <= 5; $i++)
                            <i class="fa fa-star"></i>
                          @endfor
                    </span>
                      @else
                        @for($i = 1;  $i <= $skill->trained_skill_level; $i++)
                          <i class="fa fa-star"></i>
                        @endfor
                      @endif
                      | {{ $skill->trained_skill_level }}
                    </td>
                  </tr>
                @endforeach
              </table>
            </div><!-- /.box-body -->
            <div class="box-footer">
              {{ number($skills->where('groupID', $skill_group->groupID)->sum('skillpoints_in_skill'), 0) }}
              total skillpoints
            </div>
          </div>

        @endif

      @endforeach

    </div>
    <div class="panel-footer clearfix">
      <span class="pull-left">
        {{ number($skills->sum('skillpoints_in_skill'), 0) }} total skillpoints
      </span>
      <span class="pull-right">
        {{ number(count($skills), 0) }} skills
      </span>
    </div>
  </div>

@stop

@push('javascript')
  <script>

    $.get("{{ route('character.view.skills.graph.level', ['character_id' => $request->character_id]) }}", function (data) {
      new Chart($("canvas#skills-level"), {
        type: 'pie',
        data: data
      });
    });

    $.get("{{ route('character.view.skills.graph.coverage', ['character_id' => $request->character_id]) }}", function (data) {
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

    $('')

  </script>
@endpush
