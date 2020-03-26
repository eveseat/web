@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.edit_role'))
@section('page_header', trans('web::seat.edit_role'))
@section('page_description', $role->title)

@section('left')
  @include('web::configuration.access.partials.card')
@stop

@section('right')
  <div class="card card-gray card-outline card-tabs">
    <div class="card-header p-0 border-bottom-0">
      <ul class="nav nav-tabs" role="tablist">
        <li id="nav-permissions" class="nav-item">
          <a href="#tab-permissions" role="tab" data-toggle="pill" aria-controls="tab-members" aria-selected="true" class="nav-link active">
            <span class="badge badge-secondary">{{ count($role_permissions) }}</span> {{ trans_choice('web::seat.permission', 0) }}
          </a>
        </li>
        <li id="nav-members" class="nav-item">
          <a href="#tab-members" role="tab" data-toggle="pill" aria-controls="tab-permissions" aria-selected="false" class="nav-link">
            <span class="badge badge-secondary">{{ $role->users->where('id', '<>', 1)->count() }}</span> {{ trans_choice('web::seat.member', 0) }}
          </a>
        </li>
      </ul>
    </div>
    <div class="card-body">
      <div class="tab-content p-3">
        @include('web::configuration.access.partials.permissions')
        @include('web::configuration.access.partials.members')
      </div>

      @include('web::configuration.access.partials.modals.filters')
      @include('web::configuration.access.partials.modals.members')
    </div>
  </div>
@stop

@push('javascript')

  @include('web::includes.javascript.id-to-name')
  @include('web::configuration.access.includes.scripts.filters')
  @include('web::configuration.access.includes.scripts.members')

@endpush
