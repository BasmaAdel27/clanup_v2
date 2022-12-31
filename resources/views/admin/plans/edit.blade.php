@extends('layouts.admin', ['page' => 'plans'])

@section('title', __('Edit a plan'))
    
@section('content') 
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <a class="page-pretitle" href="{{ route('admin.plans') }}">
                        {{ __('Plans') }}
                    </a>
                    <h1 class="page-title">
                        {{ __('Edit Plan') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.plans.update', $plan->id) }}" method="POST">
                        @include('layouts._form_errors')
                        @csrf
                        
                        @include('admin.plans._form')
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
