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
          <th class="no-sort"></th>
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
              {!! img($contact->contact_type, $contact->contact_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
                <span rel="id-to-name">{{ $contact->contact_id }}</span>
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
                return $item->label_id & $contact->label_id; })->implode('label_name', ', ') }}
            </td>
            <td>
              @if (!in_array($contact->contact_type, ['corporation', 'alliance']))
                <a href="http://eveboard.com/pilot/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/eveboard.png') }}">
                </a>
              @endif
              @if ($contact->contact_type == 'corporation')
                <a href="https://gate.eveonline.com/Corporation/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evegate.png') }}">
                </a>
              @elseif ($contact->contact_type == 'alliance')
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
              @if ($contact->contact_type == 'corporation')
                <a href="https://eve-kill.net/?a=corp_detail&crp_external_id={{ $contact->contact_id }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evekill.png') }}">
                </a>
              @elseif ($contact->contact_type == 'alliance')
                <a href="https://eve-kill.net/?a=alliance_detail&all_external_id={{ $contact->contact_id }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evekill.png') }}">
                </a>
              @else
                <a href="https://eve-kill.net/?a=pilot_detail&plt_external_id={{ $contact->contact_id }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evekill.png') }}">
                </a>
              @endif
              @if (!in_array($contact->contact_type, ['corporation', 'alliance']))
                <a href="http://eve-search.com/search/author/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evesearch.png') }}">
                </a>
              @endif
              @if ($contact->contact_type == 'corporation')
                <a href="http://evewho.com/corp/{{ $contact->contactName }}"
                   target="_blank">
                  <img src="{{ asset('web/img/evewho.png') }}">
                </a>
              @elseif ($contact->contact_type == 'alliance')
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
              @if ($contact->contact_type == 'corporation')
                <a href="https://zkillboard.com/corporation/{{ $contact->contact_id }}"
                   target="_blank">
                  <img src="{{ asset('web/img/zkillboard.png') }}">
                </a>
              @elseif ($contact->contact_type == 'alliance')
                <a href="https://zkillboard.com/alliance/{{ $contact->contact_id }}"
                   target="_blank">
                  <img src="{{ asset('web/img/zkillboard.png') }}">
                </a>
              @else
                <a href="https://zkillboard.com/character/{{ $contact->contact_id }}"
                   target="_blank">
                  <img src="{{ asset('web/img/zkillboard.png') }}">
                </a>
              @endif
              <a href="http://eve-prism.com/?view={{ $contact->contact_type }}&name={{ $contact->contact_id }}"
                target="_blank">
                <img src="{{ asset('web/img/eve-prism.png') }}" />
              </a>
            </td>
          </tr>
        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
