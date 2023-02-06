<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class WorldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sql_file = public_path('world.sql');
        $db = [
            'host' => 'localhost',
            'database' => 'clanup_test',
            'username' => 'clanup_site',
            'password' => 'site123',
        ];

        exec("mysql --user={$db['username']} --password={$db['password']} --host={$db['host']} --database={$db['database']} < $sql_file");

    }
}
