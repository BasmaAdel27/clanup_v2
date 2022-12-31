<div class="pb-5">
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

    <div class="row d-flex justify-content-center">
        <div class="col-12 col-lg-8 attendees-block">
            <div class="list-group card-list-group border">
                <div class="list-header p-4">
                    <div class="d-flex justify-content-between">
                        <h2>{{ __('Attendees') }}</h2>
                        <a href="{{ route('groups.events.attendees.csv', ['group' => $event->group->slug, 'event' => $event->uid]) }}">{{ __('Export CSV') }}</a>
                    </div>

                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="fa fa-search"></i>
                        </span>
                        <input class="form-control" autocomplete="off" placeholder="{{ __('Search') }}" type="search" wire:model="search">
                    </div>
                </div>

                <div class="list-group-item">
                    <ul class="nav mobile-horizontal">
                        <li class="nav-item">
                            <a class="nav-link fw-bold {{ $tab == 'going' ? 'text-primary' : 'text-muted' }}" href="javascript:void(0);" wire:click="$set('tab', 'going')">{{ __('Going') }} ({{ $attending_count }})</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link fw-bold {{ $tab == 'not_going' ? 'text-primary' : 'text-muted' }}" href="javascript:void(0);" wire:click="$set('tab', 'not_going')">{{ __('Not Going') }} ({{ $not_attending_count }})</a>
                        </li>
                    </ul>
                </div>

                @foreach ($attendees as $attendee)
                    <div class="list-group-item px-4 py-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <img class="avatar rounded shadow-0 border" src="{{ $attendee->user->avatar }}" alt="{{ $attendee->user->full_name }}">
                            </div>
                            <div class="col">
                                <p class="mb-0 fw-bold">{{ $attendee->user->full_name }}</p>
                                <p class="mb-0 text-muted">{{ $attendee->user->getRoleOf($event->group) }}</p>
                            </div>
                            <div class="col-auto lh-1">
                                @if ($auth_user and $auth_user->hasOrganizerRolesOf($event->group))
                                    <div class="dropdown">
                                        <a href="#" class="link-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @if ($tab == 'going')
                                                <a class="dropdown-item" wire:click="change_response({{ $attendee->id }}, 'not_going')">{{ __('Move to "Not going"') }}</a>
                                            @endif

                                            @if ($tab == 'not_going')
                                                <a class="dropdown-item" wire:click="change_response({{ $attendee->id }}, 'going')">{{ __('Move to "Going"') }}</a>
                                            @endif
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if (count($attendees) <= 0)
                    <div class="list-group-item list-group-item-action text-muted text-center p-4">
                        <i class="fas fa-user-friends fs-4"></i>
                        <p class="mb-0 mt-2">{{ __('No attendees yet') }}</p>
                    </div>
                @endif
    
                @if ($count > $limit)
                    <div class="list-header p-4 text-center">
                        <a class="btn btn-orange rounded-pill" wire:click="loadMore">{{ __('Load more') }}</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
