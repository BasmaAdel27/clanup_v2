@extends('layouts.app', [
    'seo_title' => __('Add a new sponsor'),
])

@section('content')
    <section class="container py-5">
        <div class="row gx-5">
            @include('application.groups.settings._sidebar', ['page' => 'sponsors'])

            <div class="col-12 col-lg-8 mb-5 mb-lg-0">
                <h1 class="mb-4">{{ __('Add a new sponsor') }}</h1>

                <form action="{{ route('groups.settings.sponsors.store', ['group' => $group->slug]) }}" method="POST" enctype="multipart/form-data">
                    @include('layouts._form_errors')
                    @csrf
                    
                    @include('application.groups.settings.sponsors._form')
                </form>
            </div>
        </div>
    </section>
@endsection