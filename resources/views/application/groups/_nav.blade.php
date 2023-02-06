<div>
    <div class="container pt-5 pb-3">
        <div class="row">
            <div class="col-md-6">
                <div class="img-wrap ratio-16-9">
                    <div class="img-content">
                        <img class="rounded border" src="{{ $group->avatar }}" alt="{{ $group->name }}">
                    </div>
                </div>
            </div> 
            <div class="col-md-6">
                <h1 class="fs-1 mb-2 mt-2 mt-lg-0 mb-3">{{ $group->name }}</h1>
                <div class="row align-items-center mb-2">
                    <div class="col-auto">
                        <span class="avatar bg-primary rounded-circle text-white">
                            <i class="fa fa-map-marker-alt"></i>
                        </span>
                    </div>
                    <div class="col fs-3">
                        {{ $group->address->name }}
                    </div>
                </div>

                <div class="row align-items-center mb-2">
                    <div class="col-auto">
                        <span class="avatar bg-primary rounded-circle text-white">
                            <i class="fa fa-user-friends"></i>
                        </span>
                    </div>
                    <div class="col fs-3">
                        {{ __(':count Members', ['count' => $group->member_count]) }} - <span class="fst-italic">{{ $group->isOpen() ? __('Public Group') : __('Closed Group') }}
                    </div>
                </div>

                <div class="row align-items-center mb-2">
                    <div class="col-auto">
                        <span class="avatar bg-primary rounded-circle text-white">
                            <i class="fa fa-user"></i>
                        </span>
                    </div>
                    <div class="col fs-3">
                        @if($group->co_organizers()->count() == 1)
{{--                            // Organizer name clickable to profile--}}
                            {!! __('Organized by <strong>:organizer_name</strong>, and <strong>:co_organizer_name</strong>', [
                                'organizer_name' => $group->createdBy->full_name,
                                'co_organizer_name' => $group->co_organizers()->first()->full_name,
                            ]) !!}
                        @elseif($group->co_organizers()->count() > 1)
                            {!! __('Organized by <strong>:organizer_name</strong>, and <strong>:count others</strong>', [
                                'organizer_name' => $group->createdBy->full_name,
                                'count' => $group->co_organizers()->count(),
                            ]) !!}
                        @else
                            {!! __('Organized by <strong>:organizer_name</strong>', [
                                'organizer_name' => $group->createdBy->full_name,
                            ]) !!}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="bg-white sticky-top border py-2">
    <div class="container d-flex flex-column flex-md-row align-items-md-center justify-content-between">
        <ul class="nav mobile-horizontal">
            <li class="nav-item">
                <a class="nav-link fs-3 ps-0 {{ $page == 'about' ? 'fw-bold text-primary' : '' }}" href="{{ route('groups.about', $group->slug) }}#about">{{ __('About') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-3 {{ $page == 'events' ? 'fw-bold text-primary' : '' }}" href="{{ route('groups.events', ['group' => $group->slug]) }}#events">{{ __('Events') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-3 {{ $page == 'members' ? 'fw-bold text-primary' : '' }}" href="{{ route('groups.members', $group->slug) }}#members">{{ __('Members') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-3 {{ $page == 'photos' ? 'fw-bold text-primary' : '' }}" href="{{ route('groups.photos', $group->slug) }}#photos">{{ __('Photos') }}</a>
            </li>
            <li class="nav-item">
                <a class="nav-link fs-3 {{ $page == 'discussions' ? 'fw-bold text-primary' : '' }}" href="{{ route('groups.discussions', $group->slug) }}#discussions">{{ __('Discussions') }}</a>
            </li>
        </ul>
        <div>
            @livewire('group.actions.join-group', ['group' => $group], key($group->slug))
        </div>
    </div>
</div>
