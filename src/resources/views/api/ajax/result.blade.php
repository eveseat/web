<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ trans('web::seat.api_key_info') }}</h3>
  </div>
  <div class="panel-body">

    <div class="row">
      <div class="col-md-5">

        <dl class="dl-horizontal">
          <dt>{{ trans('web::seat.api_key_type') }}</dt>
          <dd>{{ $key_type }}</dd>
          <dt>{{ trans('web::seat.api_access_mask') }}</dt>
          <dd>{{ $access_mask }}</dd>
        </dl>

        @if(setting('force_min_mask', true) == 'yes' && (($access_mask < setting('min_character_access_mask', true) &&
          $key_type != 'Corporation') || ($access_mask < setting('min_corporation_access_mask', true) &&
          $key_type == 'Corporation')))

          <div class="text-danger">
            {{ trans('web::seat.insufficient_access_mask') }}
          </div>

        @endif

        <hr>
        <ul class="clearfix">

          @foreach($characters as $character)

            <li class="list-unstyled">

              <div class="user-block">
                <img class="img-circle img-bordered-sm"
                     src="//image.eveonline.com/Character/{{ $character->characterID }}_256.jpg"
                     alt="user image">
                <span class="username">
                  <span>{{ $character->characterName }}</span>
                </span>
                <span class="description">{{ $character->corporationName }}</span>
              </div>

            </li>

          @endforeach

        </ul>

        @if(! (setting('force_min_mask', true) == 'yes' && (($access_mask < setting('min_character_access_mask', true) &&
          $key_type != 'Corporation')) || ($access_mask < setting('min_corporation_access_mask', true) &&
          $key_type == 'Corporation')))

          <form role="form" action="{{ route('api.key.add') }}" method="post">
            {{ csrf_field() }}

            <input type="hidden" name="key_id" value="{{ $key_id }}">
            <input type="hidden" name="v_code" value="{{ $v_code }}">

            <div class="box-footer">
              <button type="submit" class="btn btn-block btn-success">
                {{ trans('web::seat.api_add_type_key', ['type' => $key_type]) }}
              </button>
            </div>
          </form>

          <div class="text-muted">
            <i class="fa fa-info"></i>
            <span class="text-bold">{{ trans('web::seat.note') }}:</span>
            {{ trans('web::seat.api_add_job') }}
          </div>

        @endif

      </div>

      <div class="col-md-7">

        <table class="table table-condensed table-hover">
          <tbody>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans('web::seat.api_min_mask') }}</th>
            <th>{{ trans('web::seat.api_access') }}</th>
          </tr>

          @foreach($access_map as $name => $bitmask)

            <tr>
              <td>{{ ucfirst($name) }}</td>
              <td>{{ $bitmask }}</td>
              <td>
                @if($access_mask & $bitmask)
                  <span class="label label-success">
                      {{ trans('web::seat.granted') }}
                    </span>
                @else
                  <span class="label label-danger">
                    {{ trans('web::seat.denied') }}
                    </span>
                @endif
              </td>
            </tr>

          @endforeach

          </tbody>
        </table>

      </div>
    </div>

  </div>
</div>
