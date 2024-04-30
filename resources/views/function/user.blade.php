@if (Route::has('login'))
    <div class="auth">
        <hr>
        @auth
            <span class='glowlightgreen'>[Auth]</span><span>{{ Auth::user()->username }}</span>
            <span>member since: {{ \Carbon\Carbon::parse(Auth::user()->created_at)->format('m-d-Y') }}</span>
            <br></br>
            <span><a href="{{ route('logout') }}">{{ __('Log out') }}</a></span>
        @else
            <a href="{{ route('login') }}">{{ __('Log in') }}</a>
            
        @endauth
        <hr>
    </div>
@endif