@extends('layouts.admin', ['page' => 'users'])

@section('title', __('Users'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Users') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    @include('admin.users._filters')
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Users') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Full Name') }}</th>
                                        <th>{{ __('Email') }}</th>
                                        <th>{{ __('Plan') }}</th>
                                        <th>{{ __('Subscription') }}</th>
                                        <th>{{ __('Role') }}</th>
                                        <th>{{ __('Registered at') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list" id="users">
                                    @foreach ($users as $user)
                                        <tr>
                                            <td>
                                                <a href="{{ route('admin.users.edit', $user->id) }}" class="text-decoration-none">{{ $user->id }}</a>
                                            </td>
                                            <td>
                                                <a href="{{ route('profile', $user->username) }}" target="_blank" class="text-decoration-none" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ __('Click to see profile') }}">{{ $user->full_name }}</a>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $user->email }}</p>
                                            </td>
                                            <td>
                                                @if($user->currentSubscriptionPlan())
                                                    <a class="mb-0" href="{{ route('admin.plans.edit', $user->currentSubscriptionPlan()->id) }}">{{ $user->currentSubscriptionPlan()->name }}</a>
                                                @endif
                                            </td>
                                            <td>
                                                {!! optional($user->subscription())->html_status !!}
                                            </td>
                                            <td class="text-capitalize">{{ $user->role }}</td>
                                            <td>{{ $user->created_at->format('Y-m-d') }}</td>
                                            <td class="text-end">
                                                @if ($auth_user->id != $user->id)
                                                    <div class="dropdown">
                                                        <a href="#" class="link-secondary" data-bs-toggle="dropdown" aria-expanded="true">
                                                            <i class="fas fa-ellipsis-v"></i>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <a class="dropdown-item" href="{{ route('impersonate', $user->id) }}">
                                                                {{ __('Login as user') }}
                                                            </a>
                                                            <a class="dropdown-item" href="{{ route('admin.users.edit', $user->id) }}">
                                                                {{ __('Edit') }}
                                                            </a>
                                                            <a class="dropdown-item text-danger delete-confirm" href="{{ route('admin.users.delete', $user->id) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">
                                                                {{ __('Delete') }}
                                                            </a>
                                                        </div>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($users->hasPages())
                                <div class="card-footer d-flex align-items-center justify-content-center">
                                    {{ $users->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
