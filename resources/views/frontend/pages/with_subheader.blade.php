@extends('frontend.layouts.app')

@section('subheader')
  <div class="subheader">

    <div class="background-pattern" style="background-image: url('{{ trans('general.url') }}img/game_pattern.png') !important;"></div>
    <div class="background-color"></div>

    <div class="content">
      <span class="title"><i class="fa {{ $page->subheader_icon }}"></i> {{ $page->subheader_title }}</span>
    </div>

  </div>

@endsection

@section('content')
  <div class="panel">
    <div class="panel-body">
      {!! $page->content !!}
    </div>
  </div>
@stop
