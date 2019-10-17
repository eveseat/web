@extends('web::corporation.security.layouts.view', ['sub_viewname' => 'roles', 'breadcrumb' => trans_choice('web::seat.role', 2)])

@section('page_header', trans_choice('web::seat.corporation', 1) . ' ' . trans_choice('web::seat.role', 2))

@section('security_content')

  <div class="card">
    <div class="card-header">
      <h3 class="card-title">{{ trans_choice('web::seat.role', 2) }}</h3>
    </div>
    <div class="card-body p-0">

      <table class="table table-condensed table-hover">
        <tbody>

          @foreach($security as $entry)

            <tr class="bg-light">
              <td>
                <b>
                  @include('web::partials.character', ['character' => $entry->character])
                </b>
                <i class="text-muted float-right">
                  {{ $entry->roles->count() }}
                  {{ trans_choice('web::seat.role', $entry->roles->count()) }}
                </i>
              </td>
            </tr>

            <tr>
              <td>{{ $entry->roles->map(function($role) { return str_replace('_', ' ', $role->role); })->implode(', ') }}</td>
            </tr>

          @endforeach

        </tbody>
      </table>

    </div>
  </div>

@stop
