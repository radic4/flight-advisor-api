<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::factory(['title' => 'administrator'])->create();
        $user = User::factory(['username' => 'administrator', 'role_id' => 1])->create();
        Role::factory(['title' => 'regular'])->create();

        $this->call(Cities::class);
    }
}
