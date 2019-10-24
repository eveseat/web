<div role="tabpanel" aria-labelledby="nav-permissions" id="tab-permissions" class="tab-pane fade show active">
  <div class="panel-group" role="tablist" aria-multiselectable="true" id="permissions-accordion">
    <div class="row">
      <div class="col-md-4">
        <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
          @foreach(collect(config('seat.permissions'))->sortKeys() as $scope => $permissions)
            <a class="nav-link mt-0 border-top-0 @if($loop->first) active show @endif" data-toggle="pill" href="#{{ $scope }}-permissions-content" role="tab" aria-controls="{{ $scope }}-permissions-content" aria-selected="{{ $loop->first ? 'active' : 'false' }}">
              {{ ucfirst($scope) }}
              <span class="active-permissions-counter">(<b id="scope-{{ $scope }}-counter">0</b>/{{ count($permissions) }})</span>
              <span class="float-right">
                <button type="button" data-target="#{{ $scope }}-permissions-content" class="btn btn-default btn-sm check-all-permissions">
                  <i class="fas fa-check-double"></i>
                  {{ trans('web::permissions.permissions_check_all') }}
                </button>
              </span>
            </a>
          @endforeach
        </div>
      </div>
      <div class="col-md-8">
        <div class="tab-content">
            @foreach(collect(config('seat.permissions'))->sortKeys() as $scope => $permissions)
              <div id="{{ $scope }}-permissions-content" class="tab-pane permissions-tab p-3 fade @if($loop->first) active show @endif"
                   role="tabpanel" aria-labelledby="{{ $scope }}-permissions">
                @foreach($permissions as $ability => $permission)
                  @include('web::configuration.access.partials.permissions.inputs.permission', [
                    'scope'       => $scope,
                    'ability'     => is_array($permission) ? $ability : $permission,
                    'label'       => trans($permission['label']),
                    'description' => array_key_exists('description', $permission) ? trans($permission['description']) : '',
                    'division'    => array_key_exists('division', $permission) ? $permission['division'] : '',
                    'filters'     => $role->permissions->where('title', sprintf('%s.%s', $scope, $ability))->first() ? $role->permissions->where('title', sprintf('%s.%s', $scope, $ability))->first()->pivot->filters : null,
                    'is_granted'  => in_array(sprintf('%s.%s', $scope, $ability), $role_permissions),
                    'role_id'     => $role->id
                  ])
                @endforeach
              </div>
            @endforeach
        </div>
      </div>
    </div>
  </div>
</div>