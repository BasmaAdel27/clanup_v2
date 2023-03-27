<div class="col-12">
    <a class="text-primary mb-0" href="{{ route('groups.about', ['group' => $group->slug,'x'=>$group->id]) }}">< {{ __('Back to group') }} </a>
    <h2 class="h1 mb-4">{{ $group->name }}</h2>
</div>

<div class="col-lg-3 mb-4">
    <div class="card card-fluid">
        <div class="card-header">
            <div class="card-title">{{ __('Settings') }}</div>
        </div>
        <nav class="nav side-nav flex-row flex-nowrap flex-lg-column flex-lg-wrap">
            <a class="nav-link text-nowrap @if($page == 'basic') text-primary fw-bold @endif" href="{{ route('groups.settings', ['group' => $group->slug]) }}">
                {{ __('Basic Settings') }}
            </a>
            <a class="nav-link text-nowrap @if($page == 'members') text-primary fw-bold @endif" href="{{ route('groups.settings.members', ['group' => $group->slug]) }}">
                {{ __('New member settings') }}
            </a>
            <a class="nav-link text-nowrap @if($page == 'addMembers') text-primary fw-bold @endif" href="{{ route('groups.settings.addMembers', ['group' => $group->slug]) }}">
                {{ __('Add members') }}
            </a>
            <a class="nav-link text-nowrap @if($page == 'topics') text-primary fw-bold @endif" href="{{ route('groups.settings.topics', ['group' => $group->slug]) }}">
                {{ __('Topics') }}
            </a>
            <a class="nav-link text-nowrap @if($page == 'content_visibility') text-primary fw-bold @endif" href="{{ route('groups.settings.content_visibility', ['group' => $group->slug]) }}">
                {{ __('Content Visibility') }}
            </a>
            <a class="nav-link text-nowrap @if($page == 'optional') text-primary fw-bold @endif" href="{{ route('groups.settings.optional', ['group' => $group->slug]) }}">
                {{ __('Optional Settings') }}
            </a>
            <a class="nav-link text-nowrap @if($page == 'integrations') text-primary fw-bold @endif" href="{{ route('groups.settings.integrations', ['group' => $group->slug]) }}">
                {{ __('Integrations') }}
            </a>
            @can('store_sponsor', $group)
                <a class="nav-link text-nowrap @if($page == 'sponsors') text-primary fw-bold @endif" href="{{ route('groups.settings.sponsors', ['group' => $group->slug]) }}">
                    {{ __('Sponsors') }}
                </a>
            @endcan
        </nav>
    </div>
</div>
