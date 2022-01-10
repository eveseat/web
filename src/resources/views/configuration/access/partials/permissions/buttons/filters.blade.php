<button type="button" data-roleid="{{ $role_id }}" data-permission="{{ $scope }}.{{ $ability }}" data-bs-toggle="modal" data-bs-target="#permission-modal" class="btn btn-xs {{ $class }}" @if($disabled) disabled="disabled" @endif >
  <i class="fas fa-filter"></i>
  {{ trans('web::permissions.limits') }}
</button>