<div class="site-footer">
  <div class="flex-center-space">
    <ul class="footer-menu">
      @foreach($menu as $item)
        @if($item->type == 'page_link')
          <li><a href="{{ url('page/' . $item->page->slug ) }}">{{$item->name}}</a></li>
        @else
          @if($item->type == 'internal_link')
            <li><a href="{{ url( $item->link ) }}">{{$item->name}}</a></li>
          @else
            <li><a href="{{ $item->link }}" target="_blank">{{$item->name}}</a></li>
          @endif
        @endif
      @endforeach
    </ul>
    @if($languages && $languages->count() > 1)
    <div class="no-flex-shrink">
      <select class="form-control select m-t-20" onChange="window.location.href=this.value">
        <option value="disabled" disabled selected>Language</option>
          @foreach($languages as $language)
          <option value="{{url('lang/' . $language->abbr)}}">{{$language->name}}</option>
          @endforeach
      </select>
    </div>
    @endif
  </div>
  <div class="social flex-center-space-wrap">
    <div style="font-size: 12px; line-height: 34px;">
      © 2017 <span class="f-w-700">{{ config('settings.page_name') }} &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="https://madewithlove.org.in" target="_blank" style="color:#8c8b8b;text-decoration:none">Made with <span style="color:#e74c3c;font-size:10px"><i class="fa fa-heart"></i></span> in India</a></span>
    </div>
    <div>
      @if(config('settings.facebook_link'))
      <a class="btn btn-icon btn-round btn-dark" href="{{config('settings.facebook_link')}}" target="_blank"><i class="icon fa fa-facebook"></i></a>
      @endif
      @if(config('settings.twitter_link'))
      <a class="btn btn-icon btn-round btn-dark" href="{{config('settings.twitter_link')}}" target="_blank"><i class="fa fa-twitter"></i></a>
      @endif
      @if(config('settings.google_plus_link'))
      <a class="btn btn-icon btn-round btn-dark" href="{{config('settings.google_plus_link')}}" target="_blank"><i class="fa fa-google-plus"></i></a>
      @endif
      @if(config('settings.youtube_link'))
      <a class="btn btn-icon btn-round btn-dark" href="{{config('settings.youtube_link')}}" target="_blank"><i class="fa fa-youtube-play"></i></a>
      @endif
      @if(config('settings.instagram_link'))
      <a class="btn btn-icon btn-round btn-dark" href="{{config('settings.instagram_link')}}" target="_blank"><i class="fa fa-instagram"></i></a>
      @endif
    </div>
  </div>


</div>
