<form class="card" action="" method="GET">
    <div class="card-body">
        <strong class="card-title">{{ __('Filter') }}</strong>
        <div class="d-flex">
            <div class="form-group">
                <input name="filter[name]" type="text" class="form-control" value="{{ isset(Request::get("filter")['name']) ? Request::get("filter")['name'] : '' }}" placeholder="{{ __('Name') }}">
            </div>
            <div class="form-group ms-2">
                <input name="filter[organizer]" type="text" class="form-control" value="{{ isset(Request::get("filter")['organizer']) ? Request::get("filter")['organizer'] : '' }}" placeholder="{{ __('Organizer') }}">
            </div>
            <div class="form-group ms-2">
                <select id="filter[status]" name="filter[status]" class="form-control">
                    <option selected value="">{{ __('Select Status') }}</option>
                    <option value="public" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'public' ? 'selected=""' : '' }}>{{ __('Public') }}</option>
                    <option value="closed" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'closed' ? 'selected=""' : '' }}>{{ __('Closed') }}</option>
                    <option value="deleted" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'deleted' ? 'selected=""' : '' }}>{{ __('Deleted') }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-link">
                <i class="fa fa-filter me-2"></i>
                {{ __('Filter') }}
            </button>
            <a class="btn btn-link" href="{{ route('admin.groups') }}">
                <i class="fa fa-redo me-2"></i>
                {{ __('Clear') }}
            </a>
        </div>
    </div>
</form>
