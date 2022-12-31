@extends('layouts.admin', ['page' => 'topics'])

@section('title', __('Topics'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Topics') }}
                    </h1>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('admin.topics.create') }}" class="btn btn-primary">
                        {{ __('Create new') }}
                    </a>
                    <a href="{{ route('admin.topics.delete_demo_topics') }}" class="text-danger delete-confirm" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This action will delete all default topics which is added when you install the script.') }}">
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
                    @include('admin.topics._filters')
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Topics') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Category') }}</th>
                                        <th>{{ __('Slug') }}</th>
                                        <th>{{ __('Created at') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list" id="topics">
                                    @foreach ($topics as $topic)
                                        <tr>
                                            <td>
                                                <p class="mb-0">{{ $topic->id }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $topic->name }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ optional($topic->topic_category)->name }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $topic->slug }}</p>
                                            </td>
                                            <td>{{ $topic->created_at->format('Y-m-d') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.topics.edit', $topic->id) }}">
                                                    {{ __('Edit') }}
                                                </a>
                                                <a href="{{ route('admin.topics.delete', $topic->id) }}" class="ms-2 text-danger delete-confirm" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">
                                                    {{ __('Delete') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($topics->hasPages())
                                <div class="card-footer d-flex align-items-center justify-content-center">
                                    {{ $topics->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
