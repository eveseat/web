<div id="tab-permissions" class="tab-pane active">
  <div class="panel-group" role="tablist" aria-multiselectable="true" id="permissions-accordion">
    {{-- build some generic collection in order to provide some kind of compatibility layer during ACL revamp --}}
    @foreach(collect(config('web.permissions'))->mapToGroups(function ($item, $key) {
        if (is_int($key)) return ['global' => $item];
        return [$key => $item];
      })->map(function ($item) {
        if(is_array($item->first()))
          return $item->first();
        return $item;
      }) as $scope => $permissions)
      <div class="panel panel-default permissions-tab">
        <div class="panel-heading" role="tab" id="{{ $scope }}-permissions">
          <h4 class="panel-title">
            <a role="button" data-toggle="collapse" data-parent="#permissions-accordion"
               href="#{{ $scope }}-permissions-content" aria-expanded="true" aria-controls="{{ $scope }}-permissions-content">
              {{ ucfirst($scope) }}
              <span class="active-permissions-counter">(<b>0</b>/{{ count($permissions) }})</span>
            </a>
            <span class="pull-right">
                  <button type="button" class="btn btn-default btn-xs check-all-permissions">Check all permissions</button>
                </span>
          </h4>
        </div>
        @if($loop->first)
          <div id="{{ $scope }}-permissions-content" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="{{ $scope }}-permissions">
        @else
          <div id="{{ $scope }}-permissions-content" class="panel-collapse collapse" role="tabpanel" aria-labelledby="{{ $scope }}-permissions">
        @endif
            <div class="panel-body">
              @foreach($permissions as $ability => $permission)
                <div class="form-group">
                  <label>
                    @if(is_array($permission) && array_key_exists('label', $permission))
                      @if(in_array(sprintf('%s.%s', $scope, $ability), $role_permissions))
                        <input type="checkbox" name="permissions[{{ $scope }}.{{ $ability }}]" form="role-form" checked="checked" /> {{ ucfirst($permission['label']) }}
                      @else
                        <input type="checkbox" name="permissions[{{ $scope }}.{{ $ability }}]" form="role-form" /> {{ ucfirst($permission['label']) }}
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
                          <input type="checkbox" name="permissions[{{ $permission }}]" checked="checked" form="role-form" /> {{ ucfirst($permission) }}
                        @else
                          <input type="checkbox" name="permissions[{{ $permission }}]" form="role-form" /> {{ ucfirst($permission) }}
                        @endif
                      @else
                        @if(in_array(sprintf('%s.%s', $scope, $permission), $role_permissions))
                          <input type="checkbox" name="permissions[{{ $scope }}.{{ $permission }}]" checked="checked" form="role-form" /> {{ ucfirst($permission) }}
                        @else
                          <input type="checkbox" name="permissions[{{ $scope }}.{{ $permission }}]" form="role-form" /> {{ ucfirst($permission) }}
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
                  </label>
                  @if(in_array($scope, ['character', 'corporation']))
                    @if(is_null($role->permissions->where('title', sprintf('%s.%s', $scope, $ability))->first()) || is_null($role->permissions->where('title', sprintf('%s.%s', $scope, $ability))->first()->pivot->filters))
                      @if(in_array(sprintf('%s.%s', $scope, $ability), $role_permissions))
                        <button type="button" data-roleid="{{ $role->id }}" data-permission="{{ $scope }}.{{ $ability }}" data-toggle="modal" data-target="#permission-modal" class="btn btn-xs btn-default">limits</button>
                      @else
                        <button type="button" data-roleid="{{ $role->id }}" data-permission="{{ $scope }}.{{ $ability }}" data-toggle="modal" data-target="#permission-modal" class="btn btn-xs btn-default" disabled="disabled">limits</button>
                      @endif
                    @else
                      <button type="button" data-roleid="{{ $role->id }}" data-permission="{{ $scope }}.{{ $ability }}" data-toggle="modal" data-target="#permission-modal" class="btn btn-xs btn-warning">limits</button>
                    @endif
                  @endif
                  @if(is_array($permission) && array_key_exists('description', $permission))
                    <p class="help-block">{{ $permission['description'] }}</p>
                  @endif
                </div>
              @endforeach
            </div>
          </div>
        </div>
      @endforeach
  </div>
</div>