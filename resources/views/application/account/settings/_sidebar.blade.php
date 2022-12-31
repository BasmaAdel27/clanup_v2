<div class="col-lg-3 mb-4">
    <div class="card card-fluid">
        <div class="card-header">
            <div class="card-title">{{ __('Settings') }}</div>
        </div>
        <nav class="nav side-nav flex-row flex-nowrap flex-lg-column flex-lg-wrap">
            <a class="nav-link text-nowrap @if($page == 'general') text-primary fw-bold @endif" href="{{ route('account.settings.general') }}">
                {{ __('General') }}
            </a>
            <a class="nav-link text-nowrap @if($page == 'interests') text-primary fw-bold @endif" href="{{ route('account.settings.interests') }}">
                {{ __('Interests') }}
            </a>
            <a class="nav-link text-nowrap @if($page == 'notification_settings') text-primary fw-bold @endif" href="{{ route('account.settings.notifications') }}">
                {{ __('Notification Settings') }}
            </a>
            <a class="nav-link text-nowrap @if($page == 'privacy') text-primary fw-bold @endif" href="{{ route('account.settings.privacy') }}">
                {{ __('Privacy Settings') }}
            </a>
            <a class="nav-link text-nowrap @if($page == 'organizer') text-primary fw-bold @endif" href="{{ route('account.settings.organizer') }}">
                {{ __('Organizer') }}
            </a>
        </nav>
    </div>
</div>