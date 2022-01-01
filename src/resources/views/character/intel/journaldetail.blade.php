@extends('web::character.intel.layouts.view', ['sub_viewname' => 'journal_detail', 'breadcrumb' => trans('web::seat.intel')])

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
      <span id="comparison-result"></span>
    </div>
  </div>

@stop

@push('javascript')

<script>

  $("select#standings-profile-id").select2({
    placeholder: "Select a profile"
  });

  $(document.body).on("change", "select#standings-profile-id", function () {

    // Flip on the loading indicator
    $("span#comparison-result").html('<i class="fa fa-cog fa fa-spin"></i>');

    // Shitty hack so we can replace the id. Muhaha.
    var url = "{{ route('seatcore::character.view.intel.ajax.standingscomparison', ['character' => $request->character, 'profile_id' => ':id']) }}";
    url = url.replace(':id', this.value);

    // Perform the AJAX request to get the comparison.
    $.ajax({
      type   : 'GET',
      url    : url,
      success: function (result) {
        $("span#comparison-result").html(result);
        $("img").unveil(100);
      }
    });
  });

</script>

@endpush
