@extends('layouts.admin', ['page' => 'blogs'])

@section('title', __('Create blog'))
    
@section('content') 
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <a class="page-pretitle" href="{{ route('admin.blogs') }}">
                        {{ __('Blogs') }}
                    </a>
                    <h1 class="page-title">
                        {{ __('Create Blog') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.blogs.store') }}" method="POST" enctype="multipart/form-data">
                        @include('layouts._form_errors')
                        @csrf
                        
                        @include('admin.blogs._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
