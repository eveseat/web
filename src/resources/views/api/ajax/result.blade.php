<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">{{ trans('web::api.key_info') }}</h3>
  </div>
  <div class="panel-body">

    <div class="row">
      <div class="col-md-5">

        <dl class="dl-horizontal">
          <dt>{{ trans('web::api.key_type') }}</dt>
          <dd>{{ $key_type }}</dd>
          <dt>{{ trans('web::api.access_mask') }}</dt>
          <dd>{{ $access_mask }}</dd>
        </dl>
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

        <form role="form" action="{{ route('api.key.add') }}" method="post">
          {{ csrf_field() }}

          <input type="hidden" name="key_id" value="{{ $key_id }}">
          <input type="hidden" name="v_code" value="{{ $v_code }}">

          <div class="box-footer">
            <button type="submit" class="btn btn-block btn-success">
              {{ trans('web::api.add_type_key', ['type' => $key_type]) }}
            </button>
          </div>
        </form>

        <div class="text-muted">
          <i class="fa fa-info"></i>
          <span class="text-bold">{{ ucfirst(trans('web::general.note')) }}:</span>
          {{ trans('web::api.add_job_notice') }}
        </div>

      </div>

      <div class="col-md-7">

        <table class="table table-condensed table-hover">
          <tbody>
          <tr>
            <th>{{ ucfirst(trans_choice('web::general.name', 1)) }}</th>
            <th>{{ trans('web::api.min_mask') }}</th>
            <th>{{ trans('web::api.access') }}</th>
          </tr>

          @foreach($access_map as $name => $bitmask)

            <tr>
              <td>{{ ucfirst($name) }}</td>
              <td>{{ $bitmask }}</td>
              <td>
                @if($access_mask & $bitmask)
                  <span class="label label-success">
                      {{ trans('web::api.granted') }}
                    </span>
                @else
                  <span class="label label-danger">
                    {{ trans('web::api.denied') }}
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
