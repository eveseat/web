@extends('web::character.layouts.view', ['viewname' => 'skills'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.skills'))
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
      <h3 class="panel-title">{{ trans('web::seat.skills') }}</h3>
    </div>
    <div class="panel-body">

      @foreach($skill_groups as $skill_group)

        @if(count($skills->where('groupID', $skill_group->groupID)) > 0)

          <div class="box box-solid">
            <div class="box-header with-border">
              <h3 class="box-title">
                {{ $skill_group->groupName }}
              </h3>
              <span class="pull-right">
                  {{ count($skills->where('groupID', $skill_group->groupID)) }}
                skills
                </span>
            </div>
            <div class="box-body">

              <ul class="list-unstyled">

                @foreach($skills->where('groupID', $skill_group->groupID) as $skill)

                  <li>
                    <i class="fa fa-book"></i> {{ $skill->typeName }}
                    <span class="pull-right">

                      @if($skill->level == 0)

                        <i class="fa fa-star-o"></i>

                      @elseif($skill->level == 5)

                        <span class="text-green">
                          <i class="fa fa-star"></i>
                          <i class="fa fa-star"></i>
                          <i class="fa fa-star"></i>
                          <i class="fa fa-star"></i>
                          <i class="fa fa-star"></i>
                        </span>

                      @else

                        @for ($i=0; $i < $skill->level ; $i++)

                          <i class="fa fa-star"></i>

                        @endfor

                      @endif

                      | {{ $skill->level }}
                    </span>
                  </li>

                @endforeach

              </ul>
            </div><!-- /.box-body -->
            <div class="box-footer">
              {{ $skills->where('groupID', $skill_group->groupID)->sum('skillpoints') }}
              total skillpoints
            </div>
          </div>

        @endif

      @endforeach

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

</script>
@endpush
