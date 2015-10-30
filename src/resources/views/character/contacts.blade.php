@extends('web::character.layouts.view', ['viewname' => 'contacts'])

@section('title', ucfirst(trans_choice('web::character.character', 1)) . ' Contacts')
@section('page_header', ucfirst(trans_choice('web::character.character', 1)) . ' Contacts')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Contacts</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Name</th>
          <th>Standing</th>
          <th>Labels</th>
        </tr>

        <tr>
          <th class="text-center text-green" colspan="3">
            Positive Standings
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
            Neutral Standings
          </th>
        </tr>

        @foreach($contacts->filter(function($item) { return $item->standing == 0; }) as $contact)

          <tr>
            <td>
              {!! img('auto', $contact->contactID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $contact->contactName }}
            </td>
            <td>{{ $contact->standing }}</td>
            <td>{{ $contact->name }}</td>
          </tr>

        @endforeach

        <tr>
          <th class="text-center text-red" colspan="3">
            Negative Standings
          </th>
        </tr>

        @foreach($contacts->filter(function($item) { return $item->standing < 0; }) as $contact)

          <tr class="danger">
            <td>
              {!! img('auto', $contact->contactID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $contact->contactName }}
            </td>
            <td>{{ $contact->standing }}</td>
            <td>{{ $contact->name }}</td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
    <div class="panel-footer">
      {{ count($contacts) }} contacts
    </div>
  </div>

@stop
