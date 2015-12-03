<?php
namespace App\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\User;

class UserTableSeeder extends Seeder
{

public function run()
{
    DB::table('users')->delete();
    User::create(array(
        'firstname'     => 'Evan',
        'lastname'     => 'Hsu',
        'email'    => 'evanhsu@gmail.com',
        'encrypted_password' => Hash::make('password'),
        'global_admin' => 1
    ));
}

}