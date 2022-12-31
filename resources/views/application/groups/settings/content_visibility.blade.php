@extends('layouts.app', [
    'seo_title' => __('Content Visibility Settings'),
])

@section('content')
    <section class="container py-5">
        <div class="row gx-5">
            @include('application.groups.settings._sidebar', ['page' => 'content_visibility'])

            <form class="col-12 col-lg-8 mb-5 mb-lg-0" action="{{ route('groups.settings.content_visibility.update', ['group' => $group->slug]) }}" method="POST">
                @csrf
                <h1 class="mb-4">{{ __('Content Visibility') }}</h1>
                <p>{{ __('When you change your group\'s content visibility settings, all members and organizers of the group will receive an email notification.') }}</p>
                
                <div class="alert alert-dark mb-4" role="alert">
                    <i class="fas fa-exclamation-circle pe-2"></i>
                    {{ __('Once you make a group private, it cannot be changed back. This ensures that members who join private groups can maintain their privacy.') }}
                </div>

                <div class="form-group">
                    <div class="form-check">
                        <input class="form-check-input" id="public" name="content_visibility" value="public" type="radio" {{ $group->isOpen() ? 'checked=""' : 'disabled=""' }}>
                        <label class="form-check-label fw-bold" for="public">{{ __('Public') }}</label>
                    </div>
                    <p class="fs-7">{{ __('This group\'s content, including its members and event details, is visible to public.') }}</p>

                    <div class="form-check">
                        <input class="form-check-input" id="private" name="content_visibility" value="private" type="radio" {{ $group->isClosed() ? 'checked="" disabled=""' : '' }}>
                        <label class="form-check-label fw-bold" for="private">{{ __('Private') }}</label>
                    </div>
                    <p class="fs-7">{{ __('Only members of this group can see its full content, including details about its members and events. Some information about the group is public.') }}</p>
                    <p>{{ __('These basic details about groups and events are always public, regardless of your content visibility setting:') }}</p>
                    <ul class="fs-7">
                        <li>
                            <p class="mb-0">{{ __('Basic group information') }}</p>
                            <p class="text-muted">{{ __('Group name, featured photo, description, number of members, date founded') }}</p>
                        </li>
                        <li>
                            <p class="mb-0">{{ __('Basic organizer information') }}</p>
                            <p class="text-muted">{{ __('Organizer name and profile photo') }}</p>
                        </li>
                        <li>
                            <p class="mb-0">{{ __('Basic event details') }}</p>
                            <p class="text-muted">{{ __('Titles, date, time and number of RSVPs') }}</p>
                        </li>
                    </ul>
                </div>

                <div class="float-end">
                    <button type="submit" class="btn btn-primary" @if($group->isClosed())disabled=""@endif>{{ __('Update settings') }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection