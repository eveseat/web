@extends('web::corporation.layouts.view', ['viewname' => 'contacts'])

@section('title', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.contacts'))
@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans('web::seat.contacts'))

@section('corporation_content')

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
            <td class="no-sort"></td>
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
            <td>
              @if (!in_array($contact->contactTypeID, [2, 16159]))
                <a href="http://eveboard.com/pilot/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/eveboard.png') }}">
                </a>
              @endif
              @if ($contact->contactTypeID == 2)
                <a href="https://gate.eveonline.com/Corporation/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evegate.png') }}">
                </a>
              @elseif ($contact->contactTypeID == 16159)
                <a href="https://gate.eveonline.com/Alliance/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evegate.png') }}">
                </a>
              @else
                <a href="https://gate.eveonline.com/Profile/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evegate.png') }}">
                </a>
              @endif
              @if ($contact->contactTypeID == 2)
                <a href="https://eve-kill.net/?a=corp_detail&crp_external_id={{ $contact->contactID }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evekill.png') }}">
                </a>
              @elseif ($contact->contactTypeID == 16159)
                <a href="https://eve-kill.net/?a=alliance_detail&all_external_id={{ $contact->contactID }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evekill.png') }}">
                </a>
              @else
                <a href="https://eve-kill.net/?a=pilot_detail&plt_external_id={{ $contact->contactID }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evekill.png') }}">
                </a>
              @endif
              @if (!in_array($contact->contactTypeID, [2, 16159]))
                <a href="http://eve-search.com/search/author/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evesearch.png') }}">
                </a>
              @endif
              @if ($contact->contactTypeID == 2)
                <a href="http://evewho.com/corp/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evewho.png') }}">
                </a>
              @elseif ($contact->contactTypeID == 16159)
                <a href="http://evewho.com/alli/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evewho.png') }}">
                </a>
              @else
                <a href="http://evewho.com/pilot/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evewho.png') }}">
                </a>
              @endif
              @if ($contact->contactTypeID == 2)
                <a href="https://zkillboard.com/corporation/{{ $contact->contactID }}"
                   target="_blank">
                  <img src="{{ asset('web/img/zkillboard.png') }}">
                </a>
              @elseif ($contact->contactTypeID == 16159)
                <a href="https://zkillboard.com/alliance/{{ $contact->contactID }}"
                   target="_blank">
                  <img src="{{ asset('web/img/zkillboard.png') }}">
                </a>
              @else
                <a href="https://zkillboard.com/character/{{ $contact->contactID }}"
                   target="_blank">
                  <img src="{{ asset('web/img/zkillboard.png') }}">
                </a>
              @endif
              @if ($contact->contactTypeID != 16159)
                <a href="http://eve-hunt.net/hunt/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evehunt.png') }}">
                </a>
              @endif
            </td>
          </tr>
        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
