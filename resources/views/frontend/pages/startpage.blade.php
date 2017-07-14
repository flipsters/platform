@extends('frontend.layouts.app')


@section('subheader')

  {{-- Load carousel --}}
  @if(config('settings.frontpage_carousel'))
    @include('frontend.pages.inc.slider')
  @endif

  <div style="position: relative">


    <div style="position: absolute; z-index:0 !important; top: 0; width: 100%; ">

      <div id="parallax" style="background: linear-gradient(0deg, rgba(26,24,24,1) 0%, rgba(26,24,24,0.8) 30%, rgba(26,24,24,0.5) 100%),url('{{ trans('general.url') }}img/game_pattern_white.png'); height:200px; "></div>
    </div>

  </div>

@stop



@section('content')

{{-- Load Google AdSense --}}
@if(config('settings.google_adsense'))
  @include('frontend.ads.google')
@endif

{{-- Title "Newest Listings" --}}
<div style="margin-bottom: 20px; font-size: 24px; color: #fff; font-weight: 700; "><i class="fa fa-tags" aria-hidden="true"></i> {{ trans('listings.general.newest_listings') }}</div>


  {{-- START LISTINGS --}}
  <div class="row">

    @forelse($listings->slice(0, 24) as $listing)
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
      <div class="empty-list add-button">
        {{-- Icon --}}
        <div class="icon">
          <i class="fa fa-frown-o" aria-hidden="true"></i>
        </div>
        {{-- Text --}}
        <div class="text">
          {{ trans('listings.general.no_listings') }}
        </div>
        {{-- Create listing button --}}
        @if(Auth::check())
        <a href="{{ url('listings/add' ) }}" class="btn btn-orange"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('listings.general.no_listings_add') }}</a>
        @else
        <a href="javascript:void(0);" data-toggle="modal" data-target="#LoginModal" class="btn btn-orange"><i class="fa fa-plus" aria-hidden="true"></i> {{ trans('listings.general.no_listings_add') }}</a>
        @endif
      </div>
      {{-- End empty list message --}}
    @endforelse

    {{-- Show more link on bottom --}}
    @if(count($listings) > 24)
      <div class="text-center m-b-10 m-t-10">
        <a href="{{ url('listings') }}" class="btn btn-dark f-w-500">{{ trans('listings.general.show_all') }}</a>
      </div>
    @endif
    <!-- <center><script type="text/javascript" language="javascript">
      var aax_size='728x90';
      var aax_pubname = 'swapifier-21';
      var aax_src='302';
    </script>
    <script type="text/javascript" language="javascript" src="http://c.amazon-adsystem.com/aax2/assoc.js"></script></center> -->

  </div>
  {{-- END LISTINGS --}}


@stop


@section('after-scripts')
  {{-- Load carousel JS Settings --}}
  @if(config('settings.frontpage_carousel'))
    <script>
      $(document).ready(function(){
        $(".owl-carousel").on('initialize.owl.carousel',function(){
            $(".owl-carousel").addClass('carousel-loaded');
        });

        $(".owl-carousel").owlCarousel({
                autoplay: true,
                nav:false,
                dots:false,
                lazyLoad: true,
                loop: true,
                items : 4, //4 items above 1000px browser width
                responsive:{
                    0:{
                        items:1
                    },
                    500:{
                        items:2
                    },
                    900:{
                        items:3
                    },
                    1100:{
                        items:4
                    },
                    1500:{
                        items:5
                    }
                }
        });
      });
    </script>
  @endif
@stop
