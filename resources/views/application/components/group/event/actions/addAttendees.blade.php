<div>
    <div class="container">
        <div class="row py-5">
            <div class="col-12">
                <time datetime="{{ convertToLocal($event->starts_at, 'U') }}">{{ convertToLocal($event->starts_at) }}</time>
            </div>
            <div class="col-12">
                <h1>{{ $event->title }}</h1>
            </div>
            <div class="col-12">
                <a href="{{ route('groups.events.show', ['group' => $this->event->group, 'event' => $event]) }}">
                    < {{ __('Back to event') }}</a>
            </div>
        </div>
    </div>
</div>
