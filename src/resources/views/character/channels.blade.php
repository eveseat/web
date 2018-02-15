@extends('web::character.layouts.view', ['viewname' => 'channels'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.channels'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.channels'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.channels') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans('web::seat.owner') }}</th>
          <th>{{ trans_choice('web::seat.role', 1) }}</th>
          <th>{{ trans('web::seat.password') }}</th>
        </tr>
        </thead>
        <tbody>

        @foreach($channels as $channel)

          <tr>
            <td>{{ $channel->info->name }}</td>
            <td>
              <a href="{{ route('character.view.sheet', ['character_id' => $channel->info->owner_id]) }}">
                {!! img('character', $channel->info->owner_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                <span rel="id-to-name">{{ $channel->info->owner_id }}</span>
              </a>
            </td>
            <td>
              @foreach($channel->members as $member)
                @if($member->accessor_id == $request->character_id)

                  {{ $member->role }}

                @endif
              @endforeach
            </td>
            <td>
              @if($channel->info->has_password)
                {{ trans('web::seat.yes') }}
              @else
                {{ trans('web::seat.no') }}
              @endif
            </td>
            <td>

              <!-- Button trigger modal -->
              <a type="button" data-toggle="modal" data-target="#detailModal{{ $channel->channel_id }}">
                <i class="fa fa-commenting"></i>
                {{ trans_choice('web::seat.detail', 1) }}
              </a>

              <!-- Modal -->
              <div class="modal fade" id="detailModal{{ $channel->channel_id }}" tabindex="-1" role="dialog"
                   aria-labelledby="passwordModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                      </button>
                      <h4 class="modal-title" id="passwordModalLabel">
                        {{ trans_choice('web::seat.detail', 2) }}:
                        {{ $channel->info->name }}
                      </h4>
                    </div>
                    <div class="modal-body">

                      <p class="text-muted">{{ trans('web::seat.motd') }}</p>

                      <pre>{!! clean_ccp_html($channel->info->motd) !!}</pre>

                      <p class="text-muted">{{ trans('web::seat.channel_members') }}</p>

                      <table class="table table-condensed table-hover table-responsive">
                        <thead>
                        <tr>
                          <th>{{ trans_choice('web::seat.name', 1) }}</th>
                          <td>{{ trans_choice('web::seat.role', 1) }}</td>
                        </tr>
                        </thead>
                        <tbody>

                        @foreach($channel->members as $member)

                          <tr>
                            <td>
                              <a href="{{ route('character.view.sheet', ['character_id' => $member->accessor_id]) }}">
                                {!! img('character', $member->accessor_id, 64, ['class' => 'img-circle eve-icon small-icon'], false) !!}
                                <span rel="id-to-name">{{ $member->accessor_id }}</span>
                              </a>
                            </td>
                            <td>{{ $member->role }}</td>
                          </tr>

                        @endforeach

                        </tbody>
                      </table>

                    </div>
                  </div>
                </div>
              </div>

            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      {{ count($channels) }} {{ trans('web::seat.channels') }}
    </div>
  </div>

@stop
