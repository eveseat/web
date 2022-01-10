@extends('web::layouts.character', ['viewname' => 'sheet', 'breadcrumb' => trans('web::seat.sheet')])

@section('page_description', trans_choice('web::seat.character', 1) . ' ' . trans('web::seat.sheet'))

@inject('request', 'Illuminate\Http\Request')

@section('character_content')

  <div class="row">

    <div class="col-md-6">

      @include('web::character.sheet.summary')

      @include('web::character.sheet.implants')

      @include('web::character.sheet.attributes')

    </div>

    <div class="col-md-6">

      @include('web::character.sheet.employment-history')

      @include('web::character.sheet.clones')

      @include('web::character.sheet.titles')

    </div>

  </div>

@stop

@push('javascript')
  @include('web::includes.javascript.id-to-name')
@endpush
