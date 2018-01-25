<ul class="dropdown-menu">
    @foreach(\Seat\Web\Models\UserRelation::where('main_user_id', auth()->user()->id)->get() as $relation)
    <li class="media user-character">
        <a href="#">
            <div class="media-left">
                {!! img('character', $relation->character()->character_id, 64, ['class' => 'img-circle'], false) !!}
            </div>
            <div class="media-body">
                <h4 class="media-heading">{{ $relation->character()->name }}</h4>
                <h5>{{ $relation->character()->corporation->name }} [{{ $relation->character()->corporation->ticker }}]</h5>
            </div>
        </a>
    </li>
    @endforeach
    <li class="user-character new-character">
        <!-- Route to link new character -->
        <a href="#" class="text-center">
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