@extends('layouts.admin', ['page' => 'pages'])

@section('title', __('Edit page'))
    
@section('content') 
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <a class="page-pretitle" href="{{ route('admin.pages') }}">
                        {{ __('Pages') }}
                    </a>
                    <h1 class="page-title">
                        {{ __('Edit Page') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.pages.update', $page->slug) }}" method="POST">
                        @include('layouts._form_errors')
                        @csrf
                        
                        @include('admin.pages._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
