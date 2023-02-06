<div class="mb-4">
    <label class="form-label" for="mailchimp_api_key">{{ __('Mailchimp API Key') }}</label>
    <input class="form-control" name="mailchimp_api_key" type="text" value="{{ $integration->getSetting('mailchimp_api_key', $group->id) }}" placeholder="{{ __('Mailchimp API Key') }}" required>
</div>

<div class="mb-4">
    <label class="form-label" for="mailchimp_list_id">{{ __('Mailchimp List ID') }}</label>
    <input class="form-control" name="mailchimp_list_id" type="text" value="{{ $integration->getSetting('mailchimp_list_id', $group->id) }}" placeholder="{{ __('Mailchimp List ID') }}" required>
</div>
