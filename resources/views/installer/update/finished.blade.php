@extends('layouts.installer')

@section('title', __('Update Finished'))

@section('content')
    <p class="text-center">
        {{ session('message')['message'] }}
    </p>

    <p class="text-center">
        <a class="btn btn-primary px-4" href="{{ route('updater.overview') }}">
            {{ __('Click here to exit') }}
        </a>
    </p>
@endsection
