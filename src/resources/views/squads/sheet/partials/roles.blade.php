<div class="col-12">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Roles</h3>
        </div>
        <table data-src="{{ route('seatcore::squads.roles.show', $squad) }}" class="table table-striped table-hover" id="roles-table">
            <thead>
            <tr>
                <th>Name</th>
                <th>Description</th>
                <th>Permissions</th>
                <th>Action</th>
            </tr>
            </thead>
        </table>
        <div class="card-footer">
            <form method="post" action="{{ route('seatcore::squads.roles.store', $squad) }}" data-table="rolesTableBuilder" id="squad-role-form">
                {!! csrf_field() !!}
                <div class="row justify-content-end">
                    <div class="col-4">
                        <div class="input-group input-group-sm">
                            <label for="squad-role" class="sr-only">Role</label>
                            <div class="input-group mb-2">
                                <select name="role_id" placeholder="Search a role to add to this Squad..." class="form-control input-sm" id="squad-role"></select>
                                <button class="btn btn-sm btn-success d-sm-inline-block" type="submit">
                                    <i class="fas fa-plus"></i> Add
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@push('javascript')
    <script>
        if (! DataTable.isDataTable('#roles-table')) {
            let rolesTable = document.getElementById('roles-table');
            window.LaravelDataTables["rolesTableBuilder"] = new DataTable(rolesTable, {
                processing: true,
                serverSide: true,
                order: [[0, 'desc']],
                ajax: {
                    url: rolesTable.dataset.src,
                    type: 'POST',
                    headers: {
                        'X-HTTP-Method-Override': 'GET'
                    }
                },
                columns: [
                    {data: 'title', name: 'title'},
                    {data: 'description', name: 'description'},
                    {data: 'permissions', name: 'permissions'},
                    {defaultContent: "", data: "action", name: "action", title: "Action", "orderable": false, "searchable": false}
                ]
            });
        }
    </script>
@endpush