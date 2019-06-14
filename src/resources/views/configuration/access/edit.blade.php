@extends('web::layouts.grids.4-8')

@section('title', trans('web::seat.edit_role'))
@section('page_header', trans('web::seat.edit_role'))
@section('page_description', $role->title)

@section('left')
  @include('web::configuration.access.partials.card')
@stop

@section('right')
  <div class="nav-tabs-custom">
    <ul class="nav nav-tabs">
      <li class="active" id="nav-permissions">
        <a href="#tab-permissions" data-toggle="tab">
          <span class="badge">{{ count($role_permissions) }}</span> {{ trans_choice('web::seat.permission', 0) }}
        </a>
      </li>
      <li id="nav-members">
        <a href="#tab-members" data-toggle="tab">
          <span class="badge">{{ $role->groups->where('id', '<>', 1)->count() }}</span> {{ trans_choice('web::seat.member', 0) }}
        </a>
      </li>
    </ul>
    <div class="tab-content">
      @include('web::configuration.access.partials.permissions')
      @include('web::configuration.access.partials.members')
    </div>
  </div>
  @include('web::configuration.access.partials.modals.filters')
  @include('web::configuration.access.partials.modals.members')
@stop

@push('javascript')

  @include('web::includes.javascript.id-to-name')
  @include('web::configuration.access.includes.scripts.filters')
  @include('web::configuration.access.includes.scripts.members')

@endpush
