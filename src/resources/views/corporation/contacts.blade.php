@extends('web::corporation.layouts.view', ['viewname' => 'contacts', 'breadcrumb' => trans('web::seat.contacts')])

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
          <th>{{ trans_choice('web::seat.type', 1) }}</th>
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
              {!! img('auto', $contact->contact_id, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              <span class="id-to-name" data-id="{{$contact->contact_id}}">{{ $contact->contact_id }}</span>
            </td>
            <td>{{ ucfirst($contact->contact_type) }}</td>
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
              {{ $labels->whereIn('label_id', $contact->label_ids)->implode('label_name', ', ') }}
            </td>
            <td>
              @if (!in_array($contact->contact_type, ['corporation', 'alliance']))
                <a href="http://eveskillboard.com/pilot/{{ $contact->contact_id }}"
                   target="_blank" class="id-to-name" data-id="{{$contact->contact_id}}">
                  <img src="{{ asset('web/img/eveskillboard.png') }}">
                </a>
              @endif
              @if (!in_array($contact->contact_type, ['corporation', 'alliance']))
                <a href="http://eve-search.com/search/author/{{ $contact->contact_id }}"
                   target="_blank" class="id-to-name" data-id="{{$contact->contact_id}}">
                  <img src="{{ asset('web/img/evesearch.png') }}">
                </a>
              @endif
              @if ($contact->contact_type == 'corporation')
                <a href="http://evewho.com/corp/{{ $contact->contact_id }}"
                   target="_blank" class="id-to-name" data-id="{{$contact->contact_id}}">
                  <img src="{{ asset('web/img/evewho.png') }}">
                </a>
              @elseif ($contact->contact_type == 'alliance')
                <a href="http://evewho.com/alli/{{ $contact->contact_id }}"
                   target="_blank" class="id-to-name" data-id="{{$contact->contact_id}}">
                  <img src="{{ asset('web/img/evewho.png') }}">
                </a>
              @else
                <a href="http://evewho.com/pilot/{{ $contact->contact_id }}"
                   target="_blank" class="id-to-name" data-id="{{$contact->contact_id}}">
                  <img src="{{ asset('web/img/evewho.png') }}">
                </a>
              @endif
              @if (in_array($contact->contact_type, ['corporation', 'alliance', 'character']))
                <a href="https://zkillboard.com/{{ $contact->contact_type }}/{{ $contact->contact_id }}"
                   target="_blank">
                  <img src="{{ asset('web/img/zkillboard.png') }}">
                </a>
              @endif
              <a href="http://eve-prism.com/?view={{ $contact->contact_type }}&name={{ $contact->contact_id }}"
                 target="_blank" class="id-to-name" data-id="{{$contact->contact_id}}">
                  <img src="{{ asset('web/img/eve-prism.png') }}"/>
              </a>
            </td>
          </tr>
        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
