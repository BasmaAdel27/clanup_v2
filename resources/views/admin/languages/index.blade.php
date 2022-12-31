@extends('layouts.admin', ['page' => 'languages'])

@section('title', __('Languages'))
    
@section('content') 
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Languages') }}
                    </h1>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('admin.languages.create') }}" class="btn btn-primary">
                        {{ __('Create new') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Languages') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Locale') }}</th>
                                        <th>{{ __('Edit') }}</th>
                                    </tr> 
                                </thead> 
                                <tbody class="list" id="languages">
                                    @foreach($languages as $language => $name)
                                        <tr>
                                            <td>
                                                {{ $name }}
                                                @if (Config::get('app.locale') == $language)
                                                    <div class="badge bg-primary">
                                                        {{ __('Default') }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td> 
                                                <a href="{{ route('admin.languages.translations', $language) }}">
                                                    {{ $language }}
                                                </a>
                                            </td>
                                            <td><a href="{{ route('admin.languages.translations', $language) }}">{{ __('Edit') }}</a></td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection