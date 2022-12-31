<div class="mb-4">
    <label class="form-label" for="name">{{ __('Name') }}</label>
    <input class="form-control" name="name" type="text" value="{{ $sponsor->name }}" placeholder="{{ __('Name of the sponsor') }}">
</div>

<div class="mb-4">
    <label class="form-label" for="description">{{ __('What is the sponsor offering the group?') }}</label>
    <input class="form-control" name="description" type="text" value="{{ $sponsor->description }}" placeholder="{{ __('Venue, food and drinks etc.') }}">
</div>

<div class="mb-4">
    <label class="form-label" for="website">{{ __('Website') }}</label>
    <input class="form-control" name="website" type="url" value="{{ $sponsor->website }}" placeholder="{{ __('Website url') }}">
</div>

<div class="mb-3">
    <label class="form-label" for="logo">{{ __('Logo') }}</label>
    @if ($sponsor->avatar)
        <p>
            <img class="img-thumbnail h-110px" src="{{ $sponsor->avatar }}">
        </p>
    @endif
    <input class="form-control" name="logo" type="file">
</div>

<div class="text-end">
    @if ($sponsor->id)
        <a class="text-danger me-3" href="{{ route('groups.settings.sponsors.delete', ['group' => $group->slug, 'sponsor' => $sponsor->id]) }}">{{ __('Delete') }}</a>
    @endif
    <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
</div>