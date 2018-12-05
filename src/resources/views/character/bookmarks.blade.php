@extends('web::character.layouts.view', ['viewname' => 'bookmarks'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans_choice('web::seat.bookmark', 2))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans_choice('web::seat.bookmark', 2))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        {{ trans_choice('web::seat.bookmark', 2) }}
        @if(auth()->user()->has('character.jobs'))
          <span class="pull-right">
            <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.bookmarks']) }}"
               style="color: #000000">
              <i class="fa fa-refresh" data-toggle="tooltip" title="{{ trans('web::seat.update_bookmarks') }}"></i>
            </a>
          </span>
        @endif
      </h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans('web::seat.created') }}</th>
          <th>{{ trans_choice('web::seat.name', 1) }}</th>
          <th>{{ trans('web::seat.location') }}</th>
          <th>{{ trans('web::seat.note') }}</th>
          <th></th>
        </tr>

        @forelse($bookmarks->unique('folder_id')->groupBy('folder_id') as $folder_id => $data)

          <tr class="active">
            <td colspan="5">
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
