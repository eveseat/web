@extends('web::layouts.character', ['viewname' => 'note', 'breadcrumb' => trans('web::seat.intel')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Notes</h3>
      <div class="card-tools">
        <div class="input-group input-group-sm">
          <!-- Button trigger modal -->
          <button type="button" data-bs-toggle="modal" data-bs-target="#note-create-modal"
                  data-object-type="{{ Seat\Eveapi\Models\Character\CharacterInfo::class }}"
                  data-object-id="{{ request()->character->character_id }}" class="btn btn-sm btn-success">
            <i class="fas fa-plus-square"></i>
            Add Note
          </button>
        </div>
      </div>
    </div>
    <div class="card-body">

      {!! $dataTable->table() !!}

    </div>
  </div>

  {{-- include the note creation modal --}}
  @include('web::common.notes.modals.create')

  {{-- include the note edit modal --}}
  @include('web::common.notes.modals.edit')

@stop

@push('javascript')

  {!! $dataTable->scripts() !!}

@endpush
