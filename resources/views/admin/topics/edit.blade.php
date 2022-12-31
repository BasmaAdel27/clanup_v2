@extends('layouts.admin', ['page' => 'topics'])

@section('title', __('Edit topic'))
    
@section('content') 
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <a class="page-pretitle" href="{{ route('admin.topics') }}">
                        {{ __('Topics') }}
                    </a>
                    <h1 class="page-title">
                        {{ __('Edit Topic') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.topics.update', $topic->id) }}" method="POST">
                        @include('layouts._form_errors')
                        @csrf
                        
                        @include('admin.topics._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
