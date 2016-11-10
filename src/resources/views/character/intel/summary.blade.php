@extends('web::character.intel.layouts.view', ['sub_viewname' => 'summary'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))

@inject('request', 'Illuminate\Http\Request')

@section('intel_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Top Wallet Journal Interactions</h3>
    </div>
    <div class="panel-body">

      <span id="journal_from" a-ajax-loaded="false">
        <i class="fa fa-cog fa fa-spin"></i> {{ trans('web::seat.loading_journal') }}</p>
      </span>

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Top Wallet Transaction Interactions</h3>
    </div>
    <div class="panel-body">

      <span id="transactions" a-ajax-loaded="false">
        <i class="fa fa-cog fa fa-spin"></i> {{ trans('web::seat.loading_transactions') }}</p>
      </span>

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Top Mail From</h3>
    </div>
    <div class="panel-body">

      <span id="mail_from" a-ajax-loaded="false">
        <i class="fa fa-cog fa fa-spin"></i> {{ trans('web::seat.loading_mail') }}</p>
      </span>

    </div>
  </div>

@stop

@section('javascript')

  <script>

    $(document).ready(function () {

      // Journal From Entries
      $.ajax({
        type: 'GET',
        url: "{{ route('character.view.intel.summary.ajax.journal', ['character_id' => $request->character_id]) }}",
        success: function (result) {
          $("span#journal_from").html(result);
          $("img").unveil(100);
        }
      });

      $.ajax({
        type: 'GET',
        url: "{{ route('character.view.intel.summary.ajax.transactions', ['character_id' => $request->character_id]) }}",
        success: function (result) {
          $("span#transactions").html(result);
          $("img").unveil(100);
        }
      });

      // Mail From Entries
      $.ajax({
        type: 'GET',
        url: "{{ route('character.view.intel.summary.ajax.mail', ['character_id' => $request->character_id]) }}",
        success: function (result) {
          $("span#mail_from").html(result);
          $("img").unveil(100);
        }
      });

    });

  </script>

@stop
