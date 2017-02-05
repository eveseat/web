@extends('web::layouts.grids.3-9')

@section('title', trans('web::seat.people_groups'))
@section('page_header', trans('web::seat.people_groups'))

@section('left')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.unaffiliated_keys') }}</h3>
    </div>
    <div class="panel-body">

      @foreach($unaffiliated as $key)

        <ul class="list-unstyled">
          <li>
            <span class="text-muted">{{ trans('web::seat.key') }}: {{ $key->key_id }}</span>

            <!-- Button trigger modal -->
            <a type="button" id="add-to-existing" a-key-id="{{ $key->key_id }}" class="pull-right" data-toggle="modal"
               data-target="#groupModal">
              {{ trans('web::seat.add_to_existing_group') }}
            </a>

          </li>

          @foreach($key->characters as $character)
            <li>
              <a href="{{ route('character.view.sheet', ['character_id' => $character->characterID]) }}">
                {!! img('character', $character->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                {{ $character->characterName }}
              </a>
              <a href="{{ route('people.new.group', ['character_id' => $character->characterID]) }}" class="pull-right"
                 data-toggle="tooltip"
                 title=""
                 data-original-title="{{ trans('web::seat.new_group_with_main', ['name' => $character->characterName]) }}">
                <i class="fa fa-plus"></i>
              </a>
            </li>
          @endforeach

        </ul>
        <hr>

      @endforeach

    </div>
    <div class="panel-footer">Footer</div>
  </div>

@stop

@section('right')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.people_groups') }}</h3>
    </div>
    <div class="panel-body">

        <div class="col-md-12">

          <table class="table compact table-condensed table-hover table-responsive" id="characters">
            <thead>
              <tr>
                <th>{{ trans('web::seat.main_character') }}</th>
                <th>{{ trans_choice('web::seat.character', 2) }}</th>
                <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
                <th>{{ trans('web::seat.key') }}</th>
                <th>{{ trans('web::seat.actions') }}</th>
              </tr>
            </thead>
            <tbody>
              @foreach($people as $person)

                <tr>
                  <td id="main_character" class="{{$person->main_character_id}}">
                    {!! img('character', $person->main_character_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                    {{ $person->main_character_name }}
                  </td>

                    @foreach($person->members as $member)

                        @foreach($member->characters as $character)
                        <tr id="characters" class="{{$person->main_character_id}}">
                          <td>
                            <a href="{{ route('character.view.sheet', ['character_id' => $character->characterID]) }}">
                              {!! img('character', $character->characterID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                              {{ $character->characterName }}
                            </a>
                          </td>

                          <td>
                            {{ $character->corporationName }}
                          </td>

                          <td>
                            <span>Key: {{ $member->key_id }}</span>
                          </td>

                          <td>
                            <a href="{{ route('people.remove.group.key', ['group_id' => $person->id, 'key_id' => $member->key_id]) }}"><i class="fa fa-chain-broken"></i></a>
                            &nbsp;
                            <a href="{{ route('people.set.main', ['group_id' => $person->id, 'character_id' => $character->characterID]) }}"data-toggle="tooltip"
                               title="" data-original-title="Set {{ $character->characterName }} as Main">
                              <i class="fa fa-angle-double-up"></i>
                            </a>
                          </td>

                        </tr>
                      @endforeach

                    @endforeach
                </tr>
              @endforeach
            </tbody>
          </table>

        </div>

    </div>
  </div>

  <!-- Modal -->
  <div class="modal fade" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="ownerModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">
            {{ trans('web::seat.add_to_existing') }}
          </h4>
        </div>
        <div class="modal-body">

          <form role="form" action="{{ route('people.add.group.existing') }}" method="post">
            {{ csrf_field() }}

            <div class="box-body">

              <input type="hidden" name="key_id" id="group-add-key-id-value">

              <div class="form-group">
                <label>{{ trans('web::seat.group_main') }}</label>
                <select name="person_id" id="user_id" class="form-control select2" style="width: 100%;">
                </select>
              </div>
              <!-- /.form-group -->

            </div>
            <!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary pull-right">
                {{ trans('web::seat.add') }}
              </button>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
@stop

@push('javascript')

<script>

  // Update the modal key_id to the clicked one
  $("a#add-to-existing").click(function () {

    $("input#group-add-key-id-value")
        .val($(this).attr('a-key-id'));
  });

  $("#user_id").select2({
    ajax: {
      url     : "{{ route('people.search') }}",
      dataType: 'json',
      delay   : 250,
      data    : function (params) {
        return {
          q   : params.term, // search term
          page: params.page
        };
      },
    }
  });

  $("td#main_character").each(function(index, td) {
    var characters = $("table#characters").find("tr[class=" + $(this).attr('class') + "]").length + 1;
    $(this).attr('rowspan', characters);
  });

</script>

@endpush
