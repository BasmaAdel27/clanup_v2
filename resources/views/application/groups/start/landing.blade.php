@extends('layouts.app', [
    'seo_title' => __('Start a Group'),
    'seo_description' => __("We'll walk you through a few steps to build your local community"),
])

@section('content')
    <section class="border-bottom py-5">
        <div class="container">
            <div class="row d-flex align-items-center">
                <div class="col-lg-6 text-center text-lg-start">
                    <p class="text-secondary">{{ __('BECOME AN ORGANIZER') }}</p>
                    <h1>{{ __("We'll walk you through a few steps to build your local community") }}</h1>
                    <a class="btn btn-orange" href="{{ route('start.create') }}">{{ __('Get started') }}</a>
                </div>
            </div>
        </div>
    </section>

    <section class="border-bottom py-5">
        <div class="container">
            <h2 class="text-center">{{ __('Organize your way, on your schedule') }}</h2>
            <div class="row mt-5">
                <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                    <img src="{{ asset('assets/images/start/find-members.svg') }}" width="120" alt="{{ $application_name }} {{ __('Find your members') }}" />
                    <h3>{{ __('Find your members') }}</h3>
                    <p class="text-muted">{{ __('We\'ll help you find interested members and you can approve them to be sure they\'ll be a good fit for your community.') }}</p>
                </div>
                <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                    <img src="{{ asset('assets/images/start/schedule-event.svg') }}" width="120" alt="{{ $application_name }} {{ __('Schedule events in minutes') }}" />
                    <h3>{{ __('Schedule events in minutes') }}</h3>
                    <p class="text-muted">{{ __('Organizer tools make it easy to schedule and manage your events. You decide when, where, and how often your group meets.') }}</p>
                </div>
                <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                    <img src="{{ asset('assets/images/start/get-help-from-others.svg') }}" width="120" alt="{{ $application_name }} {{ __('Have others help you host') }}" />
                    <h3>{{ __('Have others help you host') }}</h3>
                    <p class="text-muted">{{ __('You don\'t have to do it alone. Recruit a leadership team to help you host events and manage your group.') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 bg-primary">
        <div class="container">
            <div class="row">
                <div class="col-sm-4 col-lg-5">
                    <div class="p-3 p-5-lg-5">
                        <img class="img-fluid rounded-circle shadow-sm" src="{{ asset('assets/images/start/testimonial.png') }}" alt="{{ __('Meredith Goodwin, Founder') }}">
                    </div>
                </div>
                <div class="col-sm-8 col-lg-6 d-flex align-items-center">
                    <div>
                        <blockquote class="blockquote-icon">
                            <p class="h2 mb-4">{{ __('Samsa was a travelling salesman - and above it there hung a picture that he had recently cut out of an illustrated magazine and housed in a nice, gilded frame.') }}</p>
                            <p class="h4 text-uppercase">— {{ __('Meredith Goodwin, Founder') }}</p>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5">
        <div class="container">
            <h2 class="text-center">{{ __('How to get started') }}</h2>
            <p class="text-center">{{ __('Here\'s an overview of how you\'ll create your Network group, from start to finish.') }}</p>
            <div class="row mt-5">
                <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                    <img src="{{ asset('assets/images/start/step-1.svg') }}" width="120" alt="{{ $application_name }} {{ __('Create a group') }}" />
                    <h3>{{ __('Create a group') }}</h3>
                    <p class="text-muted">{{ __('Decide what the group is about, who should join, and what you\'ll do at your events.') }}</p>
                </div>
                <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                    <img src="{{ asset('assets/images/start/step-2.svg') }}" width="120" alt="{{ $application_name }} {{ __('Review and submit') }}" />
                    <h3>{{ __('Review and submit') }}</h3>
                    <p class="text-muted">{{ __('Your group will be reviewed and shared with members who have similar interests.') }}</p>
                </div>
                <div class="col-lg-4 mb-3 mb-lg-0 text-center">
                    <img src="{{ asset('assets/images/start/step-3.svg') }}" width="120" alt="{{ $application_name }} {{ __('Plan your first event') }}" />
                    <h3>{{ __('Plan your first event') }}</h3>
                    <p class="text-muted">{{ __('Use :app_name\'s organizer tools to schedule your events and manage your attendee lists.', ['app_name' => $application_name]) }}</p>
                </div>
            </div>
        </div>
    </section>

    <section id="pricing" class="py-5 bg-primary">
        <div class="container">
            <div class="row gx-5 d-flex align-items-center">
                <div class="col-lg-6">
                    <h2 class="mb-4 text-white">{{ __('Subscribe to find people who share your interests') }}</h2>
                    <h4 class="mb-4 text-white">{{ __('With your subscription, you can:') }}</h4>
                    <ul class="fa-ul text-white ">
                        <li class="mb-3">
                            <span class="fa-li"><i class="fas fa-check"></i></span>{{ __('Assign co-organizers to help you host events') }}
                        </li>
                        <li class="mb-3">
                            <span class="fa-li"><i class="fas fa-check"></i></span>{{ __('Get support from our community experts 7 days a week') }}
                        </li>
                        <li class="mb-3">
                            <span class="fa-li"><i class="fas fa-check"></i></span>{{ __('Schedule events and manage your attendee lists') }}
                        </li>
                    </ul>
                </div>

                <div class="col-lg-6">
                    <p class="h2 text-white mb-2">{{ __('Starting at:') }}</p>
                    @if ($most_cheap_plan)
                        <p class="h3 text-white">{{ money($most_cheap_plan->price, $most_cheap_plan->currency, true) }} <span>/ {{ __('month') }}</span></p>
                    @endif
                    <a class="btn btn-outline-light mt-3" href="{{ route('checkout.plans') }}">{{ __('Get started') }}</a>
                </div>
            </div>
        </div>
    </section>

    <section class="py-5 border-bottom">
        <div class="container">
            <h2 class="text-primary mb-4">{{ __('Frequently asked questions') }}</h2>
            <div class="row gx-5">
                <div class="col-lg-6">
                    <p class="h4">{{ __('How much of a time commitment is expected?') }}</p>
                    <p class="text-muted mb-5">{{ __('As a :app_name organizer, you decide how often your group meets. You can even recruit others to help you host events and share responsibilities as the organizer.', ['app_name' => $application_name]) }}</p>
                    <p class="h4">{{ __('How can I grow my :app_name group?', ['app_name' => $application_name]) }}</p>
                    <p class="text-muted">{{ __(':app_name will announce your new group to members in your area who share your interests. Every event you schedule will be announced to your group\'s members, and easily findable to non-members who are looking for events like yours. The more events your group hosts, the faster it\'s likely to grow. Sharing your upcoming events on your other social networks will also help attract people who aren\'t already :app_name members.', ['app_name' => $application_name]) }}</p>
                </div>
                <div class="col-lg-6">
                    <p class="h4">{{ __('How can I monetize my :app_name group?', ['app_name' => $application_name]) }}</p>
                    <p class="text-muted mb-5">{{ __('There are several ways that you can share costs, or even make a profit as an organizer. You can choose to charge your members dues, or you can ticket each event.') }}</p>
                    <p class="h4">{{ __('How will :app_name help me be successful?', ['app_name' => $application_name]) }}</p>
                    <p class="text-muted">{{ __(':app_name provides the tools and resources you need to build a great community. We\'ll announce your group to people in your area who share your interests, and continuously help new :app_name members find your group and your events. Once you schedule events, :app_name manages your attendee lists and reminders. You\'ll have access to support 7 days a week, and access to articles and tips on how to grow a successful community.', ['app_name' => $application_name]) }}</p>
                </div>
            </div>
        </div>
    </section>

    @if (count($organizer_help_blogs) > 0)
        <section class="py-5">
            <div class="container">
                <h2 class="text-center">{{ __('We\'re here to help') }}</h2>
                <p class="text-center mb-4">{{ __('The :app_name Organizer Guide will help you learn how to build a successful community.', ['app_name' => $application_name]) }}</p>
                <div class="row">
                    @foreach ($organizer_help_blogs as $blog)
                        <div class="col-lg-4 mb-4">
                            @include('application.static.blog._blog_card', ['blog' => $blog])
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection
