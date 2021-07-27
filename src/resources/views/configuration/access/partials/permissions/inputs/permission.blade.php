<div class="form-check mb-3">
  @if($is_granted)
    <input type="checkbox" name="permissions[{{ $scope }}.{{ $ability }}]" form="role-form" data-target="#scope-{{ $scope }}-counter" id="permission-{{ $scope }}-{{ $ability }}" class="form-check-input" checked="checked" />
  @else
    <input type="checkbox" name="permissions[{{ $scope }}.{{ $ability }}]" form="role-form" data-target="#scope-{{ $scope }}-counter" id="permission-{{ $scope }}-{{ $ability }}" class="form-check-input" />
  @endif

  <label for="permission-{{ $scope }}-{{ $ability }}" class="form-check-label">
    @switch($division)
      @case('military')
        <span class="badge badge-danger" data-toggle="tooltip" data-title="{{ trans('web::permissions.military_division') }}">
          <i class="fas fa-rocket"></i>
        </span>
        @break
      @case('assets')
        <span class="badge badge-warning" data-toggle="tooltip" data-title="{{ trans('web::permissions.assets_division') }}">
          <i class="fas fa-cubes"></i>
        </span>
        @break
      @case('financial')
        <span class="badge badge-success" data-toggle="tooltip" data-title="{{ trans('web::permissions.financial_division') }}">
          <i class="far fa-money-bill-alt"></i>
        </span>
        @break
      @case('industrial')
        <span class="badge badge-primary" data-toggle="tooltip" data-title="{{ trans('web::permissions.industrial_division') }}">
          <i class="fas fa-industry"></i>
        </span>
        @break
      @default
        <span class="badge badge-default" data-toggle="tooltip" data-title="{{ trans('web::permissions.no_division') }}">
          <i class="fas fa-slash"></i>
        </span>
    @endswitch
    {{ $label }}
  </label>

  @if(in_array($scope, ['character', 'corporation', 'alliance']))
    @include('web::configuration.access.partials.permissions.buttons.filters', ['role_id' => $role_id, 'scope' => $scope, 'class' => $filters ? 'btn-warning' : 'btn-default', 'disabled' => ! $is_granted])
  @endif

  @if($description)
  <i class="form-text text-muted">{{ trans($description) }}</i>
  @endif

  @if(in_array($scope, ['character', 'corporation', 'alliance']))
    @if($filters)
      <input type="hidden" name="filters[{{ $scope }}.{{ $ability }}]" value="{{ $filters }}" form="role-form" />
    @else
      <input type="hidden" name="filters[{{ $scope }}.{{ $ability }}]" value="{}" form="role-form" />
    @endif
  @endif
</div>
