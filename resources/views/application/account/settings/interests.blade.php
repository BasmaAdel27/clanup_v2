@extends('layouts.app', [
    'seo_title' => __('Interests'),
    'page' => 'account.interests'
])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                @include('application.account.settings._sidebar', ['page' => 'interests'])

                <div class="col-lg-8">
                    <h1>{{ __('Interests') }}</h1>
                    <p class="text-muted">{{ __('Manage your interests here') }}</p>
                    <hr>
                    
                    @livewire('topic.interests', ['model' => $auth_user])
                </div>
            </div>
        </div>
    </section>
@endsection