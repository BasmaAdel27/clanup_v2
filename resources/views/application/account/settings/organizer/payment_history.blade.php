@extends('layouts.app', [
    'seo_title' => __('Payment History'),
    'page' => 'account.organizer'
])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                @include('application.account.settings._sidebar', ['page' => 'organizer'])

                <div class="col-lg-8">
                    <a href="{{ route('account.settings.organizer') }}">< {{ __('Back') }}</a>
                    <h1>{{ __('Payment History') }}</h1>
                    
                    <div class="card mt-4">
                        @if($orders->count() > 0)
                            <div class="table-responsive">
                                <table class="table card-table table-vcenter text-nowrap datatable">
                                    <thead>
                                        <tr> 
                                            <th class="w-30px" class="text-center">{{ __('ID') }}</th>
                                            <th>{{ __('Plan') }}</th>
                                            <th>{{ __('Interval') }}</th>
                                            <th>{{ __('Amount') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th class="text-end">{{ __('Date') }}</th>
                                        </tr> 
                                    </thead> 
                                    <tbody class="list" id="orders">
                                        @foreach ($orders as $order)
                                            <tr> 
                                                <td>
                                                    <p class="mb-0">{{ get_system_setting('order_prefix') }}{{ $order->id }}</p>
                                                </td>
                                                <td>
                                                    @if($order->plan)
                                                        <p class="mb-0">{{ $order->plan->name }}</p>
                                                    @else
                                                        <p class="mb-0">-</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if($order->plan)
                                                        <p class="mb-0"> {{ __($order->plan->invoice_interval) }}</p>
                                                    @else
                                                        <p class="mb-0">-</p>
                                                    @endif
                                                </td>
                                                <td>
                                                    <p class="mb-0">{{ money($order->amount, $order->currency, true) }}</p>
                                                </td>
                                                <td>
                                                    {{ $order->payment_type }} 
                                                </td>
                                                <td class="text-end">{{ $order->created_at->format('M d, Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @else
                            <div class="row justify-content-center card-body pb-5">
                                <p class="h4">{{ __('No payments yet') }}</p>
                            </div>
                        @endif
                    </div>
                    @if ($orders->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection