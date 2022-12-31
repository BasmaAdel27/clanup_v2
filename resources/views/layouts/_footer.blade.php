<footer class="position-relative z-index-10 d-print-none">
    @if (!isset($hide_top_footer))
        <div class="bg-dark">
            <div class="container">
                <div class="row">
                    <div class="col-12 d-flex justify-content-between align-items-center mt-5">
                        <div class="d-flex flex-row align-items-center">
                            <p class="fs-3 mb-0">{{ __('Create your own group.') }}</p>
                            <a class="btn btn-outline-light ms-4" href="{{ route('start.index') }}">{{ __('Get started') }}</a>
                        </div>
                        @if (count($languages) > 1)
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" id="changeLanguage" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    {{ get_language_name(app()->getLocale()) }}
                                </button>
                                <div class="dropdown-menu overflow-hidden" aria-labelledby="changeLanguage">
                                    @foreach ($languages as $language => $name)
                                        <a class="dropdown-item" href="/change-language/{{ $language }}">{{ get_language_name($name) }}</a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                    <hr class="my-4">
                    <div class="col-md-4 mb-5 mb-lg-0">
                        <div class="fw-bold text-uppercase mb-3">{{ __('Your Account') }}</div>
                        <ul class="list-inline">
                            @if ($auth_user)
                                <li><a class="text-white" href="{{ route('account.settings.general') }}">{{ __('Settings') }}</a></li>
                                <li><a class="text-white" href="{{ route('profile', $auth_user->username) }}">{{ __('Profile') }}</a></li>
                                <li><a class="text-white" href="{{ route('events') }}">{{ __('My events') }}</a></li>
                            @else
                                <li><a class="text-white" href="{{ route('register') }}">{{ __('Sign up') }}</a></li>
                                <li><a class="text-white" href="{{ route('login') }}">{{ __('Log in') }}</a></li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-4 mb-5 mb-lg-0">
                        <div class="fw-bold text-uppercase mb-3">{{ __('Discover') }}</div>
                        <ul class="list-unstyled">
                            <li><a class="text-white" href="{{ route('find', ['source' => 'GROUPS']) }}">{{ __('Groups') }}</a></li>
                            <li><a class="text-white" href="{{ route('find', ['source' => 'EVENTS']) }}">{{ __('Events') }}</a></li>
                            <li><a class="text-white" href="{{ route('find', ['source' => 'EVENTS', 'type' => 2]) }}">{{ __('Online Events') }}</a></li>
                            <li><a class="text-white" href="{{ route('topics') }}">{{ __('Browse Topics') }}</a></li>
                        </ul>
                    </div>
                    <div class="col-md-4 mb-5 mb-lg-0">
                        <div class="fw-bold text-uppercase mb-3">{{ $application_name }}</div>
                        <ul class="list-unstyled">
                            <li>
                                <a class="text-white" href="{{ route('blog') }}">{{ __('Blog') }}</a>
                            </li>
                            @foreach (\App\Models\Page::where('is_active', 1)->where('show_on_footer', 1)->get() as $page)
                                <li>
                                    <a class="text-white" href="{{ route('page.show', $page->slug) }}">{{ $page->name }}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    <div class="col-12 mt-4">
                        <div class="fw-bold text-uppercase mb-3">{{ __('Follow us') }}</div>
                        <ul class="list-inline">
                            @foreach (['facebook', 'twitter', 'instagram', 'pinterest', 'linkedin', 'youtube', 'vimeo'] as $social)
                                @if (get_system_setting($social . '_link'))
                                    <li class="list-inline-item">
                                        <a class="text-white fs-2" href="{{ get_system_setting($social . '_link') }}" target="_blank" title="{{ $social }}">
                                            <i class="fab fa-{{ $social }}"></i>
                                        </a>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if (!isset($hide_bottom_footer))
        <div class="py-4 bg-dark">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <p class="text-sm mb-md-0">&copy; {{ now()->format('Y') }}, {{ $application_name }}. {{ __('All rights reserved.') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif
</footer>
