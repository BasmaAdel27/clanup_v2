@extends('layouts.app', [
    'seo_title' => __('Sponsors'),
])

@section('content')
    <section class="container py-5">
        <div class="row gx-5">
            @include('application.groups.settings._sidebar', ['page' => 'sponsors'])

            <div class="col-12 col-lg-8 mb-5 mb-lg-0">
                <div class="row mb-4">
                    <div class="col">
                        <h1>{{ __('Sponsors') }}</h1>
                    </div>
                    @can('store_sponsor', $group)
                        <div class="col col-auto">
                            <a class="btn btn-primary" href="{{ route('groups.settings.sponsors.create', ['group' => $group->slug]) }}">{{ __('Add sponsor') }}</a>
                        </div>
                    @endcan
                </div>

                <div class="alert alert-dark mb-4" role="alert">
                    <i class="fas fa-exclamation-circle pe-2"></i>
                    {{ __('List your sponsors at your event\'s page.') }}
                </div>

                <div class="list-group card-list-group border">
                    @foreach ($sponsors as $sponsor)
                        <div class="list-group-item p-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-auto">
                                    <img class="avatar rounded shadow-0 border" src="{{ $sponsor->avatar }}" alt="{{ $sponsor->name }}">
                                </div>
                                <div class="col">
                                    <p class="mb-0 fw-bold">{{ $sponsor->name }}</p>
                                    <p class="mb-0 text-muted">{{ $sponsor->description }}</p>
                                </div>
                                <div class="col-auto lh-1">
                                    <div class="dropdown">
                                        <a href="#" class="link-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @can('update_sponsor', $group)
                                                <a class="dropdown-item" href="{{ route('groups.settings.sponsors.edit', ['group' => $group->slug, 'sponsor' => $sponsor->id]) }}">{{ __('Edit') }}</a>
                                            @endcan
                                            
                                            @can('delete_sponsor', $group)
                                                <a class="dropdown-item text-danger" href="{{ route('groups.settings.sponsors.delete', ['group' => $group->slug, 'sponsor' => $sponsor->id]) }}">{{ __('Delete') }}</a>
                                            @endcan
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
@endsection