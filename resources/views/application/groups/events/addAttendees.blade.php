@extends('layouts.app', [
    'seo_title' => $event->title,
    'seo_description' => substr(strip_tags($event->description ), 0, 180),
    'seo_image' => $event->image,
    'fixed_header' => true,
])

@section('content')
    <section>
        <div class="border-bottom top-info">
            <div class="container py-3">
                <div class="row">
                    <div class="col-12">
                        <time datetime="{{ convertToLocal($event->starts_at, 'U') }}">{{ convertToLocal($event->starts_at) }}</time>
                    </div>
                    <div class="col-12">
                        <h1>{{ $event->title }}</h1>
                    </div>

                    <div class="col-12">
                        <div class="d-flex align-items-center">
                            <img class="avatar border" src="{{ $group->createdBy->avatar }}" alt="{{ $group->createdBy->full_name }}">
                            <p class="ms-2 mb-0">
                                <span class="text-muted">{{ __('Hosted by') }}</span><br>
                                <span>{{ $group->createdBy->full_name }}</span>
                            </p>
                        </div>
                    </div>
                    <div class="col-12">
                        <a href="{{ route('groups.events.show', ['group' => $event->group, 'event' => $event]) }}">
                            < {{ __('Back to event') }}</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white border sticky-top bottom-info py-3 d-none">
            <div class="container">
                <time class="fs-5" datetime="{{ convertToLocal($event->starts_at, 'U') }}">{{ convertToLocal($event->starts_at) }}</time>
                <h4 class="mb-0">{{ $event->title }}</h4>
                <div class="col-12">
                    <a href="{{ route('groups.events.show', ['group' => $event->group, 'event' => $event]) }}">
                        < {{ __('Back to event') }}</a>
                </div>
            </div>
        </div>

        <div class="container py-5">
            <form class="col-12 col-lg-8 mb-5 mb-lg-0" action="{{route('groups.events.addAttendees.update', ['group' => $event->group, 'event' => $event])}}" method="POST" style="margin: auto">
                @csrf
                <h1 class="mb-4">{{ __('Add Attendees') }}</h1>

                <div class="form-group mb-4">
                    <label class="form-label" for="group_welcome_message">{{ __('Choose Attendees') }}</label>
                    <select class="basic-multiple form-control" name="user_id[]" multiple="multiple">
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ in_array($user->id,isset($members) ? $members : [])
                             ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <input type="hidden" value="{{$group->id}}" name="group_id">
                <input type="hidden" value="20" name="membership">
                <div class="float-end">
                    <button type="submit" class="btn btn-primary">{{ __('Submit') }}</button>
                </div>
            </form>

        </div>

    </section>
@endsection



@push('page_body_scripts')
    <script>
        $(document).ready(function() {
            $(window).scroll(function() {
                var height = $('.top-info').outerHeight() + $('header').outerHeight();
                var scrollTop = $(window).scrollTop();
                if (scrollTop >= height - 40) {
                    $('.bottom-info').removeClass('d-none');
                } else {
                    $('.bottom-info').addClass('d-none');
                }
            });
        });
    </script>
@endpush
