@extends('web::character.intel.layouts.view', ['sub_viewname' => 'standings', 'breadcrumb' => trans('web::seat.intel')])

@section('page_header', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.intel'))

@inject('request', 'Illuminate\Http\Request')

@section('intel_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Standings Profile</h3>
    </div>
    <div class="card-body">

      <form role="form" action="#" method="post">
        {{ csrf_field() }}

        <div class="box-body">

          <div class="form-group">
            <select id="standings-profile-id" style="width: 100%;">
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

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">Interactions</h3>
    </div>
    <div class="card-body">

      <table class="table table-condensed table-hover table-striped" id="character-standings-interactions">
        <thead>
          <tr>
            <th>Interactions</th>
            <th>Character Name</th>
            <th>Character Corp</th>
            <th>Character Alliance</th>
            <th>Character Faction</th>
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
      {data: 'character.name', name: 'character.name'},
      {data: 'corporation.name', name: 'corporation.name'},
      {data: 'alliance.name', name: 'alliance.name'},
      {data: 'faction.name', name: 'faction.name'},
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
    var url = "{{ route('seatcore::character.view.intel.standingscomparison.data', ['character' => $request->character, 'profile_id' => ':id']) }}";
    url = url.replace(':id', this.value);

    // Load the data from the new URL and populate the DataTable.
    Table.ajax.url(url).load();

  });

</script>

@endpush
