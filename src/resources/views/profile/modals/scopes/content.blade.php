<table class="table table-condensed table-hover table-striped">
  <tbody>
    @foreach($scopes as $scope)
      <tr>
        <td>{{ $scope }}</td>
      </tr>
    @endforeach
  </tbody>
</table>
