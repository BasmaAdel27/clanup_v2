@extends('layouts.app', [
    'seo_title' => __('Cancel your organizer subscription'),
    'page' => 'account.organizer'
])

@section('content')
    <section class="py-5">
        <div class="container">
            <div class="row gx-5">
                @include('application.account.settings._sidebar', ['page' => 'organizer'])

                <div class="col-lg-8">
                    <a href="{{ route('account.settings.organizer') }}">< {{ __('Back') }}</a>
                    <h1>{{ __('Cancel Your Subscription') }}</h1>
                    <p>{{ __('By cancelling, you will lose your group and member information and the ability to organize events when your current subscription expires on :date', ['date' => $current_subscription->ends_at->format('M d, Y')]) }}</p>
                    
                    <form action="{{ route('account.settings.organizer.cancel_store') }}" method="POST">
                        @csrf
                        <p class="fw-bold">{{ __('Reason for cancelling') }}</p>
                        <div class="mb-4">
                            <div class="form-check">
                                <input class="form-check-input" id="value_1" name="reason_for_cancel" value="1" type="radio">
                                <label class="form-check-label" for="value_1">{{ __('Not sure how to use the platform') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="value_2" name="reason_for_cancel" value="2" type="radio">
                                <label class="form-check-label" for="value_2">{{ __('Missing features I need') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="value_3" name="reason_for_cancel" value="3" type="radio">
                                <label class="form-check-label" for="value_3">{{ __('Don\'t like the ads on the platform') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="value_4" name="reason_for_cancel" value="4" type="radio">
                                <label class="form-check-label" for="value_4">{{ __('Switching to another community building platform') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="value_5" name="reason_for_cancel" value="5" type="radio">
                                <label class="form-check-label" for="value_5">{{ __('Cost related reasons') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="value_6" name="reason_for_cancel" value="6" type="radio">
                                <label class="form-check-label" for="value_6">{{ __('Technical issues') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="value_7" name="reason_for_cancel" value="7" type="radio">
                                <label class="form-check-label" for="value_7">{{ __('Can\'t host in-person events during Covid-19') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="value_8" name="reason_for_cancel" value="8" type="radio">
                                <label class="form-check-label" for="value_8">{{ __('Not enough time to organize events') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="value_9" name="reason_for_cancel" value="9" type="radio">
                                <label class="form-check-label" for="value_9">{{ __('Not enough interest from members') }}</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" id="value_10" name="reason_for_cancel" value="10" type="radio">
                                <label class="form-check-label" for="value_10">{{ __('Other') }}</label>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-danger">{{ __('Cancel subscription') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </section>
@endsection