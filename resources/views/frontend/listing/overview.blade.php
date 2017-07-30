@extends('frontend.layouts.app')


@section('subheader')

<div style="position: relative;  height: 0px; ">


  <div style="position: absolute; z-index:0 !important; top: 0; width: 100%;">
    @if(!is_null($system))
    <div style="background-color: {{$system->color}}; height: 300px; margin-top: -60px; z-index: 0; position: relative;"></div>
    @endif

    <div style="background: linear-gradient(0deg, rgba(26,24,24,1) 0%, rgba(26,24,24,0.6) 50%, rgba(26,24,24,0) 100%),url('https://unpkg.com/swapdelivr@1.0.0/img/game_pattern_white.png'); z-index: 1; height: 300px; position: absolute; top: 0; width: 100%; margin-top: -60px;"></div>

  </div>

</div>

@stop



@section('content')

@if(!is_null($system))

<div style="margin-bottom: 50px;">
  {{-- Check if platform logo setting is enabled --}}
  @if( config('settings.platform_logo') )
    <img src="{{ trans('general.url') . 'logos/' . $system->acronym . '.png' }}" alt="" height="40">
  @else
    <span class="platform-title">{{$system->name}}</span>
  @endif
</div>

@endif

{{-- Load Google AdSense --}}
@if(config('settings.google_adsense'))
  @include('frontend.ads.google')
@endif

{{-- Listings title --}}
<div style="margin-bottom: 20px; font-size: 24px; color: #fff; font-weight: 700; "><i class="fa fa-tags" aria-hidden="true"></i> {{ trans('general.listings') }}</div>

{{-- <hr style="border-top: 1px solid rgba(255,255,255,0.2)"> --}}

  {{-- START LISTINGS --}}
  <div class="row">

    @forelse($listings as $listing)
      {{-- START GAME --}}
      <div class="col-xs-6 col-sm-4 col-md-3 col-lg-3 col-xl-2 m-b-20">

        {{-- Start Game Cover --}}
        <div class="card game-cover-wrapper hvr-grow-shadow">
          {{-- Pacman Loader for background image - show only when cover exists --}}
          @if($listing->game->image_cover)
          <div class="loader pacman-loader cover-loader"></div>
          {{-- Show game name, when no cover exist --}}
          @else
          <div class="no-cover-name">{{$listing->game->name}}</div>
          @endif

          <a href="{{ $listing->url_slug }}">
            {{-- Start Trade Ribbon --}}
            @if($listing->trade)
            <div class="ribbon ribbon-clip ribbon-bottom ribbon-trade" style="@if($listing->sell) margin-bottom: 50px; @else margin-bottom: 10px; @endif">
              <span class="ribbon-inner"><i class="icon fa fa-exchange" aria-hidden="true"></i></span>
            </div>
            @endif
            {{-- End Trade Ribbon --}}

            {{-- Start Sell Ribbon --}}
            @if($listing->sell)
            <div class="ribbon ribbon-clip ribbon-bottom ribbon-sell">
              <div class="ribbon-inner">
                <span class="currency">{{ Currency(Config::get('settings.currency'))->getSymbol() }}</span>
                <span class="price">{{ $listing->getPrice(false) }}</span>
              </div>
            </div>
            @endif
            {{-- End Sell Ribbon --}}

            {{-- Payment icon --}}
            @if($listing->payment)
            <div class="animation-scale-up payment-enabled">
              <i class="fa fa-paypal" aria-hidden="true"></i>
            </div>
            @endif

            {{-- Digital download icon --}}
            @if($listing->digital)
            <div class="animation-scale-up digital-download {{ $listing->payment ? 'with-payment' : '' }}">
              <i class="fa fa-download" aria-hidden="true"></i>
            </div>
            @endif

            {{-- Pickup icon --}}
            @if($listing->pickup)
            <div class="pickup-icon {{ $listing->digital ? 'with-digital' : '' }} {{ $listing->payment ? 'with-payment' : '' }}">
              <i class="fa fa-handshake-o" aria-hidden="true"></i>
            </div>
            @endif

            {{-- Delivery icon --}}
            @if($listing->delivery)
            <div class="delivery-icon {{ $listing->pickup ? 'with-pickup' : '' }} {{ $listing->digital ? 'with-digital' : '' }} {{ $listing->payment ? 'with-payment' : '' }}">
              <i class="fa fa-truck" aria-hidden="true"></i>
            </div>
            @endif

            {{-- Generated game cover with platform on top --}}
            @if($listing->game->cover_generator)
              <div class="lazy game-cover gen"  data-original="{{$listing->game->image_cover}}"></div>
              <div class="game-platform-gen" style="background-color: {{$listing->game->platform->color}}; text-align: {{$listing->game->platform->cover_position}};">
                {{-- Check if platform logo setting is enabled --}}
                @if( config('settings.platform_logo') )
                  <img src="{{ trans('general.url') . 'logos/' . $listing->game->platform->acronym . '_tiny.png' }}" alt="{{$listing->game->platform->name}} Logo">
                @else
                  <span>{{$listing->game->platform->name}}</span>
                @endif
              </div>
            {{-- Normal game cover --}}
            @else
              <div class="lazy game-cover"  data-original="{{$listing->game->image_cover}}"></div>
            @endif
          </a>
        </div>
        {{-- End Game Cover --}}

        {{-- Start User info --}}
        <div class="game-user-details">
          {{-- Distance --}}
          @if($listing->distance)
          <span class="distance">
            <i class="fa fa-location-arrow" aria-hidden="true"></i> {{$listing->distance}}
          </span>
          @endif
          {{-- User avtar and name --}}
          <a href="{{ $listing->user->url }}" class="user-link">
            <span class="avatar avatar-xs">
              <img src="{{$listing->user->avatar_square_tiny}}" alt="{{$listing->user->name}}'s Avatar">
            </span>
            {{$listing->user->name}}
          </a>
        </div>
        {{-- End User info --}}
      </div>
      {{-- End GAME --}}
    @empty
      {{-- Start empty list message --}}
      <div class="empty-list">
        {{-- Icon --}}
        <div class="icon">
          <i class="fa fa-frown-o" aria-hidden="true"></i>
        </div>
        {{-- Text --}}
        <div class="text">
          {{ trans('listings.general.no_listings') }}
        </div>
      </div>
      {{-- End empty list message --}}
    @endforelse

  </div>
  {{-- END LISTINGS --}}


{{ $listings->links() }}

@section('after-scripts')


<script type="text/javascript">
$(document).ready(function(){
  $(window).on("scroll", function() {
    if($(window).scrollTop() >= 30){
      $('.site-navbar').css('background-color','rgba(34,33,33,1)');
      $(".sticky-header").removeClass('slide-up')
      $(".sticky-header").addClass('slide-down')
    }else{
      $('.site-navbar').css('background','linear-gradient(0deg, rgba(34,33,33,0) 0%, rgba(34,33,33,0.8) 100%)');
    }
      var fromTop = $(window).scrollTop();
      $(".sticky-header").toggleClass("down", (fromTop > 100));
  });

});
</script>
@endsection

@stop
