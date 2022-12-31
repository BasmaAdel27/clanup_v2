<div class="card p-3 mb-4">
    <div class="row">
        <div class="col-12 card-body">
            <div class="form-group mb-4 required">
                <label class="mb-1" for="name">{{ __('Name') }}</label>
                <input name="name" type="text" class="form-control" value="{{ $plan->name }}" required>
            </div>

            <div class="form-group mb-4">
                <label class="mb-1" for="description">{{ __('Description') }}</label>
                <input name="description" type="text" class="form-control" value="{{ $plan->description }}">
            </div>

            <div class="row mb-4">
                <div class="col-6">
                    <div class="form-group required">
                        <label class="mb-1" for="price">{{ __('Monthly Price') }}</label>
                        <input name="price" type="number" step="0.01" class="form-control" autocomplete="off" value="{{ $plan->price }}" required>
                        <small class="form-text text-muted">{{ __('Set this 0 if you want this plan to be free') }}</small>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group required">
                        <label class="mb-1" for="yearly_price">{{ __('Yearly Price') }}</label>
                        <input name="yearly_price" type="number" step="0.01" class="form-control" autocomplete="off" value="{{ optional($plan->yearly)->price }}" required>
                        <small class="form-text text-muted">{{ __('Set this 0 if you want this plan to be free') }}</small>
                    </div>
                </div>
            </div>

            <div class="form-group mb-4 required">
                <label class="mb-1" for="trial_period">{{ __('Trial Period') }}</label>
                <input name="trial_period" type="number" min="0" step="1" class="form-control" value="{{ $plan->trial_period ?? 0 }}" required>
                <small class="form-text text-muted">{{ __('Set this as 0 if you want to disable trial period') }}</small>
            </div>

            <div class="form-group mb-4 required">
                <label class="mb-1" for="order">{{ __('Order') }}</label>
                <input name="order" type="number" min="0" step="1" class="form-control" value="{{ $plan->order ?? 0 }}" required>
                <small class="form-text text-muted">{{ __('This is required for ordering plans (from left to right) at checkout page') }}</small>
            </div>
            <hr>
            
            <h5 class="mb-3">{{ __('Paypal') }}</h5>
            <div class="row">
                <div class="col-12 col-lg-6">
                    <div class="form-group mb-4">
                        <label class="mb-1" for="paypal_plan_id">{{ __('Paypal Plan ID (Monthly)') }}</label>
                        <input name="paypal_plan_id" type="text" class="form-control" placeholder="{{ __('Paypal Plan ID') }}" value="{{ $plan->paypal_plan_id }}">
                        <small class="form-text text-muted">{{ __('Please check the documentation to find how you can get Paypal Plan ID') }}</small>
                        <a href="https://support.varuscreative.com/help-center/articles/1/6/31/paypal-payment" target="_blank">{{ __('Click here') }}</a>
                    </div>
                </div>
                <div class="col-12 col-lg-6">
                    <div class="form-group mb-4">
                        <label class="mb-1" for="paypal_yearly_plan_id">{{ __('Paypal Plan ID (Yearly)') }}</label>
                        <input name="paypal_yearly_plan_id" type="text" class="form-control" placeholder="{{ __('Paypal Plan ID') }}" value="{{ optional($plan->yearly)->paypal_plan_id }}">
                        <small class="form-text text-muted">{{ __('Please check the documentation to find how you can get Paypal Plan ID') }}</small>
                        <a href="https://support.varuscreative.com/help-center/articles/1/6/31/paypal-payment" target="_blank">{{ __('Click here') }}</a>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group mb-4">
                        <label class="mb-1" for="paypal_webhook_url">{{ __('Paypal Webhook URL') }}</label>
                        <input type="text" class="form-control" value="{{ route('webhooks.paypal', ['token' => get_system_setting('paypal_webhook_token')]) }}" readonly>
                        <small class="form-text text-muted">{{ __('Please use this url for Webhook Notification') }}</small>
                    </div>
                </div>
            </div>
            <hr>
            
            <h5 class="mb-3">{{ __('Features') }}</h5>
            <div class="form-group mb-4 required">
                <label class="mb-1" for="features[groups]">{{ __('How many groups can subscriber create') }}</label>
                <input name="features[groups]" type="number" min="-1" step="1" class="form-control" placeholder="{{ __('Ex. 3') }}" value="{{ optional($plan->getFeatureBySlug('groups'))->value }}" required>
                <small class="form-text text-muted">{{ __('Set -1 to make it unlimited') }}</small>
            </div>

            <div class="form-group mb-4">
                <label class="mb-1" for="features[can_access_communication_tools]">{{ __('Can use communication tools') }}</label>
                <div class="form-check form-switch">
                    <input type="hidden" name="features[can_access_communication_tools]" value="0">
                    <input class="form-check-input" name="features[can_access_communication_tools]" id="communicationTools" type="checkbox" value="1" {{ optional($plan->getFeatureBySlug('can_access_communication_tools'))->value ? 'checked=""' : '' }}>
                    <label class="form-check-label text-white" for="communicationTools">{{ __('Yes') }}</label>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="mb-1" for="features[can_access_email_addresses]">{{ __('Can access the email addresses of attendees') }}</label>
                <div class="form-check form-switch">
                    <input type="hidden" name="features[can_access_email_addresses]" value="0">
                    <input class="form-check-input" name="features[can_access_email_addresses]" id="canAccessEmailAddress" type="checkbox" value="1" {{ optional($plan->getFeatureBySlug('can_access_email_addresses'))->value ? 'checked=""' : '' }}>
                    <label class="form-check-label text-white" for="canAccessEmailAddress">{{ __('Yes') }}</label>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="mb-1" for="features[can_access_custom_reports]">{{ __('Can access the custom reports') }}</label>
                <div class="form-check form-switch">
                    <input type="hidden" name="features[can_access_custom_reports]" value="0">
                    <input class="form-check-input" name="features[can_access_custom_reports]" id="canAccessCustomReports" type="checkbox" value="1" {{ optional($plan->getFeatureBySlug('can_access_custom_reports'))->value ? 'checked=""' : '' }}>
                    <label class="form-check-label text-white" for="canAccessCustomReports">{{ __('Yes') }}</label>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="mb-1" for="features[can_display_sponsors]">{{ __('Can display sponsors on event pages') }}</label>
                <div class="form-check form-switch">
                    <input type="hidden" name="features[can_display_sponsors]" value="0">
                    <input class="form-check-input" name="features[can_display_sponsors]" id="canDisplaySponsors" type="checkbox" value="1" {{ optional($plan->getFeatureBySlug('can_display_sponsors'))->value ? 'checked=""' : '' }}>
                    <label class="form-check-label text-white" for="canDisplaySponsors">{{ __('Yes') }}</label>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="mb-1" for="features[max_sponsors_count]">{{ __('Maximum sponsors count') }}</label>
                <div class="form-group">
                    <input name="features[max_sponsors_count]" type="number" min="-1" step="1" class="form-control" placeholder="{{ __('Ex. 6') }}" value="{{ optional($plan->getFeatureBySlug('max_sponsors_count'))->value ?? 0 }}" required>
                    <small class="form-text text-muted">{{ __('Set -1 to make it unlimited') }}</small>
                </div>
            </div>

            <div class="text-end">
                @if ($plan->id)
                    <a class="btn btn-outline-danger delete-confirm" href="{{ route('admin.plans.delete', $plan->id) }}" data-title="{{ __('Are you sure?') }}" data-text="{{ __('This record will be deleted.') }}">{{ __('Delete plan') }}</a>
                @endif
                <button type="submit" class="btn btn-primary">{{ __('Save plan') }}</button>
            </div>
        </div>
    </div>
</div>
