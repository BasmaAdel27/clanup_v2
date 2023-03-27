<a class="card card-link mb-3" href="{{ route('groups.about', [$group->slug,'x' => $group->id]) }}">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                <span class="avatar rounded border" style="background-image: url({{ $group->avatar }})"></span>
            </div>
            <div class="col">
                <div class="fw-bold text-truncate text-truncate-two-line">{{ $group->name }}</div>
                <div class="text-muted fst-italic">{{ $group->isOpen() ? __('Public Group') : __('Closed Group') }}</div>
            </div>
        </div>
    </div>
</a>
