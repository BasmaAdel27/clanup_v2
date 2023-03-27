@extends('layouts.app', [
    'seo_title' => $member->full_name,
    'seo_description' => __("View :member's profile :bio", ['member' => $member->full_name, 'bio' => $member->getSetting('bio')]),
    'seo_image' => $member->avatar
])

@section('content')
    <section class="container py-5">
        <div class="row">
            <div class="col-lg-3 me-lg-auto">
                <div class="card">
                    <div class="card-body p-3 text-center">
                        <span class="avatar avatar-xl mb-3 avatar-rounded border" style="background-image: url({{asset('assets/images/default-avatar.png')}})"></span>
                        <h3 class="m-0 mb-1"><a href="#">{{ $member->full_name }}</a></h3>
                        <div class="text-muted">{{ $member->getSetting('city') ? $member->getSetting('city') . ', ' : '' }} {{ $member->getSetting('country') }}</div>
                    </div>
                    <div class="d-flex flex-column align-items-center mb-3">
                        @if (!empty($member->getSetting('facebook')))
                            <a class="text-dark mb-2 fs-3" target="_blank" href="https://facebook.com/{{ $member->getSetting('facebook') }}">
                                <i class="fab fa-facebook text-primary"></i>
                                {{ $member->getSetting('facebook') }}
                            </a>
                        @endif
                        @if (!empty($member->getSetting('instagram')))
                            <a class="text-dark mb-2 fs-3" target="_blank" href="https://instagram.com/{{ $member->getSetting('instagram') }}">
                                <i class="fab fa-instagram text-pink"></i>
                                {{ $member->getSetting('instagram') }}
                            </a>
                        @endif
                        @if (!empty($member->getSetting('twitter')))
                            <a class="text-dark mb-2 fs-3" target="_blank" href="https://twitter.com/{{ $member->getSetting('twitter') }}">
                                <i class="fab fa-twitter text-blue"></i>
                                {{ $member->getSetting('twitter') }}
                            </a>
                        @endif
                        @if (!empty($member->getSetting('linkedin')))
                            <a class="text-dark mb-2 fs-3" target="_blank" href="https://linkedin.com/in/{{ $member->getSetting('linkedin') }}">
                                <i class="fab fa-linkedin text-primary"></i>
                                {{ $member->getSetting('linkedin') }}
                            </a>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-lg-9 ps-lg-5">
                <h1 class="hero-heading mb-0">{{ __('Hello, I\'m :first_name', ['first_name' => $member->first_name]) }}</h1>
                <div class="text-block mb-4">
                    <p class="my-2"><span class="badge bg-orange">{{ __('Joined at :year', ['year' => $member->created_at->year]) }}</span></p>
                    <p class="text-muted fs-3">{{ $member->getSetting('bio') }}</p>
                </div>

                @if ($member->getSetting('show_interests_on_profile') and $member->topics()->count() > 0)
                    <hr>
                    <h2 class="mb-3">{{ __('Interests') }}</h2>
                    @include('application.components.topic.topic-list', ['topics' => $member->topics, 'container_class' => '', 'badge_class' => 'bg-primary', 'link' => true])
                    <hr>
                @endif

                @if (count($groups) > 0)
                    <h2 class="mb-3">{{ __('Groups', ['first_name' => $member->first_name]) }}</h2>
                    <div class="scrolling-pagination mb-4">
                        @foreach ($groups as $group)
                            @include('application.groups._group_card', ['group' => $group])
                        @endforeach

                        <div class="d-none">
                            {{ $groups->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </section>
@endsection
