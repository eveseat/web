<div role="tabpanel" aria-labelledby="nav-permissions" id="tab-permissions" class="tab-pane fade show active">
  <div class="panel-group" role="tablist" aria-multiselectable="true" id="permissions-accordion">
    <div class="row">
      <div class="col-md-4">
        <div class="nav flex-column nav-pills" role="tablist" aria-orientation="vertical">
          @foreach(collect(config('web.permissions'))->mapToGroups(function ($item, $key) {
            if (is_int($key)) return ['global' => $item];
            return [$key => $item];
          })->map(function ($item) {
            if(is_array($item->first())) return $item->first();
            return $item;
          }) as $scope => $permissions)
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
            @foreach(collect(config('web.permissions'))->mapToGroups(function ($item, $key) {
              if (is_int($key)) return ['global' => $item];
              return [$key => $item];
            })->map(function ($item) {
              if(is_array($item->first())) return $item->first();
              return $item;
            }) as $scope => $permissions)
              <div id="{{ $scope }}-permissions-content" class="tab-pane permissions-tab p-3 fade @if($loop->first) active show @endif"
                   role="tabpanel" aria-labelledby="{{ $scope }}-permissions">
                @foreach($permissions as $ability => $permission)
                  <div class="form-check mb-3">
                    @if(is_array($permission) && array_key_exists('label', $permission))
                      @if(in_array(sprintf('%s.%s', $scope, $ability), $role_permissions))
                        <input type="checkbox" name="permissions[{{ $scope }}.{{ $ability }}]" form="role-form" checked="checked" data-target="#scope-{{ $scope }}-counter" id="permission-{{ $scope }}-{{ $ability }}" class="form-check-input" />
                        <label for="permission-{{ $scope }}-{{ $ability }}" class="form-check-label">{{ trans($permission['label']) }}</label>
                      @else
                        <input type="checkbox" name="permissions[{{ $scope }}.{{ $ability }}]" form="role-form" data-target="#scope-{{ $scope }}-counter" id="permissions-{{ $scope }}-{{ $ability }}" class="form-check-input" />
                        <label for="permissions-{{ $scope }}-{{ $ability }}" class="form-check-label">{{ trans($permission['label']) }}</label>
                      @endif
                      @if(in_array($scope, ['character', 'corporation']))
                        @if(is_null($role->permissions->where('title', sprintf('%s.%s', $scope, $ability))->first()))
                          <input type="hidden" name="filters[{{ $scope }}.{{ $ability }}]" value="{}" form="role-form" />
                        @else
                          <input type="hidden" name="filters[{{ $scope }}.{{ $ability }}]" value="{{ $role->permissions->where('title', sprintf('%s.%s', $scope, $ability))->first()->pivot->filters ?: '{}' }}" form="role-form" />
                        @endif
                      @endif
                    @else
                      @if($scope == 'global')
                        @if(in_array($permission, $role_permissions))
                          <input type="checkbox" name="permissions[{{ $permission }}]" checked="checked" form="role-form" data-target="#scope-global-counter" id="permissions-{{ $permission }}" class="form-check-input" />
                          <label for="permissions-{{ $permission }}" class="form-check-label">{{ ucfirst($permission) }}</label>
                        @else
                          <input type="checkbox" name="permissions[{{ $permission }}]" form="role-form" data-target="#scope-global-counter" id="permissions-{{ $permission }}" class="form-check-input" />
                          <label for="permissions-{{ $permission }}" class="form-check-label">{{ ucfirst($permission) }}</label>
                        @endif
                      @else
                        @if(in_array(sprintf('%s.%s', $scope, $permission), $role_permissions))
                          <input type="checkbox" name="permissions[{{ $scope }}.{{ $permission }}]" checked="checked" form="role-form" data-target="#scope-{{ $scope }}-counter" id="permissions-{{ $scope }}-{{ $permission }}" class="form-check-input" />
                          <label for="permissions{{ $scope }}-{{ $permission }}" class="form-check-label">{{ ucfirst($permission) }}</label>
                        @else
                          <input type="checkbox" name="permissions[{{ $scope }}.{{ $permission }}]" form="role-form" data-target="#scope-{{ $scope }}-counter" id="permissions-{{ $scope }}-{{ $permission }}" class="form-check-input" />
                          <label for="permissions-{{ $scope }}-{{ $permission }}" class="form-check-label">{{ ucfirst($permission) }}</label>
                        @endif
                        @if(in_array($scope, ['character', 'corporation']))
                          @if(is_null($role->permissions->where('title', sprintf('%s.%s', $scope, $permission))->first()))
                            <input type="hidden" name="filters[{{ $scope }}.{{ $permission }}]" value="{}" form="role-form" />
                          @else
                            <input type="hidden" name="filters[{{ $scope }}.{{ $permission }}]" value="{{ $role->permissions->where('title', sprintf('%s.%s', $scope, $permission))->first()->pivot->filters ?: '{}' }}" form="role-form" />
                          @endif
                        @endif
                      @endif
                    @endif
                    @if(in_array($scope, ['character', 'corporation']))
                      @if(is_null($role->permissions->where('title', sprintf('%s.%s', $scope, $ability))->first()) || is_null($role->permissions->where('title', sprintf('%s.%s', $scope, $ability))->first()->pivot->filters))
                        @if(in_array(sprintf('%s.%s', $scope, $ability), $role_permissions))
                          @include('web::configuration.access.partials.permissions.buttons.filters', ['role_id' => $role->id, 'scope' => $scope, 'class' => 'btn-default', 'disabled' => false])
                        @else
                          @include('web::configuration.access.partials.permissions.buttons.filters', ['role_id' => $role->id, 'scope' => $scope, 'class' => 'btn-default', 'disabled' => true])
                        @endif
                      @else
                        @include('web::configuration.access.partials.permissions.buttons.filters', ['role_id' => $role->id, 'scope' => $scope, 'class' => 'btn-warning', 'disabled' => false])
                      @endif
                    @endif
                    @if(is_array($permission) && array_key_exists('description', $permission))
                      <i class="form-text text-muted">{{ trans($permission['description']) }}</i>
                    @endif
                  </div>
                @endforeach
              </div>
            @endforeach
        </div>
      </div>
    </div>
  </div>
</div>