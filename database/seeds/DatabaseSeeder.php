<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\database\seeds\UserTableSeeder;

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

        $this->call(CrewsTableSeeder::class);
        $this->call(UserTableSeeder::class);
        $this->call(HelicoptersTableSeeder::class);
        $this->call(StatusesTableSeeder::class);

        Model::reguard();
    }
}
?>