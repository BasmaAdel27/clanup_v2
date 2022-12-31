@extends('layouts.admin', ['page' => 'blog_categories'])

@section('title', __('Blog Categories'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Blog Categories') }}
                    </h1>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('admin.blog_categories.create') }}" class="btn btn-primary">
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
                            <h3 class="card-title">{{ __('Categories') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Slug') }}</th>
                                        <th>{{ __('Created at') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list" id="blog_categories">
                                    @foreach ($blog_categories as $blog_category)
                                        <tr>
                                            <td>
                                                <p class="mb-0">{{ $blog_category->id }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $blog_category->name }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $blog_category->slug }}</p>
                                            </td>
                                            <td>{{ $blog_category->created_at->format('Y-m-d') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.blog_categories.edit', $blog_category->slug) }}">
                                                    {{ __('Edit') }}
                                                </a>
                                                <a href="{{ route('admin.blog_categories.delete', $blog_category->slug) }}" class="ms-2 text-danger delete-confirm" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">
                                                    {{ __('Delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($blog_categories->hasPages())
                            <div class="card-footer d-flex align-items-center justify-content-center">
                                {{ $blog_categories->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
