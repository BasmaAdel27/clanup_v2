@extends('layouts.app', [
    'seo_title' => __('Notification Settings'),
    'page' => 'account.notification_settings'
])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                @include('application.account.settings._sidebar', ['page' => 'notification_settings'])

                <div class="col-lg-8">
                    <h1>{{ __('Notification Settings') }}</h1>
                    <p class="text-muted fs-4">{{ __('Manage your email notification settings') }}</p>
                    <hr>

                    <form action="{{ route('account.settings.notifications.update') }}" method="POST">
                        @csrf

                        <div class="divide-y">
                            @foreach ($types as $type)
                                <div>
                                    <label class="row">
                                        <span class="col">
                                            <p class="mb-0">{{ __($type->display_text) }}</p>
                                        </span>
                                        <span class="col-auto">
                                            <label class="form-check form-check-single form-switch">
                                                <input type="hidden" name="type[{{ $type->id }}]" value="0">
                                                <input class="form-check-input" name="type[{{ $type->id }}]" value="1" type="checkbox" {{ $type->status ? 'checked=""' : '' }}>
                                            </label>
                                        </span>
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="float-end mt-4">
                            <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection