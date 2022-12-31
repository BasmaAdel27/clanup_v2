<?php

/*
|--------------------------------------------------------------------------
| Module Configurations
|--------------------------------------------------------------------------
*/
return [
    'name' => 'Mailchimp',
    'providers' => [
        \App\Services\Integrations\Mailchimp\Provider\EventServiceProvider::class
    ],
];