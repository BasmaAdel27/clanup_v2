@extends('layouts.app', [
    'seo_title' => __('Group Topics'),
])

@section('content')
    <section class="container py-5">
        <div class="row gx-5">
            @include('application.groups.settings._sidebar', ['page' => 'topics'])

            <div class="col-12 col-lg-8 mb-5 mb-lg-0">
                @livewire('topic.interests', ['model' => $group])
            </div>
        </div>
    </section>
@endsection