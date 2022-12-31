@extends('layouts.app', [
    'seo_title' => __('Integrations'),
])

@section('content')
    <section class="container py-5">
        <div class="row gx-5">
            @include('application.groups.settings._sidebar', ['page' => 'integrations'])

            <div class="col-12 col-lg-8 mb-5 mb-lg-0">
                <h1 class="mb-4">{{ __('Integrations') }}</h1>
                
                @foreach ($integrations as $integration)
                    <div class="col-6 col-lg-4">
                        @include('integrations.' . $integration->slug . '._info_card')
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection