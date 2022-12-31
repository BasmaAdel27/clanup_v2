<?php
 
namespace App\Services\Integrations\Mailchimp\Listeners;

use App\Models\GroupMembership;
use App\Models\Integration;
use App\Services\Integrations\Mailchimp\Mailchimp;

class MembershipChangedListener
{
    /**
     * Handle the event.
     *
     * @param  $event
     * @return void
     */
    public function handle($event)
    {
        // Check the new membership is below member, then unsubscribe user from Mailchimp
        if ($event->membership->membership < GroupMembership::MEMBER) {
            $status = 'unsubscribed';
        } else {
            $status = 'subscribed';
        }

        $integration = Integration::where('slug', 'mailchimp')->first();
        $mailchimp_api_key = $integration->getSetting('mailchimp_api_key', $event->membership->group->id);
        $mailchimp_list_id = $integration->getSetting('mailchimp_list_id', $event->membership->group->id);
        try {
            $mailchimp = new MailChimp($mailchimp_api_key);
            $batch = $mailchimp->new_batch();
            $subscriber_hash = md5(strtolower($event->membership->user->email));
            $member = $event->membership->user;
            $batch->put("$member->uid", "lists/$mailchimp_list_id/members/$subscriber_hash", [
                "email_address" => "$member->email",
                "status_if_new" => $status,
                "status" => $status,
                "merge_fields" => [
                    "FNAME" => "$member->first_name",
                    "LNAME" => "$member->last_name"
                ]
            ]);
            $result = $batch->execute();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}