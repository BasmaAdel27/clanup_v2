<?php

use App\Models\SystemSetting;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SeedNotificationSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('notification_types')->insert([
            'name' => 'App\\Notifications\\Event\\Announcement',
            'display_text' => 'New event announcements from the groups that you are member of',
            'status' => true,
        ]);

        DB::table('notification_types')->insert([
            'name' => 'App\\Notifications\\Event\\DateTimeChanged',
            'display_text' => 'Changes to event date or time',
            'status' => true,
        ]);

        DB::table('notification_types')->insert([
            'name' => 'App\\Notifications\\Event\\AddressChanged',
            'display_text' => 'Changes to event location',
            'status' => true,
        ]);

        DB::table('notification_types')->insert([
            'name' => 'App\\Notifications\\Event\\Reminder',
            'display_text' => 'Event reminders',
            'status' => true,
        ]);

        DB::table('notification_types')->insert([
            'name' => 'App\\Notifications\\Group\\Organizer\\CandidateRequested',
            'display_text' => 'Candidate requests to join your group',
            'status' => true,
        ]);

        DB::table('notification_types')->insert([
            'name' => 'App\\Notifications\\Group\\Organizer\\MemberJoined',
            'display_text' => 'Member joined to one of your groups that you have organize',
            'status' => false,
        ]);

        DB::table('notification_types')->insert([
            'name' => 'App\\Notifications\\Group\\Organizer\\MemberLeaved',
            'display_text' => 'Member leaved from one of your groups that you have organize',
            'status' => false,
        ]);

        // Update system version
        SystemSetting::setSetting('version', '1.0.2');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
