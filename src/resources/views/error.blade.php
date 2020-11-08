@extends('web::layouts.app-mini')

@section('content')
    <div class="error-page">
      <h3 class="text-uppercase text-danger" style="margin-left: 120px;">{{ $error_name }}</h3>
      <hr/>
      <h2 class="headline text-danger float-left" style="font-size: 100px;">
        <i class="far fa-frown"></i>
      </h2>
      <div style="margin-left: 120px;">
        <p class="text-muted text-justify font-italic">{{ $error_message }}</p>
        <a href="{{ url()->previous() }}" class="btn btn-danger">
          <i class="fas fa-arrow-circle-left"></i> Go back</a>
      </div>
    </div>
@stop