@extends('layouts.app', [
    'seo_title' => $group->name,
    'seo_description' => substr(strip_tags($group->describe ), 0, 180),
    'seo_image' => $group->avatar,
    'fixed_header' => true,
])

@section('title', $group->name)

@section('content')
    <section>
        @include('application.groups._nav', ['page' => 'about'])

        <div id="about" class="container py-5">
            <div class="row align-items-start">
                <div class="col-lg-7">
                    <h2 class="mb-3">{{ __('About') }}</h2>
                    @include('layouts._read_more', ['class' => 'mb-4', 'content' => $group->describe])

                    @if ($events_count)
                        <div class="row mb-3">
                            <div class="col">
                                <h2>{{ __('Upcoming Events') }} ({{ $events_count }})</h2>
                            </div>
                            <div class="col col-auto">
                                <a href="{{ route('groups.events', ['group' => $group->slug]) }}#events">{{ __('See all') }}</a>
                            </div>
                        </div>
                        <div class="mb-4">
                            @foreach ($upcoming_events as $event)
                                @include('application.groups.events._event_card', ['event' => $event, 'list_view' => true])
                            @endforeach
                        </div>
                    @endif

                    <div class="row mb-3">
                        <div class="col">
                            <h2>{{ __('Photos') }} ({{ count($photos) }})</h2>
                        </div>
                        <div class="col col-auto">
                            <a href="{{ route('groups.photos', ['group' => $group->slug]) }}#photos">{{ __('See all') }}</a>
                        </div>
                    </div>
                    <div class="mb-4">
                        @can('view_photos', $group)
                            @if (count($photos) > 0)
                                <div class="row gallery">
                                    @foreach ($photos as $photo)
                                        <div class="col-lg-3 col-6 mb-2">
                                            <a href="{{ asset($photo->getFullUrl()) }}" data-fancybox="gallery" data-title="{{ $photo->name }}">
                                                <div class="img-wrap ratio-16-9">
                                                    <div class="img-content">
                                                        <img class="rounded-sm border" src="{{ asset($photo->getFullUrl()) }}" alt="{{ $photo->name }}" />
                                                    </div>
                                                </div>
                                            </a>
                                            @can('delete_photo', $group)
                                                <button class="ms-1 btn btn-link p-0 fs-6 text-danger delete-confirm" href="{{ route('groups.photos.delete', ['group' => $group->slug, 'photo' => $photo->id]) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">
                                                    {{ __('Delete photo') }}
                                                </button>
                                            @endcan
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="col-12 d-flex flex-column align-items-center bg-light rounded p-5 mb-4">
                                    <i class="far fa-images fs-2"></i>
                                    <p class="fs-4 mb-0 mt-2">{{ __('No photos yet') }}</p>
                                </div>
                            @endif
                        @else
                            @include('application.components.visible-only-member')
                        @endcan
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <h2>{{ __('Discussions') }} ({{ count($discussions) }})</h2>
                        </div>
                        <div class="col col-auto">
                            <a href="{{ route('groups.discussions', ['group' => $group->slug]) }}#discussions">{{ __('See all') }}</a>
                        </div>
                    </div>
                    <div class="mb-4">
                        @can('viewAny', [\App\Models\Discussion::class, $group])
                            @if (count($discussions) > 0)
                                <div class="row">
                                    @foreach ($discussions as $discussion)
                                        <div class="col-12 mb-4">
                                            <a class="card card-link" href="{{ route('groups.discussions.details', ['group' => $group->slug, 'discussion' => $discussion->id]) }}#discussionDetails">
                                                <div class="card-body">
                                                    <h3 class="card-title">{{ $discussion->title }}</h3>
                                                    <div class="markdown text-truncate text-truncate-five-line">
                                                        {!! $discussion->content !!}
                                                    </div>
                                                </div>

                                                <div class="card-footer">
                                                    <div class="row align-items-center">
                                                        <div class="col-auto">
                                                            <span class="avatar border rounded-circle" style="background-image: url({{ $discussion->user->avatar }})"></span>
                                                        </div>
                                                        <div class="col">
                                                            <p class="mb-0">{!! __('Started by <strong>:full_name</strong>', ['full_name' => $discussion->user->full_name]) !!}</p>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="col-12 d-flex flex-column align-items-center bg-light rounded p-5 mb-4">
                                    <i class="far fa-comments fs-2"></i>
                                    <p class="fs-4 mb-0 mt-2">{{ __('No discussions yet') }}</p>
                                </div>
                            @endif
                        @else
                            @include('application.components.visible-only-member')
                        @endcan
                    </div>

                    @if ($topics_count)
                        <h2 class="mb-3">{{ __('Related topics') }}</h2>
                        @include('application.components.topic.topic-list', ['topics' => $group->topics, 'container_class' => 'mb-4', 'badge_class' => 'bg-primary', 'link' => true])
                    @endif

                    @if ($group->getSetting('facebook_url') or $group->getSetting('instagram_url') or $group->getSetting('twitter_url') or $group->getSetting('linkedin_url') or $group->getSetting('website_url'))
                        <h2 class="mb-3">{{ __('Find us at') }}</h2>
                        @if ($group->getSetting('facebook_url'))
                            <a class="btn border" target="_blank" href="{{ $group->getSetting('facebook_url') }}">
                                <i class="fab fa-facebook me-2"></i> {{ __('Facebook') }}
                            </a>
                        @endif
                        @if ($group->getSetting('instagram_url'))
                            <a class="btn border" target="_blank" href="{{ $group->getSetting('instagram_url') }}">
                                <i class="fab fa-instagram me-2"></i> {{ __('Instagram') }}
                            </a>
                        @endif
                        @if ($group->getSetting('twitter_url'))
                            <a class="btn border" target="_blank" href="{{ $group->getSetting('twitter_url') }}">
                                <i class="fab fa-twitter me-2"></i> {{ __('Twitter') }}
                            </a>
                        @endif
                        @if ($group->getSetting('linkedin_url'))
                            <a class="btn border" target="_blank" href="{{ $group->getSetting('linkedin_url') }}">
                                <i class="fab fa-linkedin me-2"></i> {{ __('Linkedin') }}
                            </a>
                        @endif
                        @if ($group->getSetting('website_url'))
                            <a class="btn border" target="_blank" href="{{ $group->getSetting('website_url') }}">
                                <i class="fas fa-link me-2"></i> {{ __('Website') }}
                            </a>
                        @endif
                    @endif
                </div>

                <div class="col-lg-4 offset-lg-1 sticky-top sticky-side">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col-auto">
                                    <span class="avatar avatar-md rounded-circle border" style="background-image: url({{ $organizer->avatar }})"></span>
                                </div>
                                <div class="col">
                                    <div class="fs-4 text-muted">{{ __('Organized by') }}</div>
                                    <a class="fs-3 fw-bold text-decoration-none text-dark" href="{{ route('profile', ['user' => $organizer->username]) }}">{{ $organizer->full_name }}</a>
                                </div>
                                {{--                                    <div class="fs-3 fw-bold">{{ $organizer->full_name }}</div>--}}
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col">
                            <h2 class="mb-3">{{ __('Members') }} ({{ $member_count }})</h2>
                        </div>
                        <div class="col col-auto">
                            <a href="{{ route('groups.members', $group->slug) }}#members">{{ __('See all') }}</a>
                        </div>
                    </div>
                    <div class="avatar-list">
                        @can('view_members', $group)
                            @foreach ($members as $member)
                                <a href="{{ route('profile', $member->username) }}" class="avatar avatar-md rounded-circle border" style="background-image: url({{ $member->avatar }})" data-bs-toggle="tooltip" data-bs-placement="top" title="{{ $member->full_name }}"></a>
{{--                                <img class="avatar avatar-md rounded-circle border" src="{{ $member->avatar }}" alt="{{ $member->first_name }}" data-bs-toggle="tooltip" data-bs-placement="bottom" title="{{ $member->full_name }}"/>--}}
                            @endforeach
                        @else
                            <div class="col-12">
                                @include('application.components.visible-only-member')
                            </div>
                        @endcan
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection