@extends('layouts.admin', ['page' => 'orders'])

@section('title', __('Orders'))
    
@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Orders') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    @include('admin.orders._filters')
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Orders') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Transaction ID') }}</th>
                                        <th>{{ __('User') }}</th>
                                        <th>{{ __('Plan') }}</th>
                                        <th>{{ __('Price') }}</th>
                                        <th>{{ __('Payment Type') }}</th>
                                        <th>{{ __('Ordered at') }}</th>
                                    </tr> 
                                </thead> 
                                <tbody class="list" id="orders">
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>
                                                <p class="mb-0">{{ get_system_setting('order_prefix') }}{{ $order->id }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $order->transaction_id }}</p>
                                            </td>
                                            <td>
                                                @if ($order->user->id)
                                                    <a class="mb-0" target="_blank" href="{{ route('admin.users.edit', $order->user->id) }}">{{ $order->user->full_name }}</a>
                                                @else
                                                    <a class="mb-0">-</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if($order->plan->id)
                                                    <a class="mb-0" target="_blank" href="{{ route('admin.plans.edit', $order->plan->id) }}">{{ $order->plan->name }}</a>
                                                @else
                                                    <a class="mb-0">-</a>
                                                @endif
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ money($order->amount, $order->currency, true) }}</p>
                                            </td>
                                            <td>
                                                {{ $order->payment_type }} 
                                            </td>
                                            <td>
                                                {{ $order->created_at->format('Y-m-d') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if ($orders->hasPages())
                                <div class="card-footer d-flex align-items-center justify-content-center">
                                    {{ $orders->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
