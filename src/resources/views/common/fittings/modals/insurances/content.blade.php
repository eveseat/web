<table class="table table-condensed table-striped">
  <thead>
  <tr>
    <th>Level</th>
    <th>Cost</th>
    <th>Payout</th>
    <th>Refunded</th>
    <th>Remaining</th>
  </tr>
  </thead>
  <tbody>
  @foreach($ship->insurances->sortBy('cost') as $insurance)
    <tr>
      <th>{{ $insurance->name }}</th>
      <td>{{ number($insurance->cost) }}</td>
      <td>{{ number($insurance->payout) }}</td>
      <td>{{ number($insurance->payout - $insurance->cost) }}</td>
      <td>{{ number($ship->price->adjusted_price - $insurance->payout + $insurance->cost) }}</td>
    </tr>
  @endforeach
  </tbody>
</table>