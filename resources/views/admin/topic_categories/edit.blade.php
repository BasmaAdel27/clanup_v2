@extends('layouts.admin', ['page' => 'topic_categories'])

@section('title', __('Edit topic category'))
    
@section('content') 
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <a class="page-pretitle" href="{{ route('admin.topic_categories') }}">
                        {{ __('Topic Categories') }}
                    </a>
                    <h1 class="page-title">
                        {{ __('Edit Topic Category') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.topic_categories.update', $topic_category->id) }}" method="POST">
                        @include('layouts._form_errors')
                        @csrf
                        
                        @include('admin.topic_categories._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
