@extends('web::character.intel.layouts.view', ['sub_viewname' => 'standings'])

@section('title', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))
@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))

@inject('request', 'Illuminate\Http\Request')

@section('intel_content')

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Standings Profile</h3>
    </div>
    <div class="panel-body">

      <form role="form" action="#" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <select id="standings-profile-id" style="width: 100%">
              <option></option> <!-- blank option for select2 placeholder. quirky -_- -->
              @foreach($profiles as $profile)
                <option value="{{ $profile->id }}">{{ $profile->name }}</option>
              @endforeach
            </select>
          </div>

        </div><!-- /.box-body -->

      </form>

    </div>
  </div>

  <div class="panel panel-default">
    <div class="panel-heading">
      <h3 class="panel-title">Interactions</h3>
    </div>
    <div class="panel-body">

      <table class="table compact table-condensed table-hover table-responsive"
             id="character-standings-interactions">
        <thead>
        <tr>
          <th>Interactions</th>
          <th>Character Name</th>
          <th>Character Corp</th>
          <th>Character Alliance</th>
          <th>Standing</th>
        </tr>
        </thead>
      </table>

    </div>
  </div>

@stop

@push('javascript')

<script>

  // Profile selector
  $("select#standings-profile-id").select2({
    placeholder: "Select a profile"
  });

  // Prepare an empty DataTable to use.
  var Table = $('table#character-standings-interactions').DataTable({
    processing      : true,
    serverSide      : true,
    columns         : [
      {data: 'total', name: 'total', searchable: false},
      {data: 'characterName', name: 'characterName'},
      {data: 'corporationName', name: 'corporationName'},
      {data: 'allianceName', name: 'allianceName'},
      {data: 'standing', name: 'standing'}
    ],
    dom: '<"row"<"col-sm-6"l><"col-sm-6"f>><"row"<"col-sm-6"i><"col-sm-6"p>>rt<"row"<"col-sm-6"i><"col-sm-6"p>><"row"<"col-sm-6"l><"col-sm-6"f>>',
    'fnDrawCallback': function () {
      $(document).ready(function () {
        $('img').unveil(100);
        ids_to_names();
      });
    },
    'iDeferLoading' : 0
  });

  // Table Loaders. This should run once a profile is chosen.
  $(document.body).on("change", "select#standings-profile-id", function () {

    // Shitty hack so we can replace the id. Muhaha.
    var url = "{{ route('character.view.intel.standingscomparison.data', ['character_id' => $request->character_id, 'profile_id' => ':id']) }}";
    url = url.replace(':id', this.value);

    // Load the data from the new URL and populate the DataTable.
    Table.ajax.url(url).load();

  });

</script>

@endpush
