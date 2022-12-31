<div>
    @if($event->isCancelled())
        <button class="btn btn-dark ms-2" disabled>
            {{ __('Cancelled') }}
        </button>
    @elseif($event->isPast())
        <button class="btn btn-dark ms-2" disabled>
            {{ __('Past Event') }}
        </button>
    @elseif(!$auth_user)
        <a class="btn btn-primary ms-2" href="{{ route('login') }}?_redirect={{ Request::path() }}">
            {{ __('Login to attend') }}
        </a>
    @elseif($auth_user->isAttending($event))
        <div class="ms-2">
            <p class="mb-0">{{ __('You are going!') }}</p>
            <a class="mb-0" href="#" wire:click="show_modal()">{{ __('Edit RSVP') }}</a>
        </div>
    @elseif($auth_user->isCandidateOf($event->group))
        <button class="btn btn-dark ms-2" wire:click="show_modal()">
            {{ __('Waiting approval') }}
        </button>
    @elseif(!$auth_user->isMemberOf($event->group) and $event->group->isClosed())
        <button class="btn btn-primary ms-2" wire:click="attend()">
            {{ __('Request to join') }}
        </button>
    @elseif($event->isRSVPOpen())
        <button class="btn btn-primary ms-2" wire:click="attend()">
            {{ $event->is_online ? __('Attend Online') : __('Attend') }}
        </button>
    @endif

    <div>
        <div class="modal fade {{ $show_modal ? 'show' : '' }}" id="attendEventModal" style="display: {{ $show_modal ? 'block' : 'none' }}"
            tabindex="-1"
            role="dialog"
            aria-labelledby="attendEventModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{ __('Complete your RSVP') }}</h5>
                        <button type="button" class="btn-close" wire:click.prevent="close_modal()"></button>
                    </div>
                    <div class="modal-body py-4">
                        @if ($auth_user && $auth_user->isAttending($event))
                            <p><strong>{{ __('Current response: ') }}</strong> {{ __('You\'re going') }}</p>

                            @if ($event->rsvp_question)
                                <div class="form-group mt-4">
                                    <label class="form-label">{{  __('What is your full name?') }}</label>
                                    <input class="form-control @error('rsvp_question_answer') is-invalid @enderror" type="text" wire:model="rsvp_question_answer">
                                    @error('rsvp_question_answer')<small class="form-text text-danger ps-1">{{ $message }}</small>@enderror
                                </div>
                            @endif

                            @if ($event->allowed_guests)
                                <div class="form-group mt-4">
                                    <label class="form-label">{{  __('Are you bringing anyone?') }} <small class="fw-normal">{{ __('Max: :count', ['count' => $event->allowed_guests]) }}</small></label>
                                    <input class="form-control @error('rsvp_guests') is-invalid @enderror" min="0" max="{{ $event->allowed_guests }}" type="number" wire:model="rsvp_guests">
                                    @error('rsvp_guests')<small class="form-text text-danger ps-1">{{ __('Please enter a value between 0 and :max', ['max' => $event->allowed_guests]) }}</small>@enderror
                                </div>
                            @endif

                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <button class="btn btn-light w-100" type="button" wire:click="update_rsvp('not_going')">{{ __('Not going') }}</button>
                                </div>
                                <div class="col-6">
                                    <button class="btn btn-primary w-100" type="button" wire:click="update_rsvp('going')">{{ __('Update') }}</button>
                                </div>
                            </div>
                        @elseif($auth_user and $auth_user->isCandidateOf($event->group))
                            <p>{{ __('Awesome! Your request to join this group was sent. Please wait some time to your hosts to check and approve your profile.') }}</p>
                            <button class="btn btn-primary float-end" type="button" wire:click.prevent="close_modal()">{{ __('Done!') }}</button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show" id="backdrop" style="display:{{ $show_modal ? 'block' : 'none' }}"></div>
    </div>
</div>

