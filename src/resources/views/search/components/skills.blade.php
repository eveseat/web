<div class="panel panel-default">
  <div class="panel-heading">
    <h3 class="panel-title">Characters Skills</h3>
  </div>
  <div class="panel-body">

    <table class="table compact table-condensed table-hover table-responsive"
           id="character-skills">
      <thead>
      <tr>
        <th>{{ trans_choice('web::seat.name', 1) }}</th>
        <th>{{ trans_choice('web::seat.corporation', 1) }}</th>
        <th>{{ trans_choice('web::seat.group', 1) }}</th>
        <th>{{ trans_choice('web::seat.type', 1) }}</th>
        <th>{{ trans('web::seat.level') }}</th>
      </tr>
      </thead>
    </table>

  </div>
</div>

@push('javascript')

<script>

    $(function () {
        $('table#character-skills').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('support.search.skills.data') }}',
            columns: [
                {data: 'characterName', name: 'characterName'},
                {data: 'corporationName', name: 'corporationName'},
                {data: 'groupName', name: 'groupName'},
                {data: 'typeName', name: 'typeName'},
                {data: 'level', name: 'level'},
            ],
            'fnDrawCallback': function () {
                $(document).ready(function () {
                    $('img').unveil(100);
                });
            },
            'search': {
                'search': '{{ $query }}'
            }
        });
    });

</script>

@endpush
