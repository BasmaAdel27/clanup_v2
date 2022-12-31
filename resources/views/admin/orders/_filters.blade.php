<form class="card" action="" method="GET">
    <div class="card-body">
        <strong class="card-title">{{ __('Filter') }}</strong>
        <div class="d-flex">
            <div class="form-group">
                <input name="filter[id]" type="text" class="form-control" value="{{ isset(Request::get("filter")['id']) ? Request::get("filter")['id'] : '' }}" placeholder="{{ __('Order ID') }}">
            </div>
            <div class="form-group ms-2">
                <input name="filter[transaction_id]" type="text" class="form-control" value="{{ isset(Request::get("filter")['transaction_id']) ? Request::get("filter")['transaction_id'] : '' }}" placeholder="{{ __('Transaction ID') }}">
            </div>
            <div class="form-group ms-2">
                <input name="filter[user]" type="text" class="form-control" value="{{ isset(Request::get("filter")['user']) ? Request::get("filter")['user'] : '' }}" placeholder="{{ __('Search user') }}">
            </div>
            <div class="form-group ms-2">
                <select id="filter[plan_id]" name="filter[plan_id]" data-toggle="select" class="form-control">
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
            <a class="btn btn-link" href="{{ route('admin.orders') }}">
                <i class="fa fa-redo me-2"></i>
                {{ __('Clear') }}
            </a>
        </div>
    </div>
</form>