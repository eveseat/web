@extends('web::layouts.character', ['viewname' => 'monitoring', 'breadcrumb' => trans('web::seat.monitoring')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.monitoring'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ trans('web::seat.monitoring') }}</h3>
            <div class="card-actions">
                <input type="text" placeholder="Search for a job..." id="jobs-search" class="form-control" />
            </div>
        </div>
        <div class="card-body">
            <ul class="list-group list-group-horizontal row">
                @foreach($entries as $entry)
                    <li class="list-group-item d-flex justify-content-between align-items-start border-0 col-12 col-xl-6 col-xxl-3 job-entry" data-job-class="{{ $entry->job_class }}" data-job-name="{{ $entry->job_display_name }}">
                        <div class="ms-2 me-auto">
                            <div class="fw-bold">{{ $entry->job_display_name }}</div>
                            <small class="text-muted">{{ human_diff($entry->updated_at) }}</small>
                        </div>
                        <span @class(['badge', 'bg-success' => $entry->status == 'completed', 'bg-danger' => $entry->status == 'failed', 'bg-warning' => $entry->status == 'running', 'bg-secondary' => $entry->status == 'queued'])>{{ $entry->status }}</span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

@stop

@push('javascript')
    <script>
        document.getElementById('jobs-search').addEventListener('keyup', function (e) {
            let searchedValue = e.target.value.replace(/["\\]/g, '\\$&');

            if (searchedValue === '')
            {
                document.querySelectorAll('.job-entry.d-none').forEach((node) => {
                    node.classList.remove('d-none');
                });
            } else {
                document.querySelectorAll('.job-entry:not([data-job-class*="' + searchedValue + '" i]), .job-entry:not([data-job-name*="' + searchedValue + '" i])').forEach((node) => {
                    node.classList.add('d-none');
                });

                document.querySelectorAll('.job-entry[data-job-class*="' + searchedValue + '" i], .job-entry[data-job-name*="' + searchedValue + '" i]').forEach((node) => {
                    node.classList.remove('d-none');
                });
            }
        });
    </script>
@endpush
