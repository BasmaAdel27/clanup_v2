<a class="card card-link mb-3" href="{{ route('groups.settings.integrations.details', ['group' => $group->slug, 'integration' => $integration->slug]) }}">
    <div class="card-body">
        <div class="row align-items-center">
            <div class="col-auto">
                <span class="avatar rounded border" style="background-image: url({{ asset('assets/images/mailchimp.png') }})"></span>
            </div>
            <div class="col">
                <div class="font-weight-medium">{{ $integration->name }}</div>
                <div class="text-muted">{{ $integration->description }}</div>
            </div>
        </div>
    </div>
</a>