@extends('layouts.admin', ['page' => 'languages'])

@section('title', __('Edit Language'))
    
@push('page_head_scripts') 
    <link type="text/css" href="{{ asset('assets/css/language.css') }}" rel="stylesheet">
@endpush

@section('content') 
    <div class="bg-white">
        <div class="container">
            <div class="page-header d-print-none">
                <div class="row align-items-center">
                    <div class="col">
                        <a class="page-pretitle" href="{{ route('admin.languages') }}">
                            {{ __('Languages') }}
                        </a>
                        <h1 class="page-title">
                            {{ __('Edit Language') }} ({{ get_language_name($language) }})
                        </h1>
                    </div>
                    <div class="col-auto ms-auto d-print-none">
                        <a href="{{ route('admin.languages.set_default', ['language' => $language]) }}" class="btn btn-primary">
                            {{ __('Set as Default') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="page-body">
            <div class="container">
                <div class="row row-deck row-cards">
                    <div class="col-12">
                        <div id="app" class="row">
                            <form action="{{ route('admin.languages.translations', ['language' => $language]) }}" method="get">
                                <div class="panel p-0 m-0">
                                    <div class="panel-header">
                                        {{ __('Translations') }}
                                        <div class="flex flex-grow justify-end items-center">
                                            @include('admin.languages.translations._search', ['name' => 'filter', 'value' => Request::get('filter')])
                                            @include('admin.languages.translations._select', ['name' => 'language', 'items' => $languages, 'submit' => true, 'selected' => $language])
                                        </div>
                                    </div>
                    
                                    <div class="panel-body">
                                        @if(count($translations))
                                            <table>
                                                <thead>
                                                    <tr>
                                                        <th class="w-1/5 uppercase font-thin d-none">{{ __('Group') }}</th>
                                                        <th class="w-1/5 uppercase font-thin d-none">{{ __('Key') }}</th>
                                                        <th class="uppercase font-thin">{{ config('app.locale') }}</th>
                                                        <th class="uppercase font-thin">{{ $language }}</th>
                                                    </tr>
                                                </thead>
                    
                                                <tbody>
                                                    @foreach($translations as $type => $items)
                                                        @foreach($items as $group => $translations)
                                                            @foreach($translations as $key => $value)
                                                                @if(!is_array($value[config('app.locale')]))
                                                                    <tr>
                                                                        <td class="d-none">{{ $group }}</td>
                                                                        <td class="d-none">{{ $key }}</td>
                                                                        <td>{{ $value[config('app.locale')] }}</td>
                                                                        <td>
                                                                            <translation-input 
                                                                                initial-translation="{{ $value[$language] }}" 
                                                                                language="{{ $language }}" 
                                                                                group="{{ $group }}" 
                                                                                translation-key="{{ $key }}" 
                                                                                route="admin/languages">
                                                                            </translation-input>
                                                                        </td>
                                                                    </tr>
                                                                @endif
                                                            @endforeach
                                                        @endforeach
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_body_scripts') 
    <script src="{{ asset('assets/js/language.js') }}"></script>
@endpush