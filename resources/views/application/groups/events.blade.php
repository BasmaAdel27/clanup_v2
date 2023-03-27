@extends('layouts.app', [
    'seo_title' => __(':group Events', ['group' => $group->name]),
    'seo_description' => substr(strip_tags($group->describe ), 0, 180),
    'seo_image' => $group->avatar,
    'seo_og_type' => 'articles',
    'fixed_header' => true,
])

@section('content')
    <section>
        @include('application.groups._nav', ['page' => 'events'])

        <div id="events" class="container py-5">
            <div class="row align-items-start">
                <div class="col-lg-3 sticky-top sticky-side">
                    <div class="card card-fluid">
                        <div class="card-header">
                            <div class="card-title">{{ __('Filter') }}</div>
                        </div>
                        <nav class="nav side-nav flex-row flex-nowrap flex-lg-column flex-lg-wrap">
                            @if ($auth_user and $auth_user->hasOrganizerRolesOf($group))
                                <a class="nav-link text-nowrap {{ $tab == 'draft' ? 'text-primary fw-bold' : '' }}" href="{{ route('groups.events.draft', ['group' => $group->slug,'x'=>$group->id]) }}#events">
                                    {{ __('Drafts') }}
                                </a>
                            @endif  
                            <a class="nav-link text-nowrap {{ $tab == 'upcoming' ? 'text-primary fw-bold' : '' }}" href="{{ route('groups.events', ['group' => $group->slug,'x'=>$group->id]) }}#events">
                                {{ __('Upcoming') }}
                            </a>
                            <a class="nav-link text-nowrap {{ $tab == 'past' ? 'text-primary fw-bold' : '' }}" href="{{ route('groups.events.past', ['group' => $group->slug,'x'=>$group->id]) }}#events">
                                {{ __('Past') }}
                            </a>
                        </nav>
                    </div>
                </div>
            
                <div class="col-lg-9">
                    <div class="scrolling-pagination">
                        @foreach ($events as $event)
                            @include('application.groups.events._event_card', ['event' => $event, 'list_view' => true])
                        @endforeach

                        <div class="d-none">
                            {{ $events->links() }}
                        </div>
                    </div>
                    
                    @if(count($events) <= 0)
                        <div class="col-12 d-flex flex-column align-items-center bg-light rounded p-5 mb-4">
                            <i class="far fa-calendar-alt fs-2"></i>
                            <p class="fs-4 mb-0 mt-2">{{ __('No events yet') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection