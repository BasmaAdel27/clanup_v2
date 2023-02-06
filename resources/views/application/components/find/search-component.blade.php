<div>
    @if ($inline)
        <form class="d-none d-lg-flex place_autocomplete_container" action="{{ route('find') }}" method="GET">
            <input type="hidden" name="source" value="{{ request()->query('source', 'EVENTS') }}">
            <div class="input-icon">
                <span class="input-icon-addon">
                    <i class="fa fa-search"></i>
                </span>
                <input class="form-control rounded-0 rounded-start" autocomplete="off" name="search" placeholder="{{ __('Search events') }}" value="{{ $search }}" type="search">
            </div>
            @if (get_system_setting('google_places_api_key'))
                <div class="input-icon">
                    <span class="input-icon-addon">
                        <i class="fa fa-map-pin"></i>
                    </span>
                    <input class="form-control rounded-0 border-start-0 border-end-0 place_autocomplete hide-on-focus" data-type="(regions)" autocomplete="off" id="placeAutoCompleteHeader" placeholder="{{ __('Location') }}" value="{{ $place }}" type="text" wire:ignore>
                    <input type="hidden" name="place" value="{{ $place }}" wire:model="place">
                    <input type="hidden" name="lat" value="{{ $lat }}" wire:model="lat">
                    <input type="hidden" name="lng" value="{{ $lng }}" wire:model="lng">
                </div>
            @endif
            <button class="btn btn-orange btn-icon rounded-0 rounded-end" type="submit">
                <i class="fas fa-search"></i>
            </button>
        </form>
    @else
        <form action="{{ route('find', ['source' => 'EVENTS']) }}">
            <div class="row">
                <div class="col">
                    <div class="form-group">
                        <div class="input-icon">
                            <span class="input-icon-addon">
                                <i class="fa fa-search"></i>
                            </span>
                            <input class="form-control" autocomplete="off" name="search" placeholder="{{ __('Search for "yoga"') }}" value="{{ $search }}" type="search">
                        </div>
                    </div>
                </div>
                @if (get_system_setting('google_places_api_key'))
                    <div class="col">
                        <div class="form-group">
                            <div class="input-icon place_autocomplete_container">
                                <span class="input-icon-addon"><i class="fa fa-map-pin"></i></span>
                                <input class="form-control place_autocomplete hide-on-focus" data-type="(regions)" autocomplete="off" id="placeAutoCompleteHome" placeholder="{{ __('Location') }}" value="{{ $place }}" type="text" wire:ignore>
                                <input type="hidden" name="place" value="{{ $place }}" wire:model="place">
                                <input type="hidden" name="lat" value="{{ $lat }}" wire:model="lat">
                                <input type="hidden" name="lng" value="{{ $lng }}" wire:model="lng">
                            </div>
                        </div>
                    </div>
                @endif
                <div class="col-12 mt-2">
                    <button class="btn btn-orange w-100">{{ __('Search') }}</button>
                </div>
            </div>
        </form>
    @endif
</div>
