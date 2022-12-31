@extends('layouts.app', [
    'seo_title' => $page->name,
    'seo_description' => $page->description,
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
                                    <li class="breadcrumb-item active"><a>{{ __('Pages') }}</a></li>
                                </ol>
                            </div>
                            <h1 class="page-title">
                                {{ $page->name }}
                            </h1>
                        </div>
                        <div class="col-auto">
                            <div class="btn-list">
                                @livewire('common.share-button', ['icon_class' => '', 'button' => true, 'url' => route('page.show', $page->slug)], key($page->id))
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card card-lg">
                    <div class="card-body">
                        <h2 class="card-title">
                            {{ $page->name }}
                        </h2>

                        <div class="markdown mb-4">
                            {!! $page->content !!}
                        </div>

                        <strong>{{ __('Updated at:') }}</strong> 
                        <p class="text-muted">
                            <time datetime="{{ convertToLocal($page->updated_at, 'U') }}">{{ convertToLocal($page->updated_at, 'D, M j g:i A') }}</time>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
