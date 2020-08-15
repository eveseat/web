@extends('web::character.layouts.view', ['viewname' => 'skills', 'breadcrumb' => trans('web::seat.skills')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.skills'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="row">
    <div class="col-md-12">
      <div class="card-deck">
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{ trans('web::seat.main_char_skills_per_level') }}</h3>
          </div>
          <div class="card-body">
            <canvas id="skills-level" height="200"></canvas>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h3 class="card-title">{{ trans('web::seat.main_char_skills_coverage') }}</h3>
            <div class="card-tools">
              <div class="input-group input-group-sm">
                <a href="{{ route('character.export.skills', [request()->character_id]) }}" class="btn btn-sm btn-light">
                  <i class="fas fa-file-export"></i>
                  Export Skills (Pyfa Format)
                </a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <canvas id="skills-coverage" height="200"></canvas>
          </div>
        </div>
      </div>
    </div>
  </div>

  @foreach($skill_groups->chunk(2) as $skill_group_row)
    <div class="row">
      <div class="col-md-12 mt-3">
        <div class="card-deck">

          @foreach($skill_group_row as $skill_group)
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">{{ $skill_group->groupName }}</h3>
                @if(auth()->user()->has('character.jobs'))
                  <div class="card-tools">
                    <div class="input-group input-group-sm">
                      <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.skills']) }}"
                         class="btn btn-sm btn-light">
                        <i class="fas fa-sync" data-toggle="tooltip" title="{{ trans('web::seat.update_skills') }}"></i>
                      </a>
                    </div>
                  </div>
                @endif
              </div>
              <div class="card-body p-0">
                <table class="table table-striped table-hover table-condensed table-sm">
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
                        | <i>{{ $skill->trained_skill_level }}</i>
                      </td>
                    </tr>
                  @endforeach
                </table>
              </div>
              <div class="card-footer">
                <div class="float-left">
                  <i class="text-muted">{{ count($skills->where('groupID', $skill_group->groupID)) }} skills</i>
                </div>
                <div class="float-right">
                  <i class="text-muted">{{ number_format($skills->where('groupID', $skill_group->groupID)->sum('skillpoints_in_skill'), 0) }} skillpoints</i>
                </div>
              </div>
            </div>
          @endforeach

        </div>
      </div>
    </div>
  @endforeach

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
