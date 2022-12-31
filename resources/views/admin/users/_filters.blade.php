<form class="card" action="" method="GET">
    <div class="card-body">
        <strong class="card-title">{{ __('Filter') }}</strong>
        <div class="d-flex">
            <div class="form-group">
                <input name="filter[search]" type="text" class="form-control" value="{{ isset(Request::get("filter")['search']) ? Request::get("filter")['search'] : '' }}" placeholder="{{ __('Search') }}">
            </div>
            <div class="form-group ms-2">
                <select id="filter[role]" name="filter[role]" class="form-control">
                    <option selected value="">{{ __('Select Role') }}</option>
                    <option value="admin" {{ isset(Request::get("filter")['role']) && Request::get("filter")['role'] == 'admin' ? 'selected=""' : '' }}>{{ __('Admin') }}</option>
                    <option value="user" {{ isset(Request::get("filter")['role']) && Request::get("filter")['role'] == 'user' ? 'selected=""' : '' }}>{{ __('User') }}</option>
                </select>
            </div>
            <button type="submit" class="btn btn-link">
                <i class="fa fa-filter me-2"></i>
                {{ __('Filter') }}
            </button>
            <a class="btn btn-link" href="{{ route('admin.users') }}">
                <i class="fa fa-redo me-2"></i>
                {{ __('Clear') }}
            </a>
        </div>
    </div>
</form>