@extends('layouts.admin', ['page' => 'languages'])

@section('title', __('Add Language'))
    
@section('content') 
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <a class="page-pretitle" href="{{ route('admin.languages') }}">
                        {{ __('Languages') }}
                    </a>
                    <h1 class="page-title">
                        {{ __('Add Language') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <form action="{{ route('admin.languages.store') }}" method="POST">
                        @include('layouts._form_errors')
                        @csrf
                        
                        <div class="form-group mb-4 required">
                            <label for="name">{{ __('Name') }}</label>
                            <select name="name" class="form-control">
                                @foreach (get_language_codes() as $key => $name)
                                    <option value="{{ $key }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
    
                        <div class="form-group text-end">
                            <button type="submit" class="btn btn-primary">{{ __('Add Language') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection