<form class="card" action="" method="GET">
    <div class="card-body">
        <strong class="card-title">{{ __('Filter') }}</strong>
        <div class="d-flex">
            <div class="form-group">
                <input name="filter[user]" type="text" class="form-control" value="{{ isset(Request::get("filter")['user']) ? Request::get("filter")['user'] : '' }}" placeholder="{{ __('Search user') }}">
            </div>
            <div class="form-group ms-2">
                <select id="filter[status]" name="filter[status]" class="form-control">
                    <option selected value="">{{ __('Select Status') }}</option>
                    <option value="active" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'active' ? 'selected=""' : '' }}>{{ __('Active') }}</option>
                    <option value="cancelled" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'cancelled' ? 'selected=""' : '' }}>{{ __('Cancelled') }}</option>
                    <option value="on_trial" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'on_trial' ? 'selected=""' : '' }}>{{ __('On trial') }}</option>
                    <option value="on_grace" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'on_grace' ? 'selected=""' : '' }}>{{ __('On grace') }}</option>
                    <option value="ended" {{ isset(Request::get("filter")['status']) && Request::get("filter")['status'] == 'ended' ? 'selected=""' : '' }}>{{ __('Ended') }}</option>
                </select>
            </div>
            <div class="form-group ms-2">
                <select id="filter[plan_id]" name="filter[plan_id]" class="form-control">
                    <option selected value="">{{ __('Select Plan') }}</option>
                    @foreach(get_all_plans_available() as $option)
                        <option value="{{ $option->id }}" {{ isset(Request::get("filter")['plan_id']) && Request::get("filter")['plan_id'] == $option->id ? 'selected=""' : '' }}>{{ $option->name }}</option>
                    @endforeach
                </select>
            </div>
            <button type="submit" class="btn btn-link">
                <i class="fa fa-filter me-2"></i>
                {{ __('Filter') }}
            </button>
            <a class="btn btn-link" href="{{ route('admin.subscriptions') }}">
                <i class="fa fa-redo me-2"></i>
                {{ __('Clear') }}
            </a>
        </div>
    </div>
</form>