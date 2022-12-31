@extends('layouts.admin', ['page' => 'blog_categories'])

@section('title', __('Edit Blog Category'))
    
@section('content') 
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <a class="page-pretitle" href="{{ route('admin.blog_categories') }}">
                        {{ __('Blog Categories') }}
                    </a>
                    <h1 class="page-title">
                        {{ __('Edit Blog Category') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.blog_categories.update', $blog_category->slug) }}" method="POST">
                        @include('layouts._form_errors')
                        @csrf
                        
                        @include('admin.blog_categories._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
