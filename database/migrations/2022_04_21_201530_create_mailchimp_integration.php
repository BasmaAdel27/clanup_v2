<?php

use App\Models\Integration;
use Illuminate\Database\Migrations\Migration;

class CreateMailchimpIntegration extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create Mailchimp Integration
        Integration::create([
            'slug' => 'mailchimp',
            'name' => 'Mailchimp',
            'description' => 'Mailchimp integration',
            'available_as_plan_feature' => true,
            'is_active' => true,
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Integration::where('slug', 'mailchimp')->delete();
    }
}
