<?php

namespace App\Services\Integrations\Mailchimp;

use App\Models\Integration;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class Index
{
    public function update_settings(Request $request, $group)
    {

        // Validate request
        $request->validate([
            'mailchimp_api_key' => 'required|string',
            'mailchimp_list_id' => 'required|string',
        ]);

        // Check if the credentials are correct
        try {
            $mailchimp = new MailChimp($request->input('mailchimp_api_key'));
            $result = $mailchimp->get("/lists/$request->mailchimp_list_id");
            if (!isset($result['name'])) {
                throw ValidationException::withMessages(['mailchimp_api_key' => __('Please check your credentials')]);
            }
        } catch (\Throwable $th) {
            throw ValidationException::withMessages(['mailchimp_api_key' => __('Please check your credentials')]);
        }

        // Update group settings
        $integration = Integration::where('slug', 'mailchimp')->first();
        $integration->setSetting('mailchimp_api_key', $request->input('mailchimp_api_key'), $group->id);
        $integration->setSetting('mailchimp_list_id', $request->input('mailchimp_list_id'), $group->id);

        // Check if the group is already synced with Mailchimp
        // If not sync current members with mailchimp
        $is_already_sync = $integration->getSetting('mailchimp_synced', $group->id);

        if (!$is_already_sync) {
            $result = $this->sync_members($group);
            if ($result) {
                $integration->setSetting('mailchimp_synced', 1, $group->id);
            }
        }
    }

    protected function sync_members($group)
    {
        $integration = Integration::where('slug', 'mailchimp')->first();
        $mailchimp_api_key = $integration->getSetting('mailchimp_api_key', $group->id);
        $mailchimp_list_id = $integration->getSetting('mailchimp_list_id', $group->id);

        $mailchimp = new MailChimp($mailchimp_api_key);
        $batch = $mailchimp->new_batch();
        foreach ($group->members as $member) {
            $subscriber_hash = md5(strtolower($member->email));
            $batch->put("$member->uid", "lists/$mailchimp_list_id/members/$subscriber_hash", [
				"email_address" => "$member->email",
                "status_if_new" => "subscribed",
                "status" => "subscribed",
                "merge_fields" => [
                    "FNAME" => "$member->first_name",
                    "LNAME" => "$member->last_name"
                ]
			]);
        }

        $result = $batch->execute();

        return isset($result['status']) && $result['status'] == 'pending';
    }
}
