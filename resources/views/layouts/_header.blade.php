<header class="{{ !isset($fixed_header) && !isset($admin) ? 'sticky-top' : '' }} navbar navbar-expand-md navbar-light d-print-none"  @impersonating($guard = null) style="margin-top: 40px" @endImpersonating>
    <div class="{{ isset($admin) ? 'container' : 'container-fluid' }}">
        <div class="d-flex align-items-center">
            <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
                <a href="/">
                    <img src="{{ asset(get_system_setting('application_logo')) }}" alt="{{ $application_name }}" width="110" height="32" class="navbar-brand-image">
                </a>
            </h1>
            @if(!isset($admin))
                @livewire('find.search-component', 'header')
            @endisset
        </div>
        <div class="navbar-nav flex-row order-md-last">
            @if ($auth_user)
                <a class="nav-item fw-bold text-decoration-underline me-3" href="{{ route('start.index') }}">{{ __('Start a group') }}</a>
                <div class="dropdown nav-item me-3">
                    @php
                        $notifications = $auth_user->notifications()->orderBy('read_at', 'asc')->orderBy('created_at', 'desc')->paginate(10);
                        $unread_count = $auth_user->unreadNotifications->count();
                    @endphp
                    <button class="position-relative bell-icon bg-light" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-bell"></i>
                        @if ($unread_count)
                            <span class="position-absolute top-0 start-100 translate-middle p-2 bg-danger border border-light rounded-circle">
                                <span class="visually-hidden">{{ __('Unread notifications') }}</span>
                            </span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end notification-dropdown" aria-labelledby="notificationsDropdown">
                        <div class="scrolling-pagination">
                            @foreach ($notifications as $notification)
                                @include('application.components.notification-item', ['notification' => $notification])
                            @endforeach
    
                            <div class="d-none">
                                {{ $notifications->links() }}
                            </div>
                        </div>
                        @if (count($notifications) == 0)
                            <div class="text-center">
                                <p>{{ __('No notifications yet.') }}</p>
                            </div>
                        @endif
                    </ul>
                </div>
                <div class="nav-item dropdown border-start ps-3">
                    <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                        <span class="avatar avatar-sm border" style="background-image: url({{ $auth_user->avatar }})"></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                        @if ($auth_user->isAdmin())
                            <a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                <i class="fas fa-tachometer-alt me-2 text-muted"></i> {{ __('Admin Dashboard') }}
                            </a>
                            <div class="dropdown-divider"></div>
                        @endif
                        <a class="dropdown-item" href="{{ route('groups') }}">
                            <i class="fas fa-user-friends me-2 text-muted"></i> {{ __('My Groups', ['tab' => $auth_user->isOrganizerOfAnyGroup() ? '' : 'member']) }}
                        </a>
                        <a class="dropdown-item" href="{{ route('events') }}">
                            <i class="fas fa-calendar-alt me-2 text-muted"></i> {{ __('My Events') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('profile', $auth_user->username) }}">
                            <i class="fas fa-user-circle me-2 text-muted"></i> {{ __('Profile') }}
                        </a>
                        <a class="dropdown-item" href="{{ route('account.settings.general') }}">
                            <i class="fas fa-cog me-2 text-muted"></i>  {{ __('Settings') }}
                        </a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="{{ route('logout') }}">
                            <i class="fas fa-sign-out-alt me-2 text-muted"></i> {{ __('Logout') }}
                        </a>
                    </div>
                </div>
            @else
                <div class="nav-item">
                    <a class="btn btn-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                </div>
                <div class="nav-item">
                    <a class="btn btn-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                </div>
            @endif
        </div>
    </div>
</header>