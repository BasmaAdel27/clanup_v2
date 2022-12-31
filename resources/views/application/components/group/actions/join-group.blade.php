<div>
    @if ($auth_user and $auth_user->isMemberOf($group))
        <div class="d-inline-flex mt-3 mt-md-0">
            <div class="dropdown">
                <button class="btn btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    @if ($auth_user->hasOrganizerRolesOf($group))
                        {{ __('You\'re a organizer') }}
                    @else
                        {{ __('You\'re a member') }}
                    @endif  
                </button>
                <div class="dropdown-menu text-sm">
                    @can('update', $group)
                        <a class="dropdown-item" href="{{ route('groups.settings', $group->slug) }}">{{ __('Settings') }}</a>
                    @endcan 
                    @if (!$auth_user->isOrganizerOf($group))
                        <a class="dropdown-item" href="#" wire:click="unsubscribe_from_group()">{{ __('Leave this group') }}</a>
                    @endif  
                </div>
            </div>
            @if ($auth_user->hasOrganizerRolesOf($group))
                <a href="{{ route('groups.events.create', ['group' => $group->slug]) }}" class="btn btn-orange ms-2 text-nowrap">{{ __('Schedule event') }}</a>
            @endif 
        </div>
    @else 
        @if ($auth_user and $auth_user->isCandidateOf($group))
            <button class="btn btn-dark" wire:click="$set('show_modal', true)">{{ __('Waiting for approval') }}</button>
        @else
            <button class="btn btn-orange" wire:click.prevent="join_group()">{{ __('Join Group') }}</button>
        @endif
        <div>
            <div class="modal fade {{ $show_modal ? 'show' : '' }}" id="joinGroupModal" style="display: {{ $show_modal ? 'block' : 'none' }}"
                tabindex="-1"
                role="dialog"
                aria-labelledby="joinGroupModalLabel"
                aria-hidden="true"> 
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">{{ __('Join Group') }}</h5>
                            <button type="button" class="btn-close" wire:click.prevent="close_modal()"></button>
                        </div>
                        <div class="modal-body py-3">
                            @if ($need_pp)
                                <p>{{ __('You need a profile picture added to your profile before to request join this group.') }}</p>
                                <a href="{{ route('account.settings.general') }}">{{ __('Add profile picture') }}</a>
                            @endif

                            @if ($candidate or ($auth_user and $auth_user->isCandidateOf($group)))
                                <p>{{ __('Awesome! Your request to join this group was sent. Please wait some time to your hosts to check and approve your profile.') }}</p>
                                <div class="float-end">
                                    <button class="btn btn-primary" type="button" wire:click.prevent="close_modal()">{{ __('Done!') }}</button>
                                    <button class="btn btn-danger" type="button" wire:click.prevent="revert_candidate_request()">{{ __('Cancel Request') }}</button>
                                </div>
                            @endif
                            
                            @if ($welcome_member)
                                <p>{!! nl2br(e($group->getSetting('welcome_message'))) !!}</p>
                                <button class="btn btn-primary float-end" type="button" wire:click.prevent="close_modal()">{{ __('Done!') }}</button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-backdrop fade show" id="backdrop" style="display:{{ $show_modal ? 'block' : 'none' }}"></div>
        </div>
    @endif
</div>
