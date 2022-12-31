@extends('layouts.admin', ['page' => 'blogs'])

@section('title', __('Blogs'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Blogs') }}
                    </h1>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
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
                            <h3 class="card-title">{{ __('Blogs') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Created at') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list" id="blogs">
                                    @foreach ($blogs as $blog)
                                        <tr>
                                            <td>
                                                <p class="mb-0">{{ $blog->id }}</p>
                                            </td>
                                            <td>
                                                <a class="mb-0 text-truncate" target="_blank" href="{{ route('blog.show', $blog->slug) }}">
                                                    {{ Illuminate\Support\Str::limit($blog->name, 80, $end='...') }}
                                                </a>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $blog->blog_category->name }}</p>
                                            </td>
                                            <td>{{ $blog->created_at->format('Y-m-d') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.blogs.edit', $blog->slug) }}">
                                                    {{ __('Edit') }}
                                                </a>
                                                <a href="{{ route('admin.blogs.delete', $blog->slug) }}" class="ms-2 text-danger delete-confirm" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">
                                                    {{ __('Delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($blogs->hasPages())
                            <div class="card-footer d-flex align-items-center justify-content-center">
                                {{ $blogs->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
