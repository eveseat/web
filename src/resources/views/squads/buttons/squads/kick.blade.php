<div class="text-right">
  @can('squads.kick', [request()->squad, $row])
    @if($row->id != auth()->user()->id)
      <form method="post" action="{{ route('seatcore::squads.members.kick', ['squad' => request()->squad, 'member' => $row]) }}">
        {!! csrf_field() !!}
        {!! method_field('DELETE') !!}
        <button type="submit" data-seat-entity="member" class="btn btn-danger btn-sm d-sm-inline-block confirmdelete">
          <i class="fas fa-crosshairs"></i> {{ trans('web::squads.kick') }}
        </button>
      </form>
    @endif
  @endcan
</div>
