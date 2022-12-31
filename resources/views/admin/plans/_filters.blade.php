<form class="card" action="" method="GET">
    <div class="card-body">
        <strong class="card-title">{{ __('Filter') }}</strong>
        <div class="d-flex">
            <div class="form-group">
                <input name="filter[name]" type="text" class="form-control" value="{{ isset(Request::get("filter")['name']) ? Request::get("filter")['name'] : '' }}" placeholder="{{ __('Name') }}">
            </div>
            <button type="submit" class="btn btn-link">
                <i class="fa fa-filter me-2"></i>
                {{ __('Filter') }}
            </button>
            <a class="btn btn-link" href="{{ route('admin.plans') }}">
                <i class="fa fa-redo me-2"></i>
                {{ __('Clear') }}
            </a>
        </div>
    </div>
</form>
