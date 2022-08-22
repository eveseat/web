<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Members</h3>
        </div>
        @can('squads.show_members', $squad)
            <table data-src="{{ route('seatcore::squads.members.index', $squad) }}" class="table card-table table-vcenter table-hover table-striped text-nowrap" id="members-table">
                <thead>
                <tr>
                    <th>{{ trans_choice('web::squads.name', 1) }}</th>
                    <th>{{ trans_choice('web::squads.character', 0) }}</th>
                    <th>{{ trans('web::squads.member_since') }}</th>
                    <th>Action</th>
                </tr>
                </thead>
            </table>
        @endcan
        @cannot('squads.show_members', $squad)
            <div class="card-body">
                <p class="text-center">You are not member of that squad.</p>
            </div>
        @endcannot
        @can('squads.manage_members', $squad)
            <div class="card-footer">
                <form method="post" action="{{ route('seatcore::squads.members.store', $squad) }}" data-table="dataTableBuilder" id="squad-member-form">
                    {!! csrf_field() !!}
                    <div class="row justify-content-end">
                        <div class="col-4">
                            <div class="input-group input-group-sm">
                                <label for="squad-member" class="sr-only">User</label>
                                <div class="input-group mb-2">
                                    <select name="user_id" placeholder="Search an user to add to this Squad..." class="form-control input-sm" id="squad-member"></select>
                                    <button class="btn btn-sm btn-success d-sm-inline-block" type="submit">
                                        <i class="fas fa-plus"></i> Add
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        @endcan
    </div>
</div>

@push('javascript')
    <script>
        if (! DataTable.isDataTable('#members-table')) {
            let membersTable = document.getElementById('members-table');
            window.LaravelDataTables["membersTableBuilder"] = new DataTable(membersTable, {
                processing: true,
                serverSide: true,
                order: [[0, 'desc']],
                ajax: {
                    url: membersTable.dataset.src,
                    type: 'POST',
                    headers: {
                        'X-HTTP-Method-Override': 'GET'
                    }
                },
                columns: [
                    {data: "name", name: "name", title: "Name", "orderable": true, "searchable": true},
                    {data: "characters", name: "characters", title: "Characters", "orderable": true, "searchable": true},
                    {data: "member_since", name: "member_since", title: "Member Since", "orderable": false, "searchable": false},
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