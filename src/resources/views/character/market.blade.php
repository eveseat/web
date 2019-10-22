@extends('web::character.layouts.view', ['viewname' => 'market', 'breadcrumb' => trans('web::seat.market')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.market'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">
        {{ trans('web::seat.market') }}
      </h3>
      <div class="card-tools">
        <a href="{{ route('tools.jobs.dispatch', ['character_id' => $request->character_id, 'job_name' => 'character.market']) }}" class="text-dark">
          <i class="fas fa-sync" data-toggle="tooltip" title="{{ trans('web::seat.update_market') }}"></i>
        </a>
      </div>
    </div>
    <div class="card-body">
      <div class="mb-3">
        <select multiple="multiple" id="dt-character-selector" class="form-control">
          @foreach($characters as $character)
            @if($character->id == $request->character_id)
              <option selected="selected" value="{{ $character->id }}">{{ $character->name }}</option>
            @else
              <option value="{{ $character->id }}">{{ $character->name }}</option>
            @endif
          @endforeach
        </select>
      </div>
      <div class="mb-3">
        <div class="btn-group d-flex">
          <button data-filter="pending" id="dt-filters-pending" class="btn btn-primary active dt-filters-status">
            <i class="fas fa-play"></i>
            Pending
          </button>
          <button data-filter="expired" id="dt-filters-expired" class="btn btn-danger dt-filters-status">
            <i class="fas fa-history"></i>
            Expired
          </button>
          <button data-filter="completed" id="dt-filters-completed" class="btn btn-secondary dt-filters-status">
            <i class="fas fa-check"></i>
            Completed
          </button>
        </div>
      </div>
      <div class="mb-3">
        <div class="btn-group d-flex">
          <button data-filter="0" id="dt-filters-sell" class="btn btn-light active dt-filters-type">
            <i class="fas fa-caret-up"></i>
            Sell Orders
          </button>
          <button data-filter="1" id="dt-filters-buy" class="btn btn-light active dt-filters-type">
            <i class="fas fa-caret-down"></i>
            Buy Orders
          </button>
        </div>
      </div>

      {!! $dataTable->table() !!}
    </div>
  </div>

@stop

@push('javascript')
  {!! $dataTable->scripts() !!}

  <script>
    $(document).ready(function() {
        $('#dt-character-selector')
            .select2()
            .on('change', function () {
                window.LaravelDataTables['dataTableBuilder'].ajax.reload();
            });

        $('#dt-filters-pending, #dt-filters-expired, #dt-filters-completed, #dt-filters-sell, #dt-filters-buy')
            .on('click', function () {
                $(this).hasClass('active') ? $(this).removeClass('active') : $(this).addClass('active');
                window.LaravelDataTables['dataTableBuilder'].ajax.reload();
            });
    });
  </script>
@endpush
