<div class="card p-3 mb-4">
    <div class="row">
        <div class="col-12 card-body">
            <div class="form-group mb-4 required">
                <label class="mb-1" for="topic_category">{{ __('Topic Category') }}</label>
                <select name="topic_category_id" class="form-control">
                    <option value="">{{ __('Please select') }}</option>
                    @foreach(get_all_topic_categories() as $option)
                        <option value="{{ $option->id }}" {{ $topic->topic_category_id == $option->id ? 'selected=""' : '' }}>{{ $option->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group mb-4 required">
                <label class="mb-1" for="name">{{ __('Name') }}</label>
                <input name="name" type="text" class="form-control" placeholder="{{ __('Name') }}" value="{{ $topic->name }}" required>
            </div>

            @if ($topic->id)
                <div class="form-group mb-4 required">
                    <label class="mb-1" for="slug">{{ __('Slug') }}</label>
                    <input name="slug" type="text" class="form-control" placeholder="{{ __('Slug') }}" value="{{ $topic->slug }}">
                </div>
            @endif
            
            <div class="text-end">
                @if ($topic->id)
                    <a class="btn btn-outline-danger delete-confirm" href="{{ route('admin.topics.delete', $topic->id) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">{{ __('Delete') }}</a>
                @endif
                <button type="submit" class="btn btn-primary">{{ __('Save') }}</button>
            </div>
        </div>
    </div>
</div>
