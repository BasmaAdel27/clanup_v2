@extends('layouts.app', [
    'seo_title' => __('Delete :group', ['group' => $group->name]),
])

@section('content')
    <section class="container py-5">
        <div class="row gx-5">
            @include('application.groups.settings._sidebar', ['page' => 'delete'])

            <div class="col-12 col-lg-8 mb-5 mb-lg-0">
                <h1>{{ __('Delete group') }}</h1>
                <p class="mb-4">{{ __('If you delete this group, all content -- including members, past events, photos, and discussions -- will be removed and cannot be undo.') }}</p>
                
                <form id="delete_group_form" action="{{ route('groups.settings.basic.delete', ['group' => $group->slug]) }}" method="POST">
                    @csrf
                    <div class="form-group mb-4">
                        <textarea class="form-control" name="delete_reason" rows="5" placeholder="{{ __('Please explain why are you deleting this group.') }}"></textarea>
                    </div>
                    <div class="form-group d-flex justify-content-end">
                        <button data-form="delete_group_form" class="btn btn-danger delete-confirm" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This group will be deleted.') }}">{{ __('Delete Group') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection