@extends('layouts.admin', ['page' => 'currencies'])

@section('title', __('Currency Settings'))

@section('content')
    <div class="container">
        <div class="page-header d-print-none">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle">
                        {{ __('Settings') }}
                    </div>
                    <h1 class="page-title">
                        {{ __('Currency Settings') }}
                    </h1>
                </div>
            </div>
        </div>
    </div>

    <div class="page-body">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">{{ __('Currencies') }}</h3>
                        </div>
                        <div class="table-responsive">
                            <table class="table card-table table-vcenter text-nowrap datatable">
                                <thead>
                                    <tr>
                                        <th class="word-break-keep">{{ __('ID') }}</th>
                                        <th class="word-break-keep">{{ __('Name') }}</th>
                                        <th class="word-break-keep">{{ __('Code') }}</th>
                                        <th class="word-break-keep">{{ __('Symbol') }}</th>
                                        <th class="word-break-keep">{{ __('Precision') }}</th>
                                        <th class="word-break-keep">{{ __('Thousands Separator') }}</th>
                                        <th class="word-break-keep">{{ __('Decimal Separator') }}</th>
                                        <th class="word-break-keep">{{ __('Status') }}</th>
                                        <th class="word-break-keep">{{ __('Action') }}</th>
                                    </tr> 
                                </thead> 
                                <tbody class="list" id="currencies">
                                    @foreach ($currencies as $currency)
                                        <tr>
                                            <td>
                                                <p class="mb-0 word-break-keep">{{ $currency->id }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $currency->name }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $currency->short_code }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $currency->symbol }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $currency->precision }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $currency->thousands_separator }}</p>
                                            </td>
                                            <td>
                                                <p class="mb-0">{{ $currency->decimal_mark }}</p>
                                            </td>
                                            <td>
                                                @if ($currency->enabled)
                                                    <div class="badge bg-success">{{ __('Enabled') }}</div>
                                                @else
                                                    <div class="badge bg-danger">{{ __('Disabled') }}</div>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($currency->enabled)
                                                    <a class="text-danger" href="{{ route('admin.settings.currencies.disable', ['code' => $currency->code]) }}">{{ __('Disable') }}</a>
                                                @else
                                                    <a class="text-primary" href="{{ route('admin.settings.currencies.enable', ['code' => $currency->code]) }}">{{ __('Enable') }}</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
