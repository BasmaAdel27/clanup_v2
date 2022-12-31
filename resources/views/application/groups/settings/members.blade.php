@extends('layouts.app', [
    'seo_title' => __('New member settings'),
])

@section('content')
    <section class="container py-5">
        <div class="row gx-5">
            @include('application.groups.settings._sidebar', ['page' => 'members'])

            <form class="col-12 col-lg-8 mb-5 mb-lg-0" action="{{ route('groups.settings.members.update', ['group' => $group->slug]) }}" method="POST">
                @csrf
                <h1 class="mb-4">{{ __('New member settings') }}</h1>
                
                <div class="row py-2 border-bottom">
                    <div class="col-8"><p>{{ __('New members must be approved by organizers') }}</p></div>
                    <div class="col-4 form-check form-switch">
                        <input type="hidden" name="new_members_need_approved" value="0">
                        <input class="form-check-input float-end" name="new_members_need_approved" value="1" type="checkbox" {{ $group->getSetting('new_members_need_approved') ? 'checked=""' : '' }}>
                    </div>
                </div>

                <div class="row py-2 border-bottom">
                    <div class="col-8"><p>{{ __('When members join, require profile photo') }}</p></div>
                    <div class="col-4 form-check form-switch">
                        <input type="hidden" name="new_members_need_pp" value="0">
                        <input class="form-check-input float-end" name="new_members_need_pp" value="1" type="checkbox" {{ $group->getSetting('new_members_need_pp') ? 'checked=""' : '' }}>
                    </div>
                </div>

                <div class="row py-2 mb-4">
                    <div class="col-8"><p>{{ __('Allow members to create discussions') }}</p></div>
                    <div class="col-4 form-check form-switch">
                        <input type="hidden" name="allow_members_create_discussion" value="0">
                        <input class="form-check-input float-end" name="allow_members_create_discussion" value="1" type="checkbox" {{ $group->getSetting('allow_members_create_discussion') ? 'checked=""' : '' }}>
                    </div>
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" for="group_welcome_message">{{ __('Welcome message to new members') }}</label>
                    <textarea class="form-control" name="welcome_message" rows="4">{{ $group->getSetting('welcome_message') }}</textarea>
                </div>

                <div class="float-end">
                    <button type="submit" class="btn btn-primary">{{ __('Update settings') }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection