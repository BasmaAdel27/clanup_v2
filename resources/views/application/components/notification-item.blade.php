@php
    $image = null;
    $title = null;
    $link = null;
    $skip = false;

    switch ($notification->type) {
        case 'App\Notifications\Event\Reminder':
            $event = \App\Models\Event::find($notification->data['event_id'] ?? 0);
            if (!$event) {
                $skip = true;
                break;
            }
            $image = $event->image;
            $title = __('See you at: :event_title', ['event_title' => $event->title]);
            $link = route('groups.events.show', ['group' => $event->group->slug, 'event' => $event->uid]);
            break;
        case 'App\Notifications\Event\Announcement':
            $event = \App\Models\Event::find($notification->data['event_id'] ?? 0);
            if (!$event) {
                $skip = true;
                break;
            }
            $image = $event->image;
            $title = __('Upcoming event: :event_title', ['event_title' => $event->title]);
            $link = route('groups.events.show', ['group' => $event->group->slug, 'event' => $event->uid]);
            break;
        case 'App\Notifications\Event\DateTimeChanged':
            $event = \App\Models\Event::find($notification->data['event_id'] ?? 0);
            if (!$event) {
                $skip = true;
                break;
            }
            $image = $event->image;
            $title = __(':event_title date and time have been changed', ['event_title' => $event->title]);
            $link = route('groups.events.show', ['group' => $event->group->slug, 'event' => $event->uid]);
            break;
        case 'App\Notifications\Event\AddressChanged':
            $event = \App\Models\Event::find($notification->data['event_id'] ?? 0);
            if (!$event) {
                $skip = true;
                break;
            }
            $image = $event->image;
            $title = __(':event_title address have been changed', ['event_title' => $event->title]);
            $link = route('groups.events.show', ['group' => $event->group->slug, 'event' => $event->uid]);
            break;
        case 'App\Notifications\Group\Organizer\MemberJoined':
            $group = \App\Models\Group::find($notification->data['group_id'] ?? 0);
            if (!$group) {
                $skip = true;
                break;
            }
            $image = $group->avatar;
            $title = __('New member joined to :group_name', ['group_name' => $group->name]);
            $link = route('groups.members', ['group' => $group->slug]);
            break;
        case 'App\Notifications\Group\Organizer\MemberLeaved':
            $group = \App\Models\Group::find($notification->data['group_id'] ?? 0);
            if (!$group) {
                $skip = true;
                break;
            }
            $image = $group->avatar;
            $title = __('One member leaved :group_name', ['group_name' => $group->name]);
            $link = route('groups.members', ['group' => $group->slug]);
            break;
        case 'App\Notifications\Group\Organizer\CandidateRequested':
            $group = \App\Models\Group::find($notification->data['group_id'] ?? 0);
            if (!$group) {
                $skip = true;
                break;
            }
            $image = $group->avatar;
            $title = __('New request to join :group_name', ['group_name' => $group->name]);
            $link = route('groups.members', ['group' => $group->slug]);
            break;
        case 'App\Notifications\Group\ContentVisibilityChanged':
            $group = \App\Models\Group::find($notification->data['group_id'] ?? 0);
            if (!$group) {
                $skip = true;
                break;
            }
            $image = $group->avatar;
            $title = __(':group_name content visibility has been changed as closed.', ['group_name' => $group->name]);
            $link = route('groups.about', ['group' => $group->slug]);
            break;
        case 'App\Notifications\Group\MembershipChanged':
            $group = \App\Models\Group::find($notification->data['group_id'] ?? 0);
            if (!$group) {
                $skip = true;
                break;
            }
            $image = $group->avatar;
            $title = __('Your membership has been changed for :group_name', ['group_name' => $group->name]);
            $link = route('groups.about', ['group' => $group->slug]);
            break;
        case 'App\Notifications\User\SubscriptionCancelled':
            $image = $auth_user->avatar;
            $title = __('Your :app_name subscription cancelled', ['app_name' => get_system_setting('application_name')]);
            $link = route('account.settings.organizer');
            break;
    }
@endphp

@if (!$skip)
    <li class="border-bottom">
        <a class="dropdown-item" href="{{ $link }}">
            <div class="row gx-0 d-flex align-items-center">
                <div class="col-3">
                    <span class="avatar avatar-sm border" style="background-image: url({{ $image }})"></span>
                </div>
                <div class="col-9">
                    <p class="mb-0 text-truncate text-truncate-two-line">{{ $title }}</p>
                    <small class="mb-0 text-muted">{{ $notification->created_at->diffForHumans() }}</small>
                </div>
            </div>
        </a>
    </li>
@endif

