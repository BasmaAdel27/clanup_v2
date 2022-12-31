@push('page_head_scripts')
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea.tinymce',
            menubar: false,
            plugins: 'lists paste',
            toolbar: 'undo redo | bullist numlist',
            paste_auto_cleanup_on_paste : true,
            paste_remove_styles: true,
            paste_remove_styles_if_webkit: true,
            paste_strip_class_attributes: true,
            setup: function (editor) {
                editor.on('init change', function () {
                    editor.save();
                });
                editor.on('change', function (e) {
                    @this.set('event.description', editor.getContent());
                });
            }
        });
    </script>
@endpush

<div class="container py-4 py-lg-6">
    <form enctype="multipart/form-data">
        <div class="row align-items-start">
            <div class="col-12 col-md-8 pe-md-5">
                <h1 wire:ignore>{{  isset($event['id']) ? $event['title'] : __('Create an event') }}</h1>
                <a class="text-primary" href="{{ route('groups.events', ['group' => $group]) }}">< {{ $group->name }}</a>
                <hr>

                <!-- Event Title -->
                <div class="form-group mb-4">
                    <label class="form-label">{{  __('Title') }}</label>
                    <input class="form-control @error('event.title') is-invalid @enderror" placeholder="{{  __('Title') }}" type="text" wire:model="event.title">
                    @error('event.title')<small class="form-text text-danger ps-1">{{ str_replace('.', ' ', $message) }}</small>@enderror
                </div>

                <!-- Event Start Date -->
                <div class="row align-items-end mb-4">
                    <div class="col-5">
                        <div class="form-group @error('event.start_date') is-invalid @enderror">
                            <label class="form-label">{{  __('Start') }}</label>
                            <x-date_picker classes="bg-white form-control" :defaultDate="$event['start_date']" :minDate="$minDate" wire:model="event.start_date" />
                        </div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="form-group">
                            <input class="form-control @error('event.start_time') is-invalid @enderror" type="time" wire:model="event.start_time">
                        </div>
                    </div>
                    <div class="col-3">
                        <p class="mb-0 mb-md-2 fs-7">{{ convertToLocal(now(), '\G\M\T P') }}</p>
                    </div>
                    <div class="col-12">
                        @error('event.start_date')<small class="form-text text-danger ps-1">{{ str_replace('.', ' ', $message) }}</small><br>@enderror
                        @error('event.start_time')<small class="form-text text-danger ps-1">{{ str_replace('.', ' ', $message) }}</small>@enderror
                    </div>
                </div>

                <!-- Event End Date -->
                <div class="row align-items-end mb-4">
                    <div class="col-5">
                        <div class="form-group @error('event.end_date') is-invalid @enderror">
                            <label class="form-label">{{  __('End') }}</label>
                            <x-date_picker classes="bg-white form-control" :defaultDate="$event['end_date']" :minDate="$minDate" wire:model="event.end_date"/>
                        </div>
                    </div>
                    <div class="col-4 col-md-3">
                        <div class="form-group">
                            <input class="form-control @error('event.end_time') is-invalid @enderror" type="time" wire:model="event.end_time">
                        </div>
                    </div>
                    <div class="col-3">
                        <p class="mb-0 mb-md-2 fs-7">{{ convertToLocal(now(), '\G\M\T P') }}</p>
                    </div>
                    <div class="col-12">
                        @error('event.end_date')<small class="form-text text-danger ps-1">{{ str_replace('.', ' ', $message) }}</small><br>@enderror
                        @error('event.end_time')<small class="form-text text-danger ps-1">{{ str_replace('.', ' ', $message) }}</small>@enderror
                    </div>
                </div>

                <!-- Event Featured Photo -->
                <div class="col-12 mb-4" wire:ignore>
                    <label class="form-label">{{  __('Featured Photo') }}</label>
                    <label for="featured_photo_file" class="form-group border {{ $event['image'] ? 'd-flex justify-content-center h-450' : '' }} @error('event.featured_photo_file') border-danger @enderror border-3 border-dotted text-center w-100">
                        <input id="featured_photo_file" name="featured_photo_file" accept="image/*" class="d-none" type="file" onchange="$.fn.changePreview(this);" wire:model="event.featured_photo_file">
                        <div class="img-wrap {{ $event['image'] ? 'ratio-16-9' : '' }} w-100">
                            <div class="img-content">
                                <img class="image_preview" src="{{ $event['image'] ? $event['image'] : '' }}" />
                            </div>
                        </div>
                        <div class="select_photo_container {{ $event['image'] ? 'd-none' : '' }} p-5">
                            <a class="btn btn-outline-primary mb-3">
                                <i class="far fa-image"></i>
                                {{ __('Choose photo') }}
                            </a>
                            <br>
                            <small class="form-text">{{  __('At least 1200 x 675 pixels') }}</small>
                        </div>
                    </label> 
                    @error('event.featured_photo_file')<small class="form-text text-danger ps-1">{{ str_replace('.', ' ', $message) }}</small>@enderror
                </div>

                <!-- Event Description -->
                <div class="form-group mb-4">
                    <div wire:ignore>
                        <label class="form-label">{{  __('Description') }}</label>
                        <textarea class="form-control tinymce" wire:model="event.description"></textarea>
                    </div>
                    @error('event.description')<small class="form-text text-danger ps-1">{{ str_replace('.', ' ', $message) }}</small>@enderror
                </div>

                <!-- Event Is Online Checkbox -->
                <div class="form-group mb-4">
                    <label class="form-label">{{  __('This is an online event') }}</label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" id="event.is_online" type="checkbox" wire:model="event.is_online">
                        <label class="form-check-label" for="event.is_online">{{  __('Yes') }}</label>
                    </div>
                    @error('event.is_online')<small class="form-text text-danger ps-1">{{ str_replace('.', ' ', $message) }}</small>@enderror
                </div>

                <!-- Event Online Meeting Link -->
                <div class="{{ isset($event['is_online']) && $event['is_online'] ? '' : 'd-none' }} form-group mb-4">
                    <label class="form-label">{{  __('Online Meeting Link') }}</label>
                    <input class="form-control @error('event.online_meeting_link') is-invalid @enderror" placeholder="{{  __('Ex. https://zoom.us/') }}" wire:model="event.online_meeting_link" />
                    <small class="text-muted">{{ __('Link will only be visible to people who RSVP.') }}</small>
                    @error('event.online_meeting_link')<br><small class="form-text text-danger ps-1">{{ str_replace('.', ' ', $message) }}</small>@enderror
                </div>

                <!-- Event Location -->
                <div class="{{ isset($event['is_online']) && $event['is_online'] ? 'd-none' : '' }}">
                    <div class="form-group mb-4">
                        <label class="form-label">{{  __('Location') }}</label>
                        <div class="input-icon mb-3 place_autocomplete_container">
                            <span class="input-icon-addon">
                                <i class="fa fa-map-pin"></i> 
                            </span>
                            <input id="place_autocomplete" class="form-control place_autocomplete @error('event.address.name') is-invalid @enderror" autocomplete="off" placeholder="{{  __('Location') }}" type="text" name="location_name" wire:model="event.address.name" wire:ignore>
                            <input type="hidden" name="place" id="place" wire:model="event.address.location_name">
                            <input type="hidden" name="address_1" id="formatted_address" wire:model="event.address.address_1">
                            <input type="hidden" name="lat" id="lat" wire:model="event.address.lat">
                            <input type="hidden" name="lng" id="lng" wire:model="event.address.lng">
                            <input type="hidden" name="country" id="country" wire:model="event.address.country">
                            <input type="hidden" name="state" id="state" wire:model="event.address.state">
                            <input type="hidden" name="city" id="city" wire:model="event.address.city">
                            <input type="hidden" name="postal_code" id="postal_code" wire:model="event.address.zip">
                        </div>
                        @error('event.address.name')
                            <small class="form-text text-danger ps-1">{{ __('The event address is required') }}</small>
                        @enderror
                    </div>

                    <!-- Event How to find us -->
                    <div class="form-group mb-4">
                        <label class="form-label">{{  __('How to find us') }} <span class="text-muted">{{ __('(Optional)') }}</span></label>
                        <textarea class="form-control @error('event.how_to_find_us') is-invalid @enderror" rows="3" placeholder="{{  __('How to find us') }}" wire:model="event.how_to_find_us"></textarea>
                        @error('event.how_to_find_us')<small class="form-text text-danger ps-1">{{ str_replace('.', ' ', $message) }}</small>@enderror
                    </div>
                </div>

                <!-- Optional Settings -->
                <h3>{{  __('Optional Settings') }}</h3>

                <!-- Ask members a question -->
                <div class="row border-bottom py-3">
                    <div class="col-9">
                        <p class="mb-0 fw-bold fs-3">{{  __('Ask members a question') }}</p>
                    </div>
                    <div class="col-3 text-end">
                        <div class="form-check form-switch float-end">
                            <input class="form-check-input" type="checkbox" wire:model="optional_setting_rsvp_question" {{ $optional_setting_rsvp_question ? 'checked=""' : '' }}>
                        </div>
                    </div>
                    <div class="collapse {{ $optional_setting_rsvp_question ? 'show' : '' }}">
                        <div class="form-group my-3">
                            <input class="form-control @error('event.rsvp_question') is-invalid @enderror" type="text" wire:model="event.rsvp_question">
                            <small class="text-muted">{{ __('Do not ask for sensitive or private personal information.') }}</small>
                            @error('event.rsvp_question')<br><small class="form-text text-danger ps-1">{{ str_replace('.', ' ', $message) }}</small>@enderror
                        </div>
                    </div>
                </div>

                <!-- Allowed guests -->
                <div class="row border-bottom py-3">
                    <div class="col-9">
                        <p class="mb-0 fw-bold fs-3">{{  __('Allowed guests') }}</p>
                    </div>
                    <div class="col-3 text-end">
                        <div class="form-check form-switch float-end">
                            <input class="form-check-input" type="checkbox" wire:model="optional_setting_allowed_guest" {{ $optional_setting_allowed_guest ? 'checked=""' : '' }}>
                        </div>
                    </div>
                    <div class="collapse {{ $optional_setting_allowed_guest ? 'show' : '' }}">
                        <div class="form-group my-3">
                            <input class="form-control @error('event.allowed_guests') is-invalid @enderror" type="number" value="5" wire:model="event.allowed_guests">
                            @error('event.allowed_guests')<small class="form-text text-danger ps-1">{{ str_replace('.', ' ', $message) }}</small>@enderror
                        </div>
                    </div>
                </div>

                <!-- Event fee -->
                <div class="row border-bottom py-3">
                    <div class="col-9">
                        <p class="mb-0 fw-bold fs-3">{{  __('Event fee') }}</p>
                    </div>
                    <div class="col-3 text-end">
                        <div class="form-check form-switch float-end">
                            <input class="form-check-input" type="checkbox" wire:model="optional_setting_event_fee" {{ $optional_setting_event_fee ? 'checked=""' : '' }}>
                        </div>
                    </div>
                    <div class="collapse {{ $optional_setting_event_fee ? 'show' : '' }}" id="eventFee">
                        <div class="form-group my-3">
                            <label class="form-label">{{  __('Method') }}</label>
                            <select name="fee_method" class="form-control @error('event.fee_method') is-invalid @enderror" wire:model="event.fee_method">
                                <option value="" selected="">{{ __('Please select') }}</option>
                                <option value="0">{{ __('Cash') }}</option>
                            </select>
                        </div>

                        <div class="row mb-4">
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">{{  __('Currency') }}</label>
                                    <select name="currency_id" class="form-control @error('event.fee_currency_id') is-invalid @enderror" autocomplete="off" wire:model="event.fee_currency_id">
                                        <option value="" selected="">{{ __('Please select') }}</option>
                                        @foreach(get_currencies_select2_array() as $option)
                                            <option value="{{ $option['id'] }}">{{ $option['text'] }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label class="form-label">{{  __('Amount') }}</label>
                                    <input class="form-control @error('event.fee_amount') is-invalid @enderror" type="number" wire:model="event.fee_amount">
                                </div>
                            </div>
                        </div>

                        <div class="form-group mb-3">
                            <label class="form-label">{{  __('Additional refund policy') }} <span class="text-muted">{{ __('(Optional)') }}</span></label>
                            <input class="form-control @error('event.fee_additional_refund_policy') is-invalid @enderror" type="text" wire:model="event.fee_additional_refund_policy">
                        </div>
                    </div>
                </div>

                <div class="col-12 mt-4">
                    <div class="float-end">
                        @if (!array_key_exists('id', $event))
                            <button type="button" class="btn btn-light" wire:click="store('draft')">{{ __('Save as draft') }}</button>
                            <button type="button" class="btn btn-primary ms-2" wire:click="store('publish')">{{ __('Publish') }}</button>
                        @else
                            @if ($event['status'] == \App\Models\Event::DRAFT)
                                <button type="button" class="btn btn-light" wire:click="update('draft')">{{ __('Save as draft') }}</button>
                                <button type="button" class="btn btn-primary ms-2" wire:click="update('publish')">{{ __('Publish') }}</button>
                            @else
                                <button type="button" class="btn btn-primary" wire:click="update('publish')">{{ __('Update') }}</button>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-4 d-none d-md-block sticky-top mb-3 top-5 z-index-10">
                <div class="card p-4 border-0 shadow">
                    <div class="card-body p-2">
                        <h3>{{  __('Tips for a great event') }}</h3>
                        <hr>

                        <p class="fw-bold fst-italic mb-1">{{  __('Be descriptive') }}</p>
                        <p class="fw-light mb-4">{{ __('A good title immediately gives people an idea of what the event is about') }}</p>

                        <p class="fw-bold fst-italic mb-1">{{ __('Get organized') }}</p>
                        <p class="fw-light mb-4">
                            {{ __('Describe things in a clear order so it\'s easy to digest. Start with an overall description of the event and include a basic agenda, before you move into really specific details.') }}
                        </p>

                        <p class="fw-bold fst-italic mb-1">{{ __('Add an image') }}</p>
                        <p class="fw-light mb-4">
                            {{ __('Upload a photo or image to give members a better feel for the event.') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>