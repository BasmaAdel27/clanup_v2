<div wire:click="save">
    @if($button)
        @if ($auth_user and $auth_user->isSaved($event))
            <button class="btn btn-dark w-100">
                <i class="fas fa-star"></i>
                {{ __('Saved') }}
            </button>
        @else
            <button class="btn btn-outline-dark w-100">
                <i class="far fa-star"></i>
                {{ __('Save') }}
            </button>
        @endif
    @else
        <i class="{{ ($auth_user and $auth_user->isSaved($event)) ? 'fas text-orange' : 'far' }} {{ $icon_class }} fa-star fs-3"></i>
    @endif
</div>