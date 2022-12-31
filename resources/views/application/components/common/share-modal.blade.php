<div>
    <div class="modal fade {{ $show_modal ? 'show' : '' }}" id="shareModal" style="display: {{ $show_modal ? 'block' : 'none' }}" tabindex="-1" role="dialog" aria-labelledby="shareModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ __('Share') }}</h5>
                    <button type="button" class="btn-close" wire:click.prevent="close_modal()"></button>
                </div>
                <div class="modal-body">
                    <p>{{ __('Share this link via') }}</p>
                    <div class="btn-list">
                        <a class="btn btn-facebook btn-icon" href="https://www.facebook.com/sharer/sharer.php?u={{ $url }}" target="_blank">
                            <i class="fab fa-facebook fa-2x"></i>
                        </a>
                        <a class="btn btn-twitter btn-icon" href="https://twitter.com/intent/tweet?url={{ $url }}" target="_blank">
                            <i class="fab fa-twitter fa-2x"></i>
                        </a>
                        <a class="btn btn-linkedin btn-icon" href="https://www.linkedin.com/shareArticle?mini=true&url={{ $url }}" target="_blank">
                            <i class="fab fa-linkedin fa-2x"></i>
                        </a>
                        <a class="btn btn-pinterest btn-icon" href="https://pinterest.com/pin/create/button/?media=&description=&url={{ $url }}" target="_blank">
                            <i class="fab fa-pinterest fa-2x"></i>
                        </a>
                        <a class="btn btn-whatsapp btn-icon" href="https://wa.me/?text={{ $url }}" target="_blank">
                            <i class="fab fa-whatsapp fa-2x"></i>
                        </a>
                        <a class="btn btn-telegram btn-icon" href="https://telegram.me/share/url?url={{ $url }}" target="_blank">
                            <i class="fab fa-telegram fa-2x"></i>
                        </a>
                    </div>
                    <p class="my-3">{{ __('Or copy link') }}</p>
                    <div class="input-icon mb-3">
                        <span class="input-icon-addon">
                            <i class="fas fa-link"></i>
                        </span>
                        <input type="text" class="form-control" value="{{ $url }}" readonly>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-backdrop fade show" id="backdrop" style="display:{{ $show_modal ? 'block' : 'none' }}"></div>
</div>
