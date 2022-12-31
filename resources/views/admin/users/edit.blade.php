@extends('layouts.admin', ['page' => 'users'])

@section('title', __('Edit User'))
    
@section('content') 
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <a class="page-pretitle" href="{{ route('admin.users') }}">
                        {{ __('Users') }}
                    </a>
                    <h1 class="page-title">
                        {{ __('Edit User') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                        @include('layouts._form_errors')
                        @csrf
                        
                        @include('admin.users._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
