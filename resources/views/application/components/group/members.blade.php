<div class="row align-items-start">
    <div class="col-lg-3 mb-4">
        <div class="card card-fluid">
            <nav class="nav side-nav flex-row flex-nowrap flex-lg-column flex-lg-wrap">
                @if ($auth_user and $auth_user->hasAboveAssistantRolesOf($group))
                    <a class="nav-link text-nowrap {{ $tab == 'candidates' ? 'text-primary fw-bold' : '' }}" wire:click="$set('tab', 'candidates')">
                        {{ __('Candidates') }} ({{ $group_candidates_count }})
                    </a>
                @endif
                <a class="nav-link text-nowrap {{ $tab == 'all' ? 'text-primary fw-bold' : '' }}" wire:click="$set('tab', 'all')">
                    {{ __('All Members') }} ({{ $group_member_count }})
                </a>
                <a class="nav-link text-nowrap {{ $tab == 'organizers' ? 'text-primary fw-bold' : '' }}" wire:click="$set('tab', 'organizers')">
                    {{ __('Organizers') }} ({{ $group_organizers_count }})
                </a>
            </nav>
        </div>
    </div>

    <div class="col-md-9 col-xs-12">
        @can('view_members', $group)
            <div class="list-group card-list-group border">
                <div class="list-header p-3">
                    <h2>{{ __('All Members') }}</h2>
                    <div class="input-icon">
                        <span class="input-icon-addon">
                            <i class="fa fa-search"></i>
                        </span>
                        <input class="form-control" autocomplete="off" placeholder="{{ __('Search') }}" type="search" wire:model="search">
                    </div>
                </div>

                @foreach ($members as $member)
                    <div class="list-group-item p-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-auto">
                                <img class="avatar rounded shadow-0 border" src="{{ $member->avatar }}" alt="{{ $member->full_name }}">
                            </div>
                            <div class="col">
                                <p class="mb-0 fw-bold">{{ $member->full_name }}</p>
                                <p class="mb-0 text-muted">{{ $member->getRoleOf($group) }} • <time class="fst-italic" datetime="{{ convertToLocal($member->pivot->created_at, 'U') }}">{{ __('Joined :date', ['date' => convertToLocal($member->pivot->created_at, 'jS F Y')]) }}</time></p>
                            </div>
                            <div class="col-auto lh-1">
                                @if ($auth_user and $auth_user->hasAboveAssistantRolesOf($group) and !$member->isOrganizerOf($group))
                                    <div class="dropdown">
                                        <a href="#" class="link-secondary" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="fas fa-ellipsis-v"></i>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            @if ($auth_user->isOrganizerOf($group) and $member->pivot->membership != \App\Models\GroupMembership::CO_ORGANIZER)
                                                <a class="dropdown-item" wire:click="changeRoleOfMember({{ $member->id }}, {{ \App\Models\GroupMembership::CO_ORGANIZER }})">{{ __('Make Co-organizer') }}</a>
                                            @endif

                                            @if ($auth_user->isOrganizerOf($group) or $auth_user->isCoOrganizerOf($group))
                                                @if ($member->pivot->membership != \App\Models\GroupMembership::ASSISTANT_ORGANIZER)
                                                    <a class="dropdown-item" wire:click="changeRoleOfMember({{ $member->id }}, {{ \App\Models\GroupMembership::ASSISTANT_ORGANIZER }})">{{ __('Make Assistant Organizer') }}</a>
                                                @endif

                                                @if ($member->pivot->membership != \App\Models\GroupMembership::EVENT_ORGANIZER)
                                                    <a class="dropdown-item" wire:click="changeRoleOfMember({{ $member->id }}, {{ \App\Models\GroupMembership::EVENT_ORGANIZER }})">{{ __('Make Event Organizer') }}</a>
                                                @endif

                                                @if ($member->pivot->membership > \App\Models\GroupMembership::MEMBER || $member->pivot->membership == \App\Models\GroupMembership::CANDIDATE)
                                                    <a class="dropdown-item" wire:click="changeRoleOfMember({{ $member->id }}, {{ \App\Models\GroupMembership::MEMBER }})">{{ __('Make Member') }}</a>
                                                @endif
                                            @endif
                                            <hr class="my-1">
                                            <a class="dropdown-item text-danger" wire:click="changeRoleOfMember({{ $member->id }}, {{ \App\Models\GroupMembership::REMOVED }})">{{ __('Remove from group') }}</a>
                                            <a class="dropdown-item text-danger" wire:click="changeRoleOfMember({{ $member->id }}, {{ \App\Models\GroupMembership::BLACKLISTED }})">{{ __('Remove & Ban from group') }}</a>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach

                @if (count($members) <= 0)
                    <div class="list-group-item list-group-item-action text-muted text-center p-4">
                        <i class="fas fa-user-friends fs-4"></i>
                        <p class="mb-0 mt-2">{{ __('No members yet') }}</p>
                    </div>
                @endif

                @if ($group_member_count > $member_limit)
                    <div class="list-header p-3 text-center">
                        <a class="btn btn-orange rounded-pill" wire:click="loadMore">{{ __('Load more') }}</a>
                    </div>
                @endif
            </div>
        @else
            @include('application.components.visible-only-member')
        @endcan
    </div>
</div>
