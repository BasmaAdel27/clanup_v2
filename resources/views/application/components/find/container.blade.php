<div>
    <nav class="nav">
        <a class="text-decoration-none fs-2 fw-bold {{ $source == 'EVENTS' ? 'active-bottom' : 'text-muted' }}" href="{{ route('find', ['search' => request()->query('search', ''), 'source' => 'EVENTS', 'place' => $place, 'lat' => $lat, 'lng' => $lng, 'topic' => $topic, 'category' => $category]) }}">
            {{ __('Events') }}
        </a>
        <a class="text-decoration-none fs-2 fw-bold ms-2 {{ $source == 'GROUPS' ? 'active-bottom' : 'text-muted' }}" href="{{ route('find', ['search' => request()->query('search', ''), 'source' => 'GROUPS', 'place' => $place, 'lat' => $lat, 'lng' => $lng, 'topic' => $topic, 'category' => $category]) }}">
            {{ __('Groups') }}
        </a>
    </nav>

    <div class="bg-white sticky-top border-bottom py-3">
        <p class="text-muted">{{ __('Suggested events :location', ['location' => $place]) }}</p>
        <div class="d-flex">
            @if ($source == 'EVENTS')
                <div>
                    <x-date_range_picker classes="bg-white drp btn text-dark border" :defaultDateFrom="$from" :defaultDateTo="$to" wire:model="date" />
                </div>
            @endif
            
            @if ($source == 'EVENTS')
                <div class="dropdown me-2">
                    <button class="btn @if($type != 0) btn-primary @else btn-light text-dark @endif border rounded-lg dropdown-toggle" id="typeSelectDropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        @if ($type == 0)
                            {{ __('Any type') }}
                        @elseif($type == 1)
                            {{ __('In-person') }}
                        @elseif($type == 2)   
                            {{ __('Online') }}
                        @endif
                    </button>
                    <div class="dropdown-menu" aria-labelledby="typeSelectDropdown">
                        <a class="dropdown-item" wire:click="$set('type', 0)">{{ __('Any type') }}</a>
                        <a class="dropdown-item" wire:click="$set('type', 1)">{{ __('In-person') }}</a>
                        <a class="dropdown-item" wire:click="$set('type', 2)">{{ __('Online') }}</a>
                    </div>
                </div>
            @endif

            @if (get_system_setting('google_places_api_key')) 
                <div class="dropdown me-2">
                    <button class="btn @if($distance != 1000) btn-primary @else btn-light text-dark @endif border rounded-lg dropdown-toggle" id="distanceSelectDropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        @if($distance == 1000)
                            {{ __('Any distance') }}
                        @else
                            {{ __(':number Miles', ['number' => $distance]) }}
                        @endif
                    </button>
                    <div class="dropdown-menu" aria-labelledby="distanceSelectDropdown">
                        <a class="dropdown-item" wire:click="$set('distance', 1000)">{{ __('Any distance') }}</a>
                        <a class="dropdown-item" wire:click="$set('distance', 5)">{{ __(':number Miles', ['number' => 5]) }}</a>
                        <a class="dropdown-item" wire:click="$set('distance', 10)">{{ __(':number Miles', ['number' => 10]) }}</a>
                        <a class="dropdown-item" wire:click="$set('distance', 25)">{{ __(':number Miles', ['number' => 25]) }}</a>
                        <a class="dropdown-item" wire:click="$set('distance', 50)">{{ __(':number Miles', ['number' => 50]) }}</a>
                        <a class="dropdown-item" wire:click="$set('distance', 100)">{{ __(':number Miles', ['number' => 100]) }}</a>
                    </div>
                </div>
            @endif

            <div class="dropdown me-2">
                <button class="btn @if($category != 0 or $topic != 0) btn-primary @else btn-light text-dark @endif border rounded-lg dropdown-toggle" id="categorySelectDropdown" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                    @if($category != 0)
                        {{ get_topic_category_name($category) }}
                    @elseif($topic != 0)
                        {{ get_topic_name($topic) }}
                    @else
                        {{ __('Any category') }}
                    @endif
                </button>
                <div class="dropdown-menu scrollable-dropdown" aria-labelledby="categorySelectDropdown">
                    <a class="dropdown-item" wire:click="$set('category', 'any')">{{ __('Any category') }}</a>
                    @foreach (get_all_topic_categories() as $topic_category)
                        <a class="dropdown-item" wire:click="$set('category', {{ $topic_category->id }})">{{ $topic_category->name }}</a>
                    @endforeach
                </div>
            </div>

            <button class="btn btn-link fw-bold" wire:click="resetFilters()">{{ __('Clear filters') }}</button>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12 col-md-8">
            @if ($source == 'EVENTS')
                @foreach ($data as $event)
                    @include('application.groups.events._event_card', ['event' => $event, 'list_view' => true])
                @endforeach
            @elseif($source == 'GROUPS')
                @foreach ($data as $group)
                    @include('application.groups._group_card', ['group' => $group])
                @endforeach
            @endif

            @if ($count <= 0)
                <div class="d-flex flex-column align-items-center justify-content-center text-muted py-5">
                    @if ($source == 'EVENTS')
                        <i class="far fa-calendar-alt fs-3"></i>
                        <p class="mb-0 mt-2">{{ __('No events yet') }}</p>
                    @else
                        <i class="fas fa-user-friends fs-3"></i>
                        <p class="mb-0 mt-2">{{ __('No groups yet') }}</p>
                    @endif
                </div>
            @endif

            @if ($count > $limit)
                <div class="d-flex flex-column align-items-center justify-content-center text-muted py-3">
                    <a class="btn btn-orange" wire:click="loadMore">{{ __('Load more') }}</a>
                </div>
            @endif
        </div>
    </div>
</div>
