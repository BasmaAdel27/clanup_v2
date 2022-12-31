<div class="card p-3 mb-4">
    <div class="row">
        <div class="col-12 card-body">
            <div class="form-group mb-4 required">
                <label class="mb-1" for="name">{{ __('Name') }}</label>
                <input name="name" type="text" class="form-control" placeholder="{{ __('Name') }}" value="{{ $topic_category->name }}" required>
            </div>

            @if ($topic_category->id)
                <div class="form-group mb-4 required">
                    <label class="mb-1" for="slug">{{ __('Slug') }}</label>
                    <input name="slug" type="text" class="form-control" placeholder="{{ __('Slug') }}" value="{{ $topic_category->slug }}">
                </div>
            @endif
            
            <div class="text-end">
                @if ($topic_category->id)
                    <a class="btn btn-outline-danger delete-confirm" href="{{ route('admin.topic_categories.delete', $topic_category->id) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">{{ __('Delete') }}</a>
                @endif
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
