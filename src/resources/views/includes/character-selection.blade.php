<ul class="dropdown-menu">

  @foreach($user_characters as $character)

    <li class="media user-character @if($character->character_id == setting('main_character_id')) active @endif">

      <a href="{{ route('profile.change-character', ['character_id' => $character->character_id]) }}">
        <div class="media-left">
          {!! img('character', $character->character_id, 32, ['class' => 'img-circle'], false) !!}
        </div>
        <div class="media-body">
          <h4 class="media-heading">{{ $character->name }}</h4>

          @unless(empty($character->corporation))
            <span>{{ $character->corporation->name }} [{{ $character->corporation->ticker }}]</span>
          @endunless

        </div>
      </a>
    </li>

  @endforeach

  <li class="user-character new-character">
    <!-- Route to link new character -->
    <a href="{{ route('auth.eve') }}" class="text-center">
      <h3>
        <span class="fa fa-plus-circle"></span>
      </h3>
    </a>
  </li>

  <!-- Menu Footer-->
  <li class="user-footer">
    <div class="pull-left">
      <a href="{{ route('profile.view') }}"
         class="btn btn-default btn-flat">{{ trans('web::seat.profile') }}</a>
    </div>
    <div class="pull-right">
      <form role="form" action="{{ route('auth.logout') }}" method="post">
        {{ csrf_field() }}
        <button type="submit" class="btn btn-default btn-flat">
          {{ trans('web::seat.sign_out') }}
        </button>
      </form>
    </div>
  </li>

</ul>
