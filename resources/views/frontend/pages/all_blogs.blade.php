@extends('frontend.titania.layouts.master')

@section('title')
    {{ translate('Blogs') }}
@endsection

@section('css')
    
@endsection

@section('content')
    <!--Nav-->
    @includeWhen(true, 'frontend.titania.components.nav')
    <!--Nav::END-->

    <div id="main-hero" class="hero-body">
            <div class="container has-text-centered">
                <div class="columns is-vcentered">
                    <div class="column is-6 is-offset-3 has-text-centered is-subheader-caption">
                        <h1 class="title is-2">{{ translate('BLOGS') }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="section blog-section">
        <div class="container">
            <!--Blog Layout-->
            <div class="columns">
                <div class="column is-8">
                    <div class="columns is-multiline">

                        @forelse ($blogs as $blog)
                        
                        <!--Post Card-->
                        <div class="column is-6">
                            <div class="card blog-grid-item">
                                <div class="card-image">
                                    <a href="{{ route('frontend.page.index', [$blog->slug]) }}">
                                        <img class="item-featured-image" 
                                            src="{{ asset('placeholder.png') }}" 
                                            data-demo-src="{{ asset('placeholder.png') }}" 
                                            alt="{{ $blog->page_name }}">

                                        <div class="text-overlay-img text-center-overlay">
                                            <h4>{{ Str::limit($blog->page_name, 50) }}</h4>
                                        </div>
                                    </a>
                                    <div class="post-date">
                                        <div class="post-date-inner">
                                            <span>{{ $blog->created_at->format('M') }}</span>
                                            <span>{{ $blog->created_at->format('d') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-content">
                                    <div class="featured-post-title">
                                        <div class="title-meta">
                                            <a href="{{ route('frontend.page.index', [$blog->slug]) }}">
                                                <h2 class="post-title">
                                                    {{ Str::limit($blog->page_name, 70) }}
                                                </h2>
                                            </a>
                                            <h4 class="post-subtitle mt-2 mb-2">
                                                <i class="fa fa-circle"></i>
                                                <span>{{ translate('Posted at') }} {{ $blog->created_at->diffForHumans() }}</span>
                                            </h4>
                                        </div>
                                    </div>
                                    <a class="read-more-link" href="{{ route('frontend.page.index', [$blog->slug]) }}"> {{ translate('Read More') }} <span>⟶</span> </a>
                                </div>
                            </div>
                        </div>

                        @empty
                            
                        @endforelse

                    </div>

                    <div class="load-more has-text-centered">
                        {{ $blogs->links() }}
                    </div>
                </div>

                <div class="column is-4">
                    <div class="blog-sidebar">

                        <!--Recent Posts-->
                        <div class="blog-sidebar-posts">
                            <h4>{{ translate('Recent Posts') }}</h4>

                            <div class="blog-sidebar-posts-inner">
                                <!-- Recent Post -->
                                @forelse ($latest_blogs as $blog)
                                
                                <a href="{{ route('frontend.page.index', [$blog->id, $blog->slug]) }}" class="blog-sidebar-post">
                                    <div class="post-content">
                                        <h3>{{ Str::limit($blog->page_name, 70) }}</h3>
                                        <div class="meta">
                                            <i class="fa fa-circle"></i>
                                            <span>{{ translate('Posted at') }} {{ $blog->created_at->diffForHumans() }}</span>
                                        </div>
                                    </div>
                                </a>

                                @empty
                                    
                                @endforelse

                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
    
@endsection