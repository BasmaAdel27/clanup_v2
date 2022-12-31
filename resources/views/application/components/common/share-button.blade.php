<div>
    @if($button)
        <button class="btn btn-outline-primary" wire:click="$emit('showShareModal', '{{ $url }}')">
            <i class="far fa-share-square me-2"></i> {{ __('Share') }} 
        </button>
    @else
        <i class="fas fa-share-square fs-3 {{ $icon_class }}" wire:click="$emit('showShareModal', '{{ $url }}')"></i>
    @endif
</div>
