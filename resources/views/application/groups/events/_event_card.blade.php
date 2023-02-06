@push('additional_json_ld'){"@context":"https://schema.org","@type":"Event","name":"{{ $event->title }}","url":"{{ route('groups.events.show', ['group' => $event->group->slug, 'event' => $event->uid]) }}","description":"{{ substr(strip_tags($event->description), 0, 180) }}","startDate":"{{ $event->starts_at }}","endDate":"{{ $event->ends_at }}","eventStatus":"https://schema.org/EventScheduled","image":"{{ $event->image }}","eventAttendanceMode":"https://schema.org/{{ $event->is_online ? 'OnlineEventAttendanceMode' : 'OfflineEventAttendanceMode' }}","location":{"@type":"{{ $event->is_online ? 'VirtualLocation' : 'Place' }}","url":"{{ route('groups.events.show', ['group' => $event->group->slug, 'event' => $event->uid]) }}"},"organizer":{"@type":"Organization","name":"{{ $event->group->name }}","url":"{{ route('groups.about', ['group' => $event->group->slug]) }}"},"performer":"{{ $event->group->name }}"},@endpush

<div wire:key="{{ time() . $event->uid }}" class="row">
    <div class="col-12 {{ $list_view ? 'col-md-4' : '' }}">
        <a href="{{ route('groups.events.show', ['group' => $event->group->slug, 'event' => $event->uid]) }}">
            @if ($event->is_online)
                <span class="badge rounded-sm image-badge border p-2 m-2">
                    <i class="fa fa-video pe-1"></i>
                    {{ __('Online Event') }}
                </span>
            @endif
            <div class="img-wrap ratio-16-9">
                <div class="img-content">
                    <img class="rounded border" src="{{ $event->image }}" alt="{{ $event->title }}">
                </div>
            </div>
        </a>
    </div>
    <div class="col-12 d-flex flex-column {{ $list_view ? 'col-md-8' : 'justify-content-between' }}">
        <time class="text-primary text-uppercase tracking-tight fs-5 py-1" datetime="{{ convertToLocal($event->starts_at, 'U') }}">{{ convertToLocal($event->starts_at) }}</time>
        <h5 class="text-truncate text-truncate-two-line text-black fs-3">
            <a class="text-decoration-none" href="{{ route('groups.events.show', ['group' => $event->group->slug, 'event' => $event->uid]) }}">
                {{ $event->title }}
            </a>
        </h5>
        <p class="text-muted text-truncate text-truncate-two-line fs-4">
            {{ $event->group->name }} {{ $list_view ? '• ' . $event->group->address->name : '' }}
        </p>
        <div class="d-flex flex-row align-items-center justify-content-between text-muted fs-5 mt-auto">
            <div class="d-flex flex-row align-items-center">
                <p class="mb-0 fs-4">{{ $event->attendee_count }} {{ trans_choice(__('attende | attendees'), $event->attendee_count) }}</p>
                @if ($list_view && $auth_user && $auth_user->isAttending($event) and !$event->isPast())
                    <div class="btn btn-light btn-sm border rounded text-success fs-5 ms-2">
                        <i class="fas fa-check-circle pe-1"></i> {{ __('Attending') }}
                    </div>
                @endif
            </div>
            <div class="d-flex">
                @livewire('group.event.actions.save-event', ['event' => $event], key(time() . $event->uid))
                @livewire('common.share-button', ['icon_class' => 'ms-2', 'button' => false, 'url' => route('groups.events.show', ['group' => $event->group->slug, 'event' => $event->uid])], key(time() . $event->uid))
            </div>
        </div>
    </div>
    <div class="{{ $list_view ? 'col-12' : 'd-none' }}">
        <hr>
    </div>
</div>

