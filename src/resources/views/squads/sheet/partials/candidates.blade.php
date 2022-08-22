@if($squad->type == 'manual' && $squad->moderators->isNotEmpty())
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Candidates</h3>
            </div>
            <table data-src="{{ route('seatcore::squads.applications.index', $squad) }}" class="table card-table table-vcenter table-hover table-striped text-nowrap" id="candidates-table">
                <thead>
                <tr>
                    <th>{{ trans_choice('web::squads.name', 1) }}</th>
                    <th>{{ trans_choice('web::squads.character', 0) }}</th>
                    <th>{{ trans('web::squads.applied_at') }}</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        </div>
    </div>
@endif

@push('javascript')
<script>
    if (! DataTable.isDataTable('#candidates-table')) {
        let candidateTable = document.getElementById('candidates-table');
        window.LaravelDataTables["candidatesTableBuilder"] = new DataTable(candidateTable, {
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            ajax: {
                url: candidateTable.dataset.src,
                type: 'POST',
                headers: {
                    'X-HTTP-Method-Override': 'GET'
                }
            },
            columns: [
                {data: 'user.name', name: 'user.name'},
                {data: 'characters', name: 'characters'},
                {data: 'created_at', name: 'created_at'},
                {defaultContent: "", data: "action", name: "action", title: "Action", "orderable": false, "searchable": false}
            ],
            "drawCallback": function() {
                document.querySelectorAll('[data-bs-toggle=tooltip]').forEach(tooltip => {
                    new bootstrap.Tooltip(tooltip);
                });
            }
        });
    }
</script>
@endpush