@extends('web::corporation.layouts.view', ['viewname' => 'bookmarks'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.bookmark', 2))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.bookmark', 2))

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans_choice('web::seat.bookmark', 2) }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <thead>
        <tr>
          <th>{{ trans('web::seat.created') }}</th>
          <th>{{ trans('web::seat.created_by') }}</th>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans('web::seat.location') }}</th>
          <th>{{ trans('web::seat.note') }}</th>
          <th></th>
        </tr>
        </thead>
        <tbody>

        @forelse($bookmarks->unique('folder_id')->groupBy('folder_id') as $folder_id => $data)

          <tr class="active">
            <td colspan="6">
              <b>
                {{ trans('web::seat.folder') }}: {{ $data[0]->folder->name }}
              </b>
              <span class="pull-right">
              <i>
                {{ count($bookmarks->where('folder_id', $data[0]->folder_id)) }}
                {{ trans_choice('web::seat.bookmark', count($bookmarks->where('folder_id', $data[0]->folder_id))) }}
              </i>
            </span>
            </td>
          </tr>

          @foreach($bookmarks->where('folder_id', $data[0]->folder_id) as $bookmark)

            <tr>
              <td>
                <span data-toggle="tooltip"
                      title="" data-original-title="{{ $bookmark->created }}">
                  {{ human_diff($bookmark->created) }}
                </span>
              </td>
              <td>
                <a href="{{ route('character.view.sheet', ['character_id' => $bookmark->creator_id]) }}">
                  {!! img('character', $bookmark->creator_id, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  <span class="id-to-name" data-id="{{ $bookmark->creator_id }}">{{ trans('web::seat.unknown') }}</span>
                </a>
              </td>
              <td>{{ clean_ccp_html($bookmark->label) }}</td>
              <td>{{ $bookmark->system->itemName }}</td>
              <td>{{ clean_ccp_html($bookmark->notes) }}</td>
              <td>
                <i class="fa fa-info-circle" data-toggle="tooltip"
                   title="" data-original-title="{{ trans('web::seat.coordinates') }}:
                    {{ $bookmark->x }} {{ $bookmark->y }} {{ $bookmark->z }}">
                </i>
              </td>
            </tr>

          @endforeach

        @empty

          {{ trans('web::seat.none') }}

        @endforelse

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      {{ count($bookmarks) }} {{ trans_choice('web::seat.bookmark', count($bookmarks)) }}
    </div>
  </div>

@stop
