<?php

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'first_name' => 'clanup',
            'last_name' => 'Admin',
            'email'  => 'hookshamosiba201555@gmail.com',
            'password' => Hash::make("password"),
            'role' => 'admin',
        ]);
    }
}
