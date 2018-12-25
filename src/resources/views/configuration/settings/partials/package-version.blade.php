<dt>
  <i class="fa fa-question-circle text-orange version-check"
     data-vendor="{{ call_user_func([$package, 'getPackagistVendorName']) }}"
     data-name="{{ call_user_func([$package, 'getPackagistPackageName']) }}"
     data-version="{{ call_user_func([$package, 'getVersion']) }}"
     data-toggle="tooltip"
     title="Checking package status..."></i> {{ call_user_func([$package, 'getName']) }}
</dt>
<dd>
  <ul>
    <li>{{ trans('web::seat.installed') }}: <b>v{{ call_user_func([$package, 'getVersion']) }}</b></li>
    <li>{{ trans('web::seat.current') }}: <img src="{{ call_user_func([$package, 'getVersionBadge']) }}" /></li>
    <li>{{ trans('web::seat.url') }}: <a href="{{ call_user_func([$package, 'getPackageRepositoryUrl']) }}" target="_blank">{{ call_user_func([$package, 'getPackageRepositoryUrl']) }}</a> </li>
  </ul>
</dd>