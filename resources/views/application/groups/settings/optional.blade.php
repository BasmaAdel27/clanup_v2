@extends('layouts.app', [
    'seo_title' => __('Optional Settings'),
])

@section('content')
    <section class="container py-5">
        <div class="row gx-5">
            @include('application.groups.settings._sidebar', ['page' => 'optional'])

            <form class="col-12 col-lg-8 mb-5 mb-lg-0" action="{{ route('groups.settings.optional.update', ['group' => $group->slug]) }}" method="POST">
                @csrf
                <h1 class="mb-4">{{ __('Optional Settings') }}</h1>
                
                <div class="form-group mt-2 mb-4">
                    <label class="form-label" for="facebook">{{ __('Facebook') }}</label>
                    <input class="form-control" type="text" name="facebook" value="{{ $group->getSetting('facebook_url') }}" placeholder="{{ __('Link') }}">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" for="instagram">{{ __('Instagram') }}</label>
                    <input class="form-control" type="text" name="instagram" value="{{ $group->getSetting('instagram_url') }}" placeholder="{{ __('Link') }}">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" for="twitter">{{ __('Twitter') }}</label>
                    <input class="form-control" type="text" name="twitter" value="{{ $group->getSetting('twitter_url') }}" placeholder="{{ __('Link') }}">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" for="linkedin">{{ __('Linkedin') }}</label>
                    <input class="form-control" type="text" name="linkedin" value="{{ $group->getSetting('linkedin_url') }}" placeholder="{{ __('Link') }}">
                </div>

                <div class="form-group mb-4">
                    <label class="form-label" for="website">{{ __('Website') }}</label>
                    <input class="form-control" type="text" name="website" value="{{ $group->getSetting('website_url') }}" placeholder="{{ __('Link') }}">
                </div>

                <div class="float-end">
                    <button type="submit" class="btn btn-primary">{{ __('Update settings') }}</button>
                </div>
            </form>
        </div>
    </section>
@endsection