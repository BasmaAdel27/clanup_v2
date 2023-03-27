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
                    @this.set('group_describe', editor.getContent());
                });
            }
        });
    </script>
@endpush

<form wire:submit.prevent="store">
    <div class="row d-flex justify-content-center {{ $step == 'location' ? '' : 'd-none' }}">
        <div class="row d-flex justify-content-center pb-3">
            <div class="col-md-6">
                <p class="text-center">{{ __('Step 1/5') }}</p>
            </div>
        </div>
        <div class="col-md-8">
            <h1>{{ __('First, set your group\'s location.') }}</h1>
            <p>{{ __(':app_name groups meet locally, in person and online. We\'ll connect you with people in your area, and more can join you online.', ['app_name' => $application_name]) }}</p>
            <div class="input-icon mb-3 place_autocomplete_container">
                <span class="input-icon-addon">
                    <i class="fa fa-map-pin"></i>
                </span>
                <input id="place_autocomplete" class="form-control place_autocomplete" data-type="(regions)" autocomplete="off" placeholder="{{ __('Location') }}" type="text" name="location_name" wire:model="location_name" wire:click="next" wire:ignore>
                <input type="hidden" name="place" id="place_name" wire:model="place_name">
                <input type="hidden" name="address_1" id="address_1" wire:model="formatted_address">
                <input type="hidden" name="lat" id="lat" wire:model="lat">
                <input type="hidden" name="lng" id="lng" wire:model="lng">
                <input type="hidden" name="country" id="country" wire:model="country">
                <input type="hidden" name="state" id="state" wire:model="state">
                <input type="hidden" name="city" id="city" wire:model="city">
                <input type="hidden" name="postal_code" id="postal_code" wire:model="postal_code">
            </div>
            @error('location_name') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-8 p-0">
                <button type="button" class="btn btn-primary float-end" wire:click="next" {{ $location_name ? '' : 'disabled' }}>{{ __('Next') }}</button>
            </div>
        </div>
    </div>

    <div class="row d-flex justify-content-center {{ $step == 'topics' ? '' : 'd-none' }}">
        <div class="row d-flex justify-content-center pb-3">
            <div class="col-md-6">
                <p class="text-center">{{ __('Step 2/5') }}</p>
            </div>
        </div>
        <div class="col-md-8">
            <h1>{{ __('Choose a few topics that describe your group\'s interests') }}</h1>
            <p>{{ __('Be specific! This will help us promote your group to the right people.') }}</p>
            <ul class="list-inline">
                @foreach ($selected_interests as $topic)
                    <li class="list-inline-item mb-2" wire:click="detachTopic({{ json_encode($topic) }})">
                        <a class="badge bg-primary p-2 text-decoration-none">{{ $topic['name'] }}</a>
                    </li>
                @endforeach
            </ul>
            <div class="input-icon mb-3 place_autocomplete_container">
                <span class="input-icon-addon">
                    <i class="fa fa-search"></i>
                </span>
                <input class="form-control" placeholder="{{ __('Search Topics') }}" type="search" wire:model="search">
            </div>
            @if ($selected_topics_max_reached)
                <div class="alert alert-danger mt-2" role="alert">
                    <label class="label-absolute mr-3">
                        <i class="fa fa-exclamation-circle text-danger"></i>
                    </label>
                    <label class="pl-25px">{{ __('You can choose up to 15 topics.') }}</label>
                </div>
            @endif
            <div class="text-block pt-3">
                <ul class="list-inline">
                    @foreach ($recommended_interests as $topic)
                        <li class="list-inline-item mb-2 {{ isset($selected_interests[$topic->id]) ? 'd-none' : ''  }}" wire:click="attachTopic({{$topic}})">
                            <a class="badge bg-secondary p-2 text-decoration-none">{{ $topic->name }}</a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-8 p-0">
                <button type="button" class="btn btn-light float-start" wire:click="back">{{ __('Back') }}</button>
                <button type="button" class="btn btn-primary float-end" wire:click="next" {{ empty($selected_interests) ? 'disabled' : '' }}>{{ __('Next') }}</button>
            </div>
        </div>
    </div>

    <div class="row d-flex justify-content-center {{ $step == 'name' ? '' : 'd-none' }}">
        <div class="row d-flex justify-content-center pb-3">
            <div class="col-md-6">
                <p class="text-center">{{ __('Step 3/5') }}</p>
            </div>
        </div>
        <div class="col-md-8 mb-3">
            <h1>{{ __('What will your group\'s name be?') }}</h1>
            <p>{{ __('Choose a name that will give people a clear idea of what the group is about. Feel free to get creative! You can edit this later if you change your mind.') }}</p>
            <input class="form-control" placeholder="{{ __('Name') }}" type="text" wire:model="group_name" wire:keydown.enter="next">
            @error('group_name') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-8 p-0">
                <button type="button" class="btn btn-light float-start" wire:click="back">{{ __('Back') }}</button>
                <button type="button" class="btn btn-primary float-end" wire:click="next" {{ $group_name ? '' : 'disabled' }}>{{ __('Next') }}</button>
            </div>
        </div>
    </div>
    <div class="row d-flex justify-content-center {{ $step == 'status' ? '' : 'd-none' }}">
        <div class="row d-flex justify-content-center pb-3">
            <div class="col-md-6">
                <p class="text-center">{{ __('Step 4/5') }}</p>
            </div>
        </div>
        <div class="col-md-8 mb-3">
            <h1>{{ __('what will your group\'s status be?') }}</h1>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" wire:model="group_status" value="public">{{__('public')}}
                </label>
            </div>
            <div class="form-check">
                <label class="form-check-label">
                    <input type="radio" class="form-check-input" wire:model="group_status" value="private">{{__('private')}}
                </label>
            </div>
            @error('group_status') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-8 p-0">
                <button type="button" class="btn btn-light float-start" wire:click="back">{{ __('Back') }}</button>
                <button type="button" class="btn btn-primary float-end" wire:click="next" {{ $group_status ? '' : 'disabled' }}>{{ __('Next') }}</button>
            </div>
        </div>
    </div>


    <div class="row d-flex justify-content-center {{ $step == 'describe' ? '' : 'd-none' }}">
        <div class="row d-flex justify-content-center pb-3">
            <div class="col-md-6">
                <p class="text-center">{{ __('Step 5/5') }}</p>
            </div>
        </div>
        <div class="col-md-8">
            <h1>{{ __('Now describe what your group will be about') }}</h1>
            <p>{{ __('People will see this when we promote your group, but you\'ll be able to add to it later, too.') }}</p>
            <ol class="px-3">
                <li>{{ __('What\'s the purpose of the group?') }}</li>
                <li>{{ __('Who should join?') }}</li>
                <li>{{ __('What will you do at your events?') }}</li>
            </ol>
            <textarea class="form-control tinymce" placeholder="{{ __('Describe') }}" wire:model="group_describe"></textarea>
        </div>
        <div class="col-md-8 mb-3">
            @error('group_describe') <span class="error text-danger">{{ $message }}</span> @enderror
        </div>
        <div class="row d-flex justify-content-center">
            <div class="col-md-8 p-0">
                <button type="button" class="btn btn-light float-start" wire:click="back">{{ __('Back') }}</button>
                <button type="submit" class="btn btn-primary float-end" {{ $group_describe ? '' : 'disabled' }}>{{ __('Create your group') }}</button>
            </div>
        </div>
    </div>
</form>
