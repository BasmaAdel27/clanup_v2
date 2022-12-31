@extends('layouts.app', [
    'seo_title' => __('Privacy Settings'),
    'page' => 'account.privacy'
])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                @include('application.account.settings._sidebar', ['page' => 'privacy'])

                <div class="col-lg-8">
                    <h1>{{ __('Privacy Settings') }}</h1>
                    <p class="text-muted">{{ __('Control who can contact you and the information others can see on your public profile.') }}</p>
                    <hr>

                    <form action="{{ route('account.settings.privacy.update') }}" method="POST">
                        @csrf

                        <div class="divide-y">
                            <div>
                                <label class="row">
                                    <span class="col">
                                        <p class="mb-0">{{ __('Show :app_name groups on profile', ['app_name' => $application_name]) }}</p>
                                        <small class="text-muted">{{ __('On your profile, anyone can see all the :app_name groups you belong to.', ['app_name' => $application_name]) }}</small>
                                    </span>
                                    <span class="col-auto">
                                        <label class="form-check form-check-single form-switch">
                                            <input class="form-check-input" name="show_groups_on_profile" value="1" type="checkbox" {{ $auth_user->getSetting('show_groups_on_profile') ? 'checked=""' : '' }}>
                                        </label>
                                    </span>
                                </label>
                            </div>
                            <div>
                                <label class="row">
                                    <span class="col">
                                        <p class="mb-0">{{ __('Show interests on profile') }}</p>
                                        <small class="text-muted">{{ __('On your profile, anyone can see your list of interests.') }}</small>
                                    </span>
                                    <span class="col-auto">
                                        <label class="form-check form-check-single form-switch">
                                            <input class="form-check-input" name="show_interests_on_profile" value="1" type="checkbox" {{ $auth_user->getSetting('show_interests_on_profile') ? 'checked=""' : '' }}>
                                        </label>
                                    </span>
                                </label>
                            </div>
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
