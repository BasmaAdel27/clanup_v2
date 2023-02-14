

<div wire:key="{{ time().$group->uid }}" class="row">
    <div class="col-12 col-md-2">
        <a href="{{ route('groups.about', ['group'=>$group->slug,'x' => $group->id]) }}">
            <div class="img-wrap ratio-16-9">
                <div class="img-content">
                    <img class="rounded-sm border" src="{{ $group->avatar }}" alt="{{ $group->name }}" />
                </div>
            </div>
        </a>
    </div>
    <div class="d-flex flex-column col-12 col-md-10">
        <div>
            <h5 class="text-truncate text-truncate-two-line text-black fs-3">
                <a class="text-gray-900 text-decoration-none" href="{{ route('groups.about', $group->slug) }}">
                    {{ $group->name }}
                </a>
            </h5>
            <p class="text-muted fs-4">
                {{ $group->address->name }}
            </p>
            <p class="text-truncate text-truncate-two-line fs-5">
                {{ strip_tags($group->describe) }}
            </p>
        </div>
        <div class="d-flex flex-row align-items-center justify-content-between text-muted fs-7 mt-auto">
            <div>
                {{ __(':count Members', ['count' => $group->member_count]) }} - <span class="fst-italic">{{ $group->isOpen() ? __('Public Group') : __('Closed Group') }}</span>
            </div>
            <div class="d-flex">
                @livewire('common.share-button', ['icon_class' => 'ms-2', 'button' => false, 'url' => route('groups.about', $group->slug)], key($group->id))
            </div>
        </div>
    </div>
    <div class="col-12"><hr></div>
</div>

