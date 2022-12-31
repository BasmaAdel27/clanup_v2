@extends('layouts.admin', ['page' => 'topic_categories'])

@section('title', __('Topic Categories'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Topic Categories') }}
                    </h1>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('admin.topic_categories.create') }}" class="btn btn-primary">
                        {{ __('Create new') }}
                    </a>
                    <a href="{{ route('admin.topic_categories.delete_demo_topic_categories') }}" class="text-danger delete-confirm" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This action will delete all default topic categories which is added when you install the script.') }}">
                        {{ __('Delete All Defaults') }}
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    @include('admin.topic_categories._filters')
                </div>

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
                                <tbody class="list" id="topic_categories">
                                    @foreach ($topic_categories as $topic_category)
                                        <tr>
                                            <td>
                                                <p class="mb-0">{{ $topic_category->id }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $topic_category->name }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $topic_category->slug }}</p>
                                            </td>
                                            <td>
                                                {{ $topic_category->created_at->format('Y-m-d') }}
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.topic_categories.edit', $topic_category->id) }}">
                                                    {{ __('Edit') }}
                                                </a>
                                                <a href="{{ route('admin.topic_categories.delete', $topic_category->id) }}" class="ms-2 text-danger delete-confirm" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">
                                                    {{ __('Delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($topic_categories->hasPages())
                                <div class="card-footer d-flex align-items-center justify-content-center">
                                    {{ $topic_categories->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
