@extends('layouts.app', [
    'seo_title' => __('Social Settings'),
    'page' => 'account.general.details'
])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                @include('application.account.settings._sidebar', ['page' => 'general'])

                <div class="col-lg-8">
                    <a href="{{ route('account.settings.general') }}">< {{ __('Back') }}</a>
                    <h1>{{ __('Social Settings') }}</h1>
                    <hr>

                    <form action="{{ route('account.settings.general.social.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="facebook">{{ __('Facebook') }}</label>
                                <div class="input-group"><span class="input-group-text">@</span>
                                    <input class="form-control" type="text" name="facebook" value="{{ $auth_user->getSetting('facebook') }}" placeholder="{{ __('Username') }}">
                                </div>
                                @error('facebook')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="instagram">{{ __('Instagram') }}</label>
                                <div class="input-group"><span class="input-group-text">@</span>
                                    <input class="form-control" type="text" name="instagram" value="{{ $auth_user->getSetting('instagram') }}" placeholder="{{ __('Username') }}">
                                </div>
                                @error('instagram')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="twitter">{{ __('Twitter') }}</label>
                                <div class="input-group"><span class="input-group-text">@</span>
                                    <input class="form-control" type="text" name="twitter" value="{{ $auth_user->getSetting('twitter') }}" placeholder="{{ __('Username') }}">
                                </div>
                                @error('twitter')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                            <div class="mb-4 col-md-6">
                                <label class="form-label" for="linkedin">{{ __('Linkedin') }}</label>
                                <div class="input-group"><span class="input-group-text">@</span>
                                    <input class="form-control" type="text" name="linkedin" value="{{ $auth_user->getSetting('linkedin') }}" placeholder="{{ __('Username') }}">
                                </div>
                                @error('linkedin')<small class="form-text text-danger">{{ $message }}</small>@enderror
                            </div>
                        </div>
                        <div class="text-end">
                            <button class="btn btn-primary" type="submit">{{ __('Update') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection