<table class="table table-condensed table-hover table-striped">
  <tbody>
    @foreach($scopes as $scope)
      <tr>
        <td>{{ $scope }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
<p>
    <b>{{ trans("web::seat.scopes_profile") }}</b>:
    @if($profile_name !== null)
        <span class="text-success">
            {{ $profile_name }}
        </span>
    @else
        <span class="text-danger">
            {{ trans("web::seat.unknown_scopes_profile") }}
        </span>
    @endif
</p>