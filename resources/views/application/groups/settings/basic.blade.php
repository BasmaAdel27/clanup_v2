@extends('layouts.app', [
    'seo_title' => __('Group Settings'),
])

@push('page_head_scripts')
    <script src="{{ asset('assets/libs/tinymce/tinymce.min.js') }}"></script>
    <script>
        tinymce.init({
            selector: 'textarea.tinymce',
            menubar: false,
            paste_auto_cleanup_on_paste : true,
            paste_remove_styles: true,
            paste_remove_styles_if_webkit: true,
            paste_strip_class_attributes: true,
            plugins: 'lists paste',
            toolbar: 'undo redo | bullist numlist',
        });
    </script>
@endpush

@section('content')
    <section class="container py-5">
        <div class="row gx-5">
            @include('application.groups.settings._sidebar', ['page' => 'basic'])

            <div class="col-12 col-lg-8 mb-5 mb-lg-0">
                @include('layouts._form_errors')

                <form action="{{ route('groups.settings.basic.update', ['group' => $group->slug]) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <h1 class="mb-4">{{ __('Basic Settings') }}</h1>
                        
                    <div class="form-group mb-4">
                        <label class="form-label" for="group_name">{{ __('Group Name') }}</label>
                        <input class="form-control" type="text" name="group_name" value="{{ $group->name }}">
                    </div>

                    <div class="form-group mb-4">
                        <label class="form-label" for="group_describe">{{ __('Group Description') }}</label>
                        <textarea class="form-control tinymce" name="group_describe">{{ $group->describe }}</textarea>
                    </div>

                    <div class="form-group place_autocomplete_container mb-4">
                        <label class="form-label" for="place_autocomplete">{{ __('Location') }}</label>
                        <input id="place_autocomplete" class="form-control place_autocomplete" autocomplete="off" data-type="(regions)" placeholder="{{ __('Location') }}" type="text" name="location_name" value="{{ $group->address->name }}">
                        <input type="hidden" name="place" id="place_name" value="{{ $group->address->name }}">
                        <input type="hidden" name="address_1" id="formatted_address" value="{{ $group->address->address_1 }}">
                        <input type="hidden" name="lat" id="lat" value="{{ $group->address->lat }}">
                        <input type="hidden" name="lng" id="lng" value="{{ $group->address->lng }}">
                        <input type="hidden" name="country" id="country" value="{{ $group->address->country }}">
                        <input type="hidden" name="state" id="state" value="{{ $group->address->state }}">
                        <input type="hidden" name="city" id="city" value="{{ $group->address->city }}">
                        <input type="hidden" name="postal_code" id="postal_code" value="{{ $group->address->zip }}">
                    </div>

                    <div class="form-group mb-4">
                        @if ($group->avatar)
                            <p>
                                <img class="img-thumbnail h-110px" src="{{ $group->avatar }}">
                            </p>
                        @endif
                        <label class="form-label" for="group_featured_photo">{{ __('Update featured photo') }}</label>
                        <input class="form-control" name="group_featured_photo" type="file" accept="image/png, image/jpeg">
                        <small class="form-text">({{  __('Recommended size 1290x868px') }})</small>
                    </div>

                    <div class="text-end">
                        <a class="text-danger text-decoration-underline me-3" href="{{ route('groups.settings.basic.delete_view', ['group' => $group->slug]) }}">{{ __('Delete Group') }}</a>
                        <button type="submit" class="btn btn-primary">{{ __('Update settings') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection