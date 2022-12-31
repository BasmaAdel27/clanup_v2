@extends('layouts.app', [
    'seo_title' => $event->title,
    'seo_description' => substr(strip_tags($event->description ), 0, 180),
    'seo_image' => $event->image,
    'fixed_header' => true,
])

@section('content')
    <section>
        <div class="border-bottom top-info">
            <div class="container py-5">
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
                </div>
            </div>
        </div>

        <div class="bg-white border sticky-top bottom-info py-3 d-none">
            <div class="container">
                <time class="fs-5" datetime="{{ convertToLocal($event->starts_at, 'U') }}">{{ convertToLocal($event->starts_at) }}</time>
                <h4 class="mb-0">{{ $event->title }}</h4>
            </div>
        </div>

        <div id="event" class="container py-5">
            <div class="row align-items-start">
                <div class="col-lg-8">
                    <div class="img-wrap ratio-16-9 mb-4">
                        <div class="img-content">
                            <img class="rounded border" src="{{ $event->image }}" alt="{{ $event->title }}">
                        </div>
                    </div>

                    <h3>{{ __('Details') }}</h3>
                    @can('view', $event)
                        <div class="event-details mb-4">
                            {!! $event->description !!}
                        </div>
                    @else
                        <div class="my-4">
                            @include('application.components.visible-only-member')
                        </div>
                    @endcan

                    @if ($event->attendee_count)
                        <div class="row">
                            <div class="col">
                                <h3>{{ __('Attendees') }} ({{ $event->attendee_count }})</h3>
                            </div>
                            <div class="col col-auto">
                                @can('view', $event)
                                    <a href="{{ route('groups.events.attendees', ['group' => $group->slug, 'event' => $event->uid]) }}">{{ __('See all') }}</a>
                                @endcan
                            </div>
                        </div>
                        <div class="avatar-list mb-3">
                            @can('view', $event)
                                @foreach ($attendees as $attendee) 
                                    <img class="avatar avatar-md rounded-circle border" src="{{ $attendee->user->avatar }}" alt="{{ $attendee->user->full_name }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $attendee->user->full_name }}"/>
                                @endforeach
                            @else
                                <div class="col-12">
                                    @include('application.components.visible-only-member')
                                </div>
                            @endcan
                        </div>
                    @endif

                    @can('view', $event)
                        @if (count($sponsors))
                            @can('list_sponsor', $group)
                                <h3>{{ __('Sponsors') }}</h3>
                                <div class="row mb-4">
                                    @foreach ($sponsors as $sponsor)
                                        <div class="col-lg-6 mb-3">
                                            <div class="d-flex align-items-center rounded border">
                                                <a href="{{ $sponsor->website }}" target="_blank">
                                                    <img class="avatar avatar-lg m-2" src="{{ $sponsor->avatar }}" alt="{{ $sponsor->name }}">
                                                </a>
                                                <div>
                                                    <h6>{{ $sponsor->name }}</h6>
                                                    <p class="text-muted">{{ $sponsor->description }}</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endcan
                        @endif
                    @endcan
                </div>

                <div class="col-lg-4 sticky-top sticky-side">
                    @can('update', $event)
                        <div class="row mb-3">
                            <div class="dropdown">
                                <button class="col-12 btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ __('Organizer Settings') }}
                                </button>
                                <div class="dropdown-menu width-95">
                                    <a class="dropdown-item" href="{{ route('groups.events.attendees', ['group' => $group, 'event' => $event]) }}">
                                        <i class="far fa-user pe-2"></i> {{ __('Manage attendees') }}
                                    </a>
                                    @if (!$event->isCancelled())
                                        @if ($event->isRSVPOpen())
                                            <a class="dropdown-item" href="{{ route('groups.events.close_rsvp', ['group' => $group, 'event' => $event]) }}">
                                                <i class="far fa-calendar-times pe-2"></i> {{ __('Close for RSVPs') }}
                                            </a>
                                        @else
                                            <a class="dropdown-item" href="{{ route('groups.events.open_rsvp', ['group' => $group, 'event' => $event]) }}">
                                                <i class="far fa-calendar pe-2"></i> {{ __('Open for RSVPs') }}
                                            </a>
                                        @endif
                                        <a class="dropdown-item" href="{{ route('groups.events.edit', ['group' => $group, 'event' => $event]) }}">
                                            <i class="far fa-edit pe-2"></i> {{ __('Edit event') }}
                                        </a>
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#cancelEventModal">
                                            <i class="far fa-times-circle pe-2"></i> {{ __('Cancel event') }}
                                        </a>
                                    @else
                                        <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#deleteEventModal">
                                            <i class="far fa-times-circle pe-2"></i> {{ __('Delete event') }}
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @if (!$event->announced_at)
                            <div class="row">
                                <div class="col-12">
                                    <div class="alert alert-warning" role="alert">
                                        <i class="fas fa-exclamation-triangle"></i>
                                        {{ __('You did not announce this event to your members, yet. Click the link to announce!') }} <a href="#" data-bs-toggle="modal" data-bs-target="#announceEventModal" class="alert-link">{{ __('Click here') }}</a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endcan

                    <a class="card card-link mb-3" href="{{ route('groups.about', $group->slug) }}">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar rounded border" style="background-image: url({{ $group->avatar }})"></span>
                                </div>
                                <div class="col">
                                    <div class="font-weight-medium">{{ $group->name }}</div>
                                    <div class="text-muted">{{ $group->isOpen() ? __('Public Group') : __('Closed Group') }}</div>
                                </div>
                            </div>
                        </div>
                    </a>

                    <div class="card card-sm">
                        <div class="card-body">
                            @if (!$event->isRSVPOpen() and !$event->isCancelled())
                                <div class="row align-items-center mb-2">
                                    <div class="col-auto">
                                        <span class="text-dark avatar">
                                            <i class="fa fa-times"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        {{ __('RSVPs are closed') }}
                                    </div>
                                </div>
                            @endif

                            @if ($event->fee_amount)
                                <div class="row align-items-center mb-2">
                                    <div class="col-auto">
                                        <span class="text-dark avatar">
                                            <i class="fa fa-money-bill-alt"></i>
                                        </span>
                                    </div>
                                    <div class="col">
                                        <strong class="mb-1">{{ __('Fee') }}</strong>
                                        <div>
                                            {{ money($event->fee_amount, $event->currency->short_code, true) }}
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row align-items-center mb-2">
                                <div class="col-auto">
                                    <span class="text-dark avatar">
                                        <i class="fa fa-calendar"></i>
                                    </span>
                                </div>
                                <div class="col">
                                    @if ($event->isCancelled())
                                        <strong>{{ __('Cancelled') }}</strong>
                                        <div>
                                            <time class="text-decoration-line-through" datetime="{{ convertToLocal($event->starts_at, 'U') }}">{{ convertToLocal($event->starts_at) }}</time>
                                        </div>
                                    @else
                                        <time datetime="{{ convertToLocal($event->starts_at, 'U') }}">{{ convertToLocal($event->starts_at) }}</time>
                                    @endif
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="text-dark avatar">
                                        @if ($event->is_online)
                                            <i class="fa fa-video"></i>
                                        @else
                                            <i class="fa fa-map-marker-alt"></i>
                                        @endif
                                    </span>
                                </div>
                                <div class="col">
                                    @if ($event->is_online)
                                        <strong>{{ __('Online Event') }}</strong>
                                        <div>
                                            @if ($auth_user && $auth_user->isAttending($event))
                                                {{ $event->online_meeting_link }}
                                            @else
                                                {{ __('Link visible for attendees') }}
                                            @endif
                                        </div>
                                    @else
                                        <div>
                                            @can('view', $event)
                                                @if ($auth_user && $auth_user->isAttending($event))
                                                    {{ $event->address->address_1 }}
                                                    {!! __('get_directions_link', [
                                                        'lat' => $event->address->lat,
                                                        'lng' => $event->address->lat,
                                                    ]) !!}
                                                @else
                                                    {{ __('Visible for attendees') }}
                                                @endif
                                            @else
                                                {{ __('Location visible to members') }}
                                            @endcan
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white d-flex align-items-center sticky-bottom py-2 border">
            <div class="container py-2">
                <div class="row d-flex align-items-center">
                    <div class="d-none d-lg-block col-12 col-lg-7">
                        <span>
                            <time datetime="{{ convertToLocal($event->starts_at, 'U') }}">{{ convertToLocal($event->starts_at) }}</time>
                        </span>
                        <h4 class="mb-0">{{ $event->title }}</h4>
                    </div>
                    <div class="d-flex justify-content-end align-items-center col-12 col-lg-5">
                        @livewire('group.event.actions.save-event', ['event' => $event, 'icon_class' => 'me-2'], key($event->id))
                        @livewire('common.share-button', ['url' => route('groups.events.show', ['group' => $event->group->slug, 'event' => $event->uid])], key($event->id))
                        @livewire('group.event.actions.attend-event', ['event' => $event], key($event->id))
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('modals')
    @can('delete', $event)
        <div class="modal fade" id="cancelEventModal" tabindex="-1" role="dialog" aria-labelledby="cancelEventModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('groups.events.cancel', ['group' => $group, 'event' => $event]) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title h4" id="cancelEventModalLabel">{{ __('Cancel event') }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                {{ __('This will automatically update your attendees that it is no longer happening. (You can not undo this.)') }}
                            </p>
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="cancel" name="confirm_cancel" value="cancel" type="radio" checked>
                                <label class="form-check-label" for="cancel"><strong>{{ __('Cancel event') }}</strong></label>
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" id="delete" name="confirm_cancel" value="delete" type="radio">
                                <label class="form-check-label" for="delete">
                                    <strong>{{ __('Cancel and delete event') }}</strong>
                                    <p class="mb-0">
                                        {{ __('Deleting means that no one, including you, will be able to see the event page ever again.') }}
                                    </p>
                                </label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">{{ __('Confirm') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        @if ($event->isCancelled())
            <div class="modal fade" id="deleteEventModal" tabindex="-1" role="dialog" aria-labelledby="deleteEventModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <form action="{{ route('groups.events.cancel', ['group' => $group, 'event' => $event]) }}" method="POST">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="modal-title h4" id="deleteEventModalLabel">{{ __('Delete event') }}</h6>
                                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <strong>
                                    {{ __('This will permanently delete the event from your group.') }}
                                </strong>
                                <input type="hidden" name="confirm_cancel" value="delete">
                            </div>
                            <div class="modal-footer">
                                <button class="btn btn-primary" type="submit">{{ __('Confirm') }}</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endcan

    @can('update', $event)
        <div class="modal fade" id="announceEventModal" tabindex="-1" role="dialog" aria-labelledby="announceEventModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <form action="{{ route('groups.events.announce', ['group' => $group, 'event' => $event]) }}" method="POST">
                    @csrf
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title h4" id="announceEventModalLabel">{{ __('Announce this event') }}</h6>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <p>
                                {{ __('This will automatically send emails to your members. (You can not undo this.)') }}
                            </p>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-primary" type="submit">{{ __('Confirm') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    @endcan
@endpush

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
