<form class="card" action="" method="GET">
    <div class="card-body">
        <strong class="card-title">{{ __('Filter') }}</strong>
        <div class="d-flex">
            <div class="form-group">
                <input name="filter[title]" type="text" class="form-control" value="{{ isset(Request::get("filter")['title']) ? Request::get("filter")['title'] : '' }}" placeholder="{{ __('Title') }}">
            </div>
            <div class="form-group ms-2">
                <input name="filter[group]" type="text" class="form-control" value="{{ isset(Request::get("filter")['group']) ? Request::get("filter")['group'] : '' }}" placeholder="{{ __('Group') }}">
            </div>
            <div class="form-group ms-2">
                <select id="filter[status]" name="filter[status]" class="form-control">
                    <option selected value="">{{ __('Select Status') }}</option>
                    <option value="draft" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'draft' ? 'selected=""' : '' }}>{{ __('Draft') }}</option>
                    <option value="past" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'past' ? 'selected=""' : '' }}>{{ __('Past') }}</option>
                    <option value="cancelled" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'cancelled' ? 'selected=""' : '' }}>{{ __('Cancelled') }}</option>
                    <option value="upcoming" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'upcoming' ? 'selected=""' : '' }}>{{ __('Upcoming') }}</option>
                    <option value="deleted" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'deleted' ? 'selected=""' : '' }}>{{ __('Deleted') }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-link">
                <i class="fa fa-filter me-2"></i>
                {{ __('Filter') }}
            </button>
            <a class="btn btn-link" href="{{ route('admin.events') }}">
                <i class="fa fa-redo me-2"></i>
                {{ __('Clear') }}
            </a>
        </div>
    </div>
</form>
