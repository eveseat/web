@if ($user->name != 'admin' && $user->main_character_id && $user->main_character_id >= 90000000)
    {!! img('characters', 'portrait', $user->main_character_id, 32, ['class' => 'avatar avatar-sm'], false) !!}
@else
  <img src="{{ asset('web/img/logo.png') }}" width="32px" height="32px" alt="{{ config('app.name') }}" />
@endif
