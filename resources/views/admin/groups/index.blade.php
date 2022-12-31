@extends('layouts.admin', ['page' => 'groups'])

@section('title', __('Groups'))
    
@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Groups') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    @include('admin.groups._filters')
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Groups') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Organizer') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Created at') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr> 
                                </thead> 
                                <tbody class="list" id="groups">
                                    @foreach ($groups as $group)
                                        <tr>
                                            <td>
                                                <p class="mb-0">{{ $group->id }}</p>
                                            </td>
                                            <td>
                                                @if ($group->deleted_at)
                                                    <a class="mb-0" href="javascript:void()">{{ $group->name }}</a>
                                                @else
                                                    <a class="mb-0" target="_blank" href="{{ route('groups.about', ['group' => $group->slug]) }}">{{ $group->name }}</a>
                                                @endif
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ optional($group->createdBy)->full_name }}</p>
                                            </td>
                                            <td>
                                                @if ($group->deleted_at)
                                                    <p class="mb-0 text-danger">{{ __('Deleted') }}</p>
                                                @else
                                                    @if ($group->isOpen())
                                                        <p class="mb-0 text-success">{{ __('Public Group') }}</p>
                                                    @else
                                                        <p class="mb-0 text-info">{{ __('Private Group') }}</p>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $group->created_at->format('Y-m-d') }}</p>
                                            </td>
                                            <td class="text-end">
                                                <div class="dropdown">
                                                    <a href="#" class="link-secondary" data-bs-toggle="dropdown" aria-expanded="true">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @if (optional($group->createdBy)->id)
                                                            <a class="dropdown-item" href="{{ route('impersonate', optional($group->createdBy)->id) }}">
                                                                {{ __('Login & Manage') }}
                                                            </a>
                                                        @endif
                                                        @if (!$group->deleted_at)
                                                            <a class="dropdown-item text-danger delete-confirm" href="{{ route('admin.groups.delete', $group->id) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">
                                                                {{ __('Delete') }}
                                                            </a>
                                                        @endif
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($groups->hasPages())
                            <div class="card-footer d-flex align-items-center justify-content-center">
                                {{ $groups->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
