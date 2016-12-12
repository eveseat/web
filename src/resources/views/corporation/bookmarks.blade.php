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

        @forelse($bookmarks->unique('folderID')->groupBy('folderID') as $folder_id => $data)

          <tr class="active">
            <td colspan="6">
              <b>
                {{ trans('web::seat.folder') }}:
                @if(strlen($data[0]->folderName ) > 0)
                  {{ $data[0]->folderName }}
                @else
                  {{ trans('web::seat.none') }}
                @endif
              </b>
              <span class="pull-right">
              <i>
                {{ count($bookmarks->where('folderID', $data[0]->folderID)) }}
                {{ trans_choice('web::seat.bookmark', count($bookmarks->where('folderID', $data[0]->folderID))) }}
              </i>
            </span>
            </td>
          </tr>

          @foreach($bookmarks->where('folderID', $data[0]->folderID) as $bookmark)

            <tr>
              <td>
                <span data-toggle="tooltip"
                      title="" data-original-title="{{ $bookmark->created }}">
                  {{ human_diff($bookmark->created) }}
                </span>
              </td>
              <td>
                <a href="{{ route('character.view.sheet', ['character_id' => $bookmark->creatorID]) }}">
                  {!! img('character', $bookmark->creatorID, 64, ['class' => 'img-circle eve-icon small-icon']) !!}
                  <span rel="id-to-name">{{ $bookmark->creatorID }}</span>
                </a>
              </td>
              <td>{{ clean_ccp_html($bookmark->memo) }}</td>
              <td>{{ $bookmark->mapName }}</td>
              <td>{{ clean_ccp_html($bookmark->note) }}</td>
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

@push('javascript')

@include('web::includes.javascript.id-to-name')

@endpush
