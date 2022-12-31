<?php

namespace App\Console\Commands;

use App\Helpers\Installer\DatabaseManager;
use App\Models\Plan;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ResetDemo extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reset:demo';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        if (config('app.is_demo')) {
            // Purge database
            $this->purge_db();

            // Run default migrations & seeds
            $database_manager = new DatabaseManager();
            $database_manager->migrateDatabase();

            // Create 3 plans, features
            $this->create_plans();
            $plan = Plan::where('id', 1)->first();

            // Create Admin
            factory(\App\Models\User::class, 1)->create([
                'email' => 'admin@example.com',
                'role' => 'admin'
            ]);

            // Create Organizer
            factory(\App\Models\User::class, 1)->create([
                'email' => 'organizer@example.com'
            ]);
            $user = User::where('email', 'organizer@example.com')->first();
            $user->createOrRenewSubscribtion($plan);
            factory(\App\Models\Group::class, 3)->create(['created_by' => $user->id]);

            // Create Attendee
            factory(\App\Models\User::class, 1)->create([
                'email' => 'attendee@example.com'
            ]);
            
            // Create 100 random user
            factory(\App\Models\User::class, 100)->create();

            // Create 100 random groups - that includes membership, discussions, events
            factory(\App\Models\Group::class, 100)->create();

            // Create 10 blog category with blog each
            factory(\App\Models\BlogCategory::class, 10)->create();
        }
    }

    private function create_plans()
    {
        $basic_plan = Plan::create(['slug' => 'basic','name' => 'Basic Plan','description' => 'This is basic plan','is_active' => true,'price' => 4.9900,'invoice_period' => 1,'invoice_interval' => 'month','trial_period' => 7,'trial_interval' => 'day','order' => 0]);
        $basic_plan->addPlanFeatures(['groups' => 1,'can_access_communication_tools' => false,'can_access_email_addresses' => false,'can_access_custom_reports' => false,'can_display_sponsors' => false,'max_sponsors_count' => 0,]);
        $basic_plan_yearly = Plan::create(['slug' => 'basic-yearly','name' => 'Basic Plan','description' => 'This is basic plan','is_active' => true,'price' => 49.9900,'invoice_period' => 1,'invoice_interval' => 'year','trial_period' => 7,'trial_interval' => 'day','order' => 0]);
        $basic_plan_yearly->addPlanFeatures(['groups' => 1,'can_access_communication_tools' => false,'can_access_email_addresses' => false,'can_access_custom_reports' => false,'can_display_sponsors' => false,'max_sponsors_count' => 0,]);

        $startup_plan = Plan::create(['slug' => 'startup','name' => 'Startup Plan','description' => 'This is startup plan','is_active' => true,'price' => 9.9900,'invoice_period' => 1,'invoice_interval' => 'month','trial_period' => 7,'trial_interval' => 'day','order' => 0]);
        $startup_plan->addPlanFeatures(['groups' => 1,'can_access_communication_tools' => false,'can_access_email_addresses' => false,'can_access_custom_reports' => false,'can_display_sponsors' => false,'max_sponsors_count' => 0,]);
        $startup_plan_yearly = Plan::create(['slug' => 'startup-yearly','name' => 'Startup Plan','description' => 'This is startup plan','is_active' => true,'price' => 99.9900,'invoice_period' => 1,'invoice_interval' => 'year','trial_period' => 7,'trial_interval' => 'day','order' => 0]);
        $startup_plan_yearly->addPlanFeatures(['groups' => 1,'can_access_communication_tools' => true,'can_access_email_addresses' => true,'can_access_custom_reports' => false,'can_display_sponsors' => false,'max_sponsors_count' => 0,]);

        $growth_plan = Plan::create(['slug' => 'growth','name' => 'Growth Plan','description' => 'This is growth plan','is_active' => true,'price' => 19.9900,'invoice_period' => 1,'invoice_interval' => 'month','trial_period' => 7,'trial_interval' => 'day','order' => 0]);
        $growth_plan->addPlanFeatures(['groups' => 1,'can_access_communication_tools' => false,'can_access_email_addresses' => false,'can_access_custom_reports' => false,'can_display_sponsors' => false,'max_sponsors_count' => 0,]);
        $growth_plan_yearly = Plan::create(['slug' => 'growth-yearly','name' => 'Growth Plan','description' => 'This is growth plan','is_active' => true,'price' => 199.9900,'invoice_period' => 1,'invoice_interval' => 'year','trial_period' => 7,'trial_interval' => 'day','order' => 0]);
        $growth_plan_yearly->addPlanFeatures(['groups' => 1,'can_access_communication_tools' => true,'can_access_email_addresses' => true,'can_access_custom_reports' => true,'can_display_sponsors' => true,'max_sponsors_count' => 6]);
    }

    private function purge_db()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0;');
        $tables = DB::select('SHOW TABLES');
        foreach($tables as $table){
            $table = implode(json_decode(json_encode($table), true));
            Schema::drop($table);
        }
        DB::statement('SET FOREIGN_KEY_CHECKS = 1;');
    }
}