@forelse($likes as $like)
  {{-- Start user like --}}
  <div @if(!$loop->last) class="m-b-10" @endif>
    <a href="{{$like->user->url}}" class="user-link">
      {{-- User avatar --}}
      <span class="avatar avatar-xs">
        <img src="{{$like->user->avatar_square_tiny}}" alt="{{$like->user->name}}'s Avatar">
      </span>
      {{-- User name --}}
      {{$like->user->name}}
    </a>
  </div>
  {{-- End user likes --}}
@empty
  {{-- No user likes --}}
  <div class="text-center">
    <i class="fa fa-frown-o" aria-hidden="true"></i> {{ trans('comments.no_likes') }}
  </div>
@endforelse
