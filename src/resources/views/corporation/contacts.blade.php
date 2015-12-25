@extends('web::corporation.layouts.view', ['viewname' => 'contacts'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.contacts'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.contacts'))

@section('corporation_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">{{ trans('web::seat.contacts') }}</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>{{ trans('web::seat.name') }}</th>
          <th>{{ trans('web::seat.standings') }}</th>
          <th>{{ trans('web::seat.labels') }}</th>
        </tr>

        <tr>
          <th class="text-center text-green" colspan="3">
            {{ trans('web::seat.positive_standings') }}
          </th>
        </tr>

        @foreach($contacts->filter(function($item) { return $item->standing > 0; }) as $contact)

          <tr class="success">
            <td>
              {!! img('auto', $contact->contactID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $contact->contactName }}
            </td>
            <td>{{ $contact->standing }}</td>
            <td>
              {{ $labels->filter(function($item) use ($contact) {
                return $item->labelID & $contact->labelMask; })->implode('name', ', ') }}
            </td>
          </tr>

        @endforeach

        <tr>
          <th class="text-center" colspan="3">
            {{ trans('web::seat.neutral_standings') }}
          </th>
        </tr>

        @foreach($contacts->filter(function($item) { return $item->standing == 0; }) as $contact)

          <tr>
            <td>
              {!! img('auto', $contact->contactID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $contact->contactName }}
            </td>
            <td>{{ $contact->standing }}</td>
            <td>
              {{ $labels->filter(function($item) use ($contact) {
                return $item->labelID & $contact->labelMask; })->implode('name', ', ') }}
            </td>
          </tr>

        @endforeach

        <tr>
          <th class="text-center text-red" colspan="3">
            {{ trans('web::seat.negative_standings') }}
          </th>
        </tr>

        @foreach($contacts->filter(function($item) { return $item->standing < 0; }) as $contact)

          <tr class="danger">
            <td>
              {!! img('auto', $contact->contactID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $contact->contactName }}
            </td>
            <td>{{ $contact->standing }}</td>
            <td>
              {{ $labels->filter(function($item) use ($contact) {
                return $item->labelID & $contact->labelMask; })->implode('name', ', ') }}
            </td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      {{ count($contacts) }} {{ trans_choice('web::seat.contacts', count($contacts)) }}
    </div>
  </div>

@stop
