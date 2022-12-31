@extends('layouts.admin', ['page' => 'subscriptions'])

@section('title', __('Subscriptions'))
    
@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Subscriptions') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    @include('admin.subscriptions._filters')
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Subscriptions') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Plan') }}</th>
                                        <th>{{ __('Status') }}</th>
                                        <th>{{ __('Subscribed at') }}</th>
                                        <th>{{ __('Ends at') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr> 
                                </thead> 
                                <tbody class="list" id="subscriptions">
                                    @foreach ($subscriptions as $subscription)
                                        <tr>
                                            <td>
                                                <p class="mb-0">{{ $subscription->id }}</p>
                                            </td>
                                            <td>
                                                @if ($subscription->user->id)
                                                    <a class="mb-0" target="_blank" href="{{ route('admin.users.edit', $subscription->user->id) }}">{{ $subscription->user->full_name }}</a>
                                                @else
                                                    <p class="mb-0">-</p>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($subscription->plan->id)
                                                    <a class="mb-0" target="_blank" href="{{ route('admin.plans.edit', $subscription->plan->id) }}">{{ $subscription->plan->name }}</a>
                                                @else
                                                    <p class="mb-0">-</p>
                                                @endif
                                            </td>
                                            <td>
                                                {!! $subscription->html_status !!}
                                            </td>
                                            <td>
                                                {{ $subscription->created_at->format('Y-m-d') }}
                                            </td>
                                            <td>
                                                {{ $subscription->ends_at->format('Y-m-d') }}
                                            </td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.subscriptions.cancel', $subscription->id) }}" class="ms-2 text-danger delete-confirm" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">
                                                    {{ __('Cancel') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($subscriptions->hasPages())
                                <div class="card-footer d-flex align-items-center justify-content-center">
                                    {{ $subscriptions->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
