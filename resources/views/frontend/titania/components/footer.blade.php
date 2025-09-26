<footer class="footer footer-light-left">
        <div class="container">
            <div class="columns is-vcentered">
                <div class="column is-6">
                    <div class="mb-20">
                        <img class="small-footer-logo" src="{{ logo() }}" alt="{{ appName() }}">
</div>
<div>
    <span class="moto">© {{ Carbon\Carbon::now()->year }} {{ appName() }}</span>
    <nav class="level is-mobile mt-20">
        <div class="level-left level-social">
            @if(application('site_facebook'))
                <a href="{{ application('site_facebook') }}" target="_blank" class="level-item">
                    <span class="icon"><i class="fa fa-facebook"></i></span>
                </a>
            @endif

            @if(application('site_twitter'))
                <a href="{{ application('site_twitter') }}" target="_blank" class="level-item">
                    <span class="icon"><i class="fa fa-twitter"></i></span>
                </a>
            @endif

            @if(application('site_linkedin'))
                <a href="{{ application('site_linkedin') }}" target="_blank" class="level-item">
                    <span class="icon"><i class="fa fa-linkedin"></i></span>
                </a>
            @endif

            @if(application('site_youtube'))
                <a href="{{ application('site_youtube') }}" target="_blank" class="level-item">
                    <span class="icon"><i class="fa fa-youtube"></i></span>
                </a>
            @endif

            @if(application('site_linkedin'))
                <a href="{{ application('site_linkedin') }}" target="_blank" class="level-item">
                    <span class="icon"><i class="fa fa-linkedin"></i></span>
                </a>
            @endif

        </div>
    </nav>
</div>
</div>
<div class="column">
    <div class="footer-nav-right">

        @forelse(menus('footer 1') as $menu)
            @if($menu != null)
                <a class="footer-nav-link"
                    href="{{ $menu['link'] ?? 'javascript:;' }}">{{ $menu['label'] ?? null }}</a>
            @endif
        @empty

        @endforelse
    </div>
</div>
</div>
</div>
</footer>
