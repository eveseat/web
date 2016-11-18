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

  <!-- Create Modal -->
  <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="createModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
          <h4 class="modal-title" id="createModalLabel">
            Note
          </h4>
        </div>
        <div class="modal-body">

          <form role="form" action="{{ route('character.view.intel.notes.new', ['character_id' => $request->character_id]) }}" method="post">
            {{ csrf_field() }}

            <div class="box-body">

              <div class="form-group">
                <label for="text">Title</label>
                <input type="text" name="title" class="form-control" id="title" value=""
                       placeholder="Title...">
              </div>

              <div class="form-group">
                <label>Note</label>
                <textarea class="form-control" rows="15" name="note" placeholder="Note..."></textarea>
              </div>

            </div><!-- /.box-body -->

            <div class="box-footer">
              <button type="submit" class="btn btn-primary pull-right">
                Add
              </button>
            </div>
          </form>

        </div>
      </div>
    </div>
  </div>

@stop

@push('javascript')

<script>

  $(function () {
    $('table#character-notes').DataTable({
      processing: true,
      serverSide: true,
      ajax: '{{ route('character.view.intel.notes.data', ['character_id' => $request->character_id]) }}',
      columns: [
        {data: 'created_at', name: 'created_at', render: human_readable},
        {data: 'title', name: 'title'},
        {data: 'note', name: 'note'},
        {data: 'actions', name: 'actions', searchable: false},
      ]
    });
  });

</script>

@endpush
