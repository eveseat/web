<dt>
  <i class="fa fa-question-circle text-orange version-check"
     data-vendor="{{ app()->getProvider($package)->getPackagistVendorName() }}"
     data-name="{{ app()->getProvider($package)->getPackagistPackageName() }}"
     data-version="{{ app()->getProvider($package)->getVersion() }}"
     data-toggle="tooltip"
     title="Checking package status..."></i> {{ app()->getProvider($package)->getName() }}
    @if (! is_null(app()->getProvider($package)->getChangelogUri()))
      @if(app()->getProvider($package)->isChangelogApi())
        <a href="#"
           data-toggle="modal" data-target="#changelogModal"
           data-uri="{{ app()->getProvider($package)->getChangelogUri() }}"
           data-name="{{ app()->getProvider($package)->getName() }}"
           data-tag="{{ app()->getProvider($package)->getChangelogTagAttribute() }}"
           data-body="{{ app()->getProvider($package)->getChangelogBodyAttribute() }}">
          <span data-toggle="tooltip" title="Show the changelog">
            <i class="fas fa-history"></i>
          </span>
        </a>
      @else
        <a href="#"
           data-toggle="modal" data-target="#changelogModal"
           data-uri="{{ app()->getProvider($package)->getChangelogUri() }}"
           data-name="{{ app()->getProvider($package)->getName() }}">
          <span data-toggle="tooltip" title="Show the changelog">
            <i class="fas fa-history"></i>
          </span>
        </a>
      @endif
    @endif
</dt>
<dd>
  <ul>
    <li>{{ trans('web::seat.installed') }}: <b>v{{ app()->getProvider($package)->getVersion() }}</b></li>
    <li>{{ trans('web::seat.current') }}: <img src="{{ app()->getProvider($package)->getVersionBadge() }}" /></li>
    <li>{{ trans('web::seat.url') }}: <a href="{{ app()->getProvider($package)->getPackageRepositoryUrl() }}" target="_blank">{{ app()->getProvider($package)->getPackageRepositoryUrl() }}</a> </li>
  </ul>
</dd>