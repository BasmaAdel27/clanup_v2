@extends('layouts.app', [
    'seo_title' => $blog->name,
    'seo_description' => $blog->description,
])

@section('content')
    <div class="container-xl pt-4 pb-5"> 
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-9">
                <div class="page-header mb-4">
                    <div class="row align-items-center mw-100">
                        <div class="col">
                            <div class="mb-1">
                                <ol class="breadcrumb breadcrumb-alternate" aria-label="breadcrumbs">
                                    <li class="breadcrumb-item"><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                                    <li class="breadcrumb-item"><a href="{{ route('blog') }}">{{ __('Blogs') }}</a></li>
                                    <li class="breadcrumb-item active" aria-current="page"><a>{{ __('Details') }}</a></li>
                                </ol>
                            </div>
                            <h1 class="page-title">
                                {{ $blog->name }}
                            </h1>
                        </div>
                        <div class="col-auto">
                            <div class="btn-list">
                                @livewire('common.share-button', ['icon_class' => '', 'button' => true, 'url' => route('blog.show', $blog->slug)], key($blog->id))
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-lg">
                    <div class="card-body">
                        <div class="crop crop-blog">
                            <img class="img-fluid rounded" src="{{ $blog->image }}" alt="{{ $blog->name }}">
                        </div>

                        <div class="markdown my-4">
                            {!! $blog->content !!}
                        </div>

                        <strong>{{ __('Published at:') }}</strong> 
                        <p class="text-muted">
                            <time datetime="{{ convertToLocal($blog->created_at, 'U') }}">{{ convertToLocal($blog->created_at, 'D, M j g:i A') }}</time>
                        </p>
                    </div>
                </div>

                <hr>

                <div class="row">
                    <div class="col">
                        <h4 class="h2">{{ __('Related Blogs') }}</h4>
                    </div>
                    <div class="col col-auto">
                        <a href="{{ route('blog') }}">{{ __('See all') }}</a>
                    </div>
                    <div class="row mt-3">
                        @foreach ($related_blogs as $blog)
                            <div class="col-md-4 mb-4">
                                @include('application.static.blog._blog_card', ['blog' => $blog])
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
