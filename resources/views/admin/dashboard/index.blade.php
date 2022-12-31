@extends('layouts.admin', ['page' => 'dashboard'])

@section('title', __('Admin Dashboard'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Overview') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Dashboard') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row row-deck row-cards">
                <div class="col-12">
                    <div class="row row-cards">
                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-blue text-white avatar">
                                                <i class="fas fa-ticket-alt"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $events_this_month }}
                                            </div>
                                            <div class="text-muted">
                                                {{ __('Events this month') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-blue text-white avatar">
                                                <i class="fas fa-ticket-alt"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $events_total }}
                                            </div>
                                            <div class="text-muted">
                                                {{ __('Events total') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-blue text-white avatar">
                                                <i class="fas fa-user-friends"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $groups_this_month }}
                                            </div>
                                            <div class="text-muted">
                                                {{ __('Groups this month') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-lg-3">
                            <div class="card card-sm">
                                <div class="card-body">
                                    <div class="row align-items-center">
                                        <div class="col-auto">
                                            <span class="bg-blue text-white avatar">
                                                <i class="fas fa-user-friends"></i>
                                            </span>
                                        </div>
                                        <div class="col">
                                            <div class="font-weight-medium">
                                                {{ $groups_total }}
                                            </div>
                                            <div class="text-muted">
                                                {{ __('Groups total') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Earnings in :currency', ['currency' => get_system_setting('application_currency')]) }}</h3>
                        </div>
                        <div class="card-body">
                            <div id="earnings-chart" class="chart-lg"></div>
                        </div>
                        <div class="card-footer">
                            <div class="row">
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <strong>{{ __('Users this month') }}</strong>
                                    <p class="mb-0">{{ $users_this_month }}</p>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <strong>{{ __('Users total') }}</strong>
                                    <p class="mb-0">{{ $users_total }}</p>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <strong>{{ __('Subscriptions this month') }}</strong>
                                    <p class="mb-0">{{ $subscriptions_this_month }}</p>
                                </div>
                                <div class="col-md-3 mb-3 mb-md-0">
                                    <strong>{{ __('Subscriptions total') }}</strong>
                                    <p class="mb-0">{{ $subscriptions_total }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_body_scripts')
    <script src="{{ asset('assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            window.ApexCharts && (new ApexCharts(document.getElementById('earnings-chart'), {
                series: [{
                    name: "{{ __('Earnings') }}",
                    data: @json($earnings->pluck('total'))
                }],
                chart: {
                    height: 450,
                    type: 'bar',
                },
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: @json($earnings->pluck('date')),
                    position: 'bottom',
                },
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " {{ get_system_setting('application_currency') }}";
                        }
                    }
                }
            })).render();
        });
    </script>
@endpush
