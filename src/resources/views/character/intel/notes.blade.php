@extends('web::character.intel.layouts.view', ['sub_viewname' => 'note'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))

@inject('request', 'Illuminate\Http\Request')

@section('intel_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">
        Notes
        <!-- Button trigger modal -->
        <span class="pull-right">
          <a type="button" data-toggle="modal" data-target="#createModal">
            Add Note
          </a>
        </span>
      </h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-notes">
        <thead>
        <tr>
          <th>Created</th>
          <th>Title</th>
          <th>Note</th>
          <th></th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

  {{-- include the note creation modal --}}
  @include('web::includes.modals.createnote',
    ['post_route' => route('character.view.intel.notes.new', ['character_id' => $request->character_id])])

  {{-- include the note edit modal --}}
  @include('web::includes.modals.editnote',
    ['post_route' => route('character.view.intel.notes.update', ['character_id' => $request->character_id])])

@stop

@push('javascript')

<script>

  $(function () {
    $('table#character-notes').DataTable({
      processing: true,
      serverSide: true,
      ajax      : '{{ route('character.view.intel.notes.data', ['character_id' => $request->character_id]) }}',
      columns   : [
        {data: 'created_at', name: 'created_at', render: human_readable},
        {data: 'title', name: 'title'},
        {data: 'note', name: 'note'},
        {data: 'actions', name: 'actions', searchable: false}
      ],
      dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>'
    });
  });

</script>

@endpush
