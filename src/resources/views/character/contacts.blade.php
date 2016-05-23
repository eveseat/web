@extends('web::character.layouts.view', ['viewname' => 'contacts'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contacts'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.contacts'))

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.contacts') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table datatable compact table-condensed table-hover table-responsive">
        <thead>
          <tr>
            <th>{{ trans_choice('web::seat.name', 1) }}</th>
            <th>{{ trans('web::seat.standings') }}</th>
            <th>{{ trans('web::seat.labels') }}</th>
          </tr>
        </thead>
        <tbody>
        @foreach($contacts->sortByDesc('standing') as $contact)
          <tr class="
            @if($contact->standing > 0)
              success
            @elseif($contact->standing < 0)
              danger
            @endif
                  ">
            <td>
              {!! img('auto', $contact->contactID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $contact->contactName }}
            </td>
            <td>
              <span class="
                @if($contact->standing > 0)
                      text-success
                @elseif($contact->standing < 0)
                      text-danger
                @endif
                      ">
                <b>{{ $contact->standing }}</b>
              </span>
            </td>
            <td>
              {{ $labels->filter(function($item) use ($contact) {
                return $item->labelID & $contact->labelMask; })->implode('name', ', ') }}
            </td>
          </tr>
        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
