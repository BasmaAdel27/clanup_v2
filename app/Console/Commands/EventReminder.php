<?php

namespace App\Console\Commands;

use App\Models\Event;
use Illuminate\Console\Command;

class EventReminder extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'event:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send event reminders to attendees';

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
     * @return void
     */
    public function handle()
    {
        // Fetch upcoming events & send reminders
        $after_n_days = now()->addDays(config('app.event_reminder_add_days'))->format('Y-m-d');
        $events = Event::startsAt($after_n_days)->notCancelled()->get();
        foreach ($events as $event) {
            $event->sendReminderToAttendees();
        }

        // Fetch today's events & send reminders
        $events = Event::startsAt(now()->format('Y-m-d'))->notCancelled()->get();
        foreach ($events as $event) {
            $event->sendReminderToAttendees();
        }
    }
}