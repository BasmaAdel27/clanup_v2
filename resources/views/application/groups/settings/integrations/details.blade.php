@extends('layouts.app', [
    'seo_title' => $integration->name,
])

@section('content')
    <section class="container py-5">
        <div class="row gx-5">
            @include('application.groups.settings._sidebar', ['page' => 'integrations'])

            <form class="col-12 col-lg-8 mb-5 mb-lg-0" action="{{ route('groups.settings.integrations.details.update', ['group' => $group->slug, 'integration' => $integration->slug]) }}" method="POST">
                @csrf
                <div class="page-header mb-4 d-print-none m-0">
                    <div class="row align-items-center">
                        <div class="col">
                            <a class="page-pretitle" href="{{ route('groups.settings.integrations', ['group' => $group->slug]) }}">
                                {{ __('Integrations') }}
                            </a>
                            <h1>
                                {{ $integration->name }}
                            </h1>
                        </div>
                    </div>
                </div>

                @include('layouts._form_errors')
                
                @include('integrations.' . $integration->slug . '._settings_form')
                
                <div class="float-end">
                    <button type="submit" class="btn btn-primary">{{ __('Update settings') }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection