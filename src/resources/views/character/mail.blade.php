@extends('web::character.layouts.view', ['viewname' => 'mail'])

@section('title', ucfirst(trans_choice('web::character.character', 1)) . ' Mail')
@section('page_header', ucfirst(trans_choice('web::character.character', 1)) . ' Mail')

@section('character_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Mail</h3>
    </div>
    <div class="panel-body">

      <table class="table table-condensed table-hover table-responsive">
        <tbody>
        <tr>
          <th>Sent</th>
          <th>From</th>
          <th>Title</th>
          <th>To</th>
          <th></th>
        </tr>

        @foreach($mail as $message)

          <tr>
            <td>
              <span data-toggle="tooltip" title="" data-original-title="{{ $message->sentDate }}">
                {{ human_diff($message->sentDate) }}
              </span>
            </td>
            <td>
              {!! img('auto', $message->senderID, 32, ['class' => 'img-circle eve-icon small-icon']) !!}
              {{ $message->senderName }}
            </td>
            <td>
              <i class="fa fa-comment" data-toggle="popover" data-placement="top" title=""
                 data-trigger="hover" data-content="{{ str_limit(strip_tags($message->body), 200, '...') }}"></i>
              {{ str_limit($message->title, 50, '...') }}
            </td>
            <td>
              @if($message->toCorpOrAllianceID != 0)
                <span data-toggle="tooltip" data-placement="top" title=""
                      data-original-title="Corporations / Alliances">
                  <span class="label label-primary">{{ count(explode(',', $message->toCorpOrAllianceID)) }}</span>
                </span>
              @endif

              @if($message->toCharacterIDs)
                <span data-toggle="tooltip" data-placement="top" title=""
                      data-original-title="Characters">
                  <span class="label label-info">{{ count(explode(',', $message->toCharacterIDs)) }}</span>
                </span>
              @endif

              @if($message->toListID)
                <span data-toggle="tooltip" data-placement="top" title=""
                      data-original-title="Mailing Lists">
                  <span class="label label-success">{{ count(explode(',', $message->toListID)) }}</span>
                </span>
              @endif
            </td>
            <td><a href="#" class="btn btn-xs btn-primary"><i class="fa fa-envelope"></i> Read</a></td>
          </tr>

        @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
