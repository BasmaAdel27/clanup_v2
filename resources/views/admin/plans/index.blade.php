@extends('layouts.admin', ['page' => 'plans'])

@section('title', __('Plans'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Plans') }}
                    </h1>
                </div>
                <div class="col-auto ms-auto d-print-none">
                    <a href="{{ route('admin.plans.create') }}" class="btn btn-primary">
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
                    @include('admin.plans._filters')
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Plans') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Name') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Yearly Price') }}</th>
                                        <th>{{ __('Trial Period') }}</th>
                                        <th>{{ __('Created at') }}</th>
                                        <th class="text-end">{{ __('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="list" id="plans">
                                    @foreach ($plans as $plan)
                                        <tr>
                                            <td>
                                                <p class="mb-0">{{ $plan->id }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $plan->name }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ money($plan->price, $plan->currency, true) }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ money($plan->yearly->price, $plan->yearly->currency, true) }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $plan->trial_period }} {{ __('Days') }}</p>
                                            </td>
                                            <td>{{ $plan->created_at->format('Y-m-d') }}</td>
                                            <td class="text-end">
                                                <a href="{{ route('admin.plans.edit', $plan->id) }}">
                                                    {{ __('Edit') }}
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @if ($plans->hasPages())
                            <div class="card-footer d-flex align-items-center justify-content-center">
                                {{ $plans->links() }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
