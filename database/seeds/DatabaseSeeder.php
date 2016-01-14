<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\database\seeds\CrewsWithAdminUsersSeeder;
use App\database\seeds\AircraftSeeder;
use App\database\seeds\StatusesSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        $this->call(CrewsWithAdminUsersSeeder::class);
        //$this->call(UserTableSeeder::class);
        $this->call(AircraftSeeder::class);
        $this->call(StatusesSeeder::class);

        Model::reguard();
    }
}
?>