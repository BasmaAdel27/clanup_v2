@extends('layouts.app', [
    'seo_title' => isset($tag) ? __(':tag Blogs', ['tag' => $tag]) : '' . __('Blogs'),
    'seo_og_type' => 'articles',
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
                                </ol>
                            </div>
                            <h1 class="page-title">
                                {{ __('Blog') }}
                            </h1>
                        </div>
                    </div>
                </div>

                <div class="row">
                    @foreach ($blogs as $blog)
                        <div class="col-md-4 mb-4 hover-animate">
                            @include('application.static.blog._blog_card', ['blog' => $blog])
                        </div>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center">
                    {{ $blogs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
