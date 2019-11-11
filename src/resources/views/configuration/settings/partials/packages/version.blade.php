<dt>
  <i class="fa fa-question-circle text-orange version-check"
     data-vendor="{{ app()->getProvider($package)->getPackagistVendorName() }}"
     data-name="{{ app()->getProvider($package)->getPackagistPackageName() }}"
     data-version="{{ app()->getProvider($package)->getVersion() }}"
     data-toggle="tooltip"
     title="Checking package status..."></i> {{ app()->getProvider($package)->getName() }}
    @if (! is_null(app()->getProvider($package)->getChangelogUri()))
    <button data-toggle="modal" data-target="#changelogModal"
            data-uri="{{ app()->getProvider($package)->getChangelogUri() }}"
            data-name="{{ app()->getProvider($package)->getName() }}"
            @if(app()->getProvider($package)->isChangelogApi())
            data-tag="{{ app()->getProvider($package)->getChangelogTagAttribute() }}"
            data-body="{{ app()->getProvider($package)->getChangelogBodyAttribute() }}"
            @endif>
      <span data-toggle="tooltip" title="Show the changelog">
        <i class="fa fa-history"></i>
      </span>
    </button>
    @endif
</dt>
<dd>
  <ul>
    <li>{{ trans('web::seat.installed') }}: <b>v{{ app()->getProvider($package)->getVersion() }}</b></li>
    <li>{{ trans('web::seat.current') }}: <img src="{{ app()->getProvider($package)->getVersionBadge() }}" /></li>
    <li>{{ trans('web::seat.url') }}: <a href="{{ app()->getProvider($package)->getPackageRepositoryUrl() }}" target="_blank">{{ app()->getProvider($package)->getPackageRepositoryUrl() }}</a> </li>
  </ul>
</dd>