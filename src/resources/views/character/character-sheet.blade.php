@extends('web::layouts.grids.3-9')

@section('title', ucfirst(trans_choice('web::character.character', 2)))
@section('page_header', ucfirst(trans_choice('web::character.character', 2)))

@section('left')

  @include('web::character.includes.summary')

@stop

@section('right')

  <div class="row">

    <div class="col-md-6">

      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">Skills</h3>
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
    </div>
  </div>

@stop
