@if (count($errors->all()) > 0)
  <div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="fas fa-ban"></i> {{ trans('web::seat.error') }}</h4>

    <p class="message">
      @if(is_array($errors->all()))
        @foreach ($errors->all() as $m)
          {{ $m }}<br>
        @endforeach
      @else
        {{ $errors->all() }}
      @endif
    </p>
  </div>
@endif

@if ($message = session('success'))
  <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4><i class="fas fa-check-circle"></i> {{ trans('web::seat.success') }}</h4>

    <p class="message">
      @if(is_array($message))
        @foreach ($message as $m)
          {{ $m }}<br>
        @endforeach
      @else
        {{ $message }}
      @endif
    </p>
  </div>
@endif

@if ($message = session('status'))
  <div class="alert alert-success alert-block">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4><i class="fas fa-check-circle"></i> {{ trans('web::seat.success') }}</h4>

    <p class="message">
      @if(is_array($message))
        @foreach ($message as $m)
          {{ $m }}<br>
        @endforeach
      @else
        {{ $message }}
      @endif
    </p>
  </div>
@endif

@if ($message = session('error'))
  <div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="fas fa-ban"></i> {{ trans('web::seat.error') }}</h4>

    <p class="message">
      @if(is_array($message))
        @foreach ($message as $m)
          {{ $m }}<br>
        @endforeach
      @else
        {{ $message }}
      @endif
    </p>
  </div>
@endif

@if ($message = session('warning'))
  <div class="alert alert-warning alert-block">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4><i class="fas fa-exclamation-circle"></i> {{ trans('web::seat.warning') }}</h4>

    <p class="message">
      @if(is_array($message))
        @foreach ($message as $m)
          {{ $m }}<br>
        @endforeach
      @else
        {{ $message }}
      @endif
    </p>
  </div>
@endif

@if ($message = session('notice'))
  <div class="alert alert-warning alert-block">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4><i class="fas fa-exclamation-circle"></i> {{ trans('web::seat.notice') }}</h4>

    <p class="message">
      @if(is_array($message))
        @foreach ($message as $m)
          {{ $m }}<br>
        @endforeach
      @else
        {{ $message }}
      @endif
    </p>
  </div>
@endif

@if ($message = session('info'))
  <div class="alert alert-info alert-block">
    <button type="button" class="close" data-dismiss="alert">&times;</button>
    <h4><i class="fas fa-info-circle"></i> {{ trans('web::seat.info') }}</h4>

    <p class="message">
      @if(is_array($message))
        @foreach ($message as $m)
          {{ $m }}
        @endforeach
      @else
        {{ $message }}
      @endif
    </p>
  </div>
@endif
