@extends('layouts.admin', ['page' => 'pages'])

@section('title', __('Pages'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Pages') }}
                    </h1>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
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
                            <h3 class="card-title">{{ __('Pages') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Slug') }}</th>
                                        <th>{{ __('Status') }}</th> 
                                        <th>{{ __('Created at') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list" id="pages">
                                    @foreach ($pages as $page)
                                        <tr>
                                            <td>
                                                <p class="mb-0">{{ $page->id }}</p>
                                            </td>
                                            <td>
                                                <a class="mb-0 text-truncate" target="_blank" href="{{ route('page.show', $page->slug) }}">
                                                    {{ $page->name }}
                                                </a>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $page->slug }}</p>
                                            </td>
                                            <td>
                                                @if($page->is_active)
                                                    <div class="badge bg-success fs-7">
                                                        {{ __('Enabled') }}
                                                    </div>
                                                @else
                                                    <div class="badge bg-danger fs-7">
                                                        {{ __('Disabled') }}
                                                    </div>
                                                @endif
                                            </td>
                                            <td>{{ $page->created_at->format('Y-m-d') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.pages.edit', $page->slug) }}">
                                                    {{ __('Edit') }}
                                                </a>
                                                @if ($page->is_deletable)
                                                    <a href="{{ route('admin.pages.delete', $page->slug) }}" class="ms-2 text-danger delete-confirm" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">
                                                        {{ __('Delete') }}
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($pages->hasPages())
                                <div class="card-footer d-flex align-items-center justify-content-center">
                                    {{ $pages->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
