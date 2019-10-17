<div class="form-check mb-3">
  @if($is_granted)
    <input type="checkbox" name="permissions[{{ $scope }}.{{ $ability }}]" form="role-form" data-target="#scope-{{ $scope }}-counter" id="permission-{{ $scope }}-{{ $ability }}" class="form-check-input" checked="checked" />
  @else
    <input type="checkbox" name="permissions[{{ $scope }}.{{ $ability }}]" form="role-form" data-target="#scope-{{ $scope }}-counter" id="permission-{{ $scope }}-{{ $ability }}" class="form-check-input" />
  @endif

  <label for="permission-{{ $scope }}-{{ $ability }}" class="form-check-label">{{ $label }}</label>

  @if(in_array($scope, ['character', 'corporation']))
    @include('web::configuration.access.partials.permissions.buttons.filters', ['role_id' => $role_id, 'scope' => $scope, 'class' => $filters ? 'btn-warning' : 'btn-default', 'disabled' => ! $is_granted])
  @endif

  @if($description)
  <i class="form-text text-muted">{{ trans($description) }}</i>
  @endif

  @if(in_array($scope, ['character', 'corporation']))
    @if($filters)
      <input type="hidden" name="filters[{{ $scope }}.{{ $ability }}]" value="{{ $filters }}" form="role-form" />
    @else
      <input type="hidden" name="filters[{{ $scope }}.{{ $ability }}]" value="{}" form="role-form" />
    @endif
  @endif
</div>
