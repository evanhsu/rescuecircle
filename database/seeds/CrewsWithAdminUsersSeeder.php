<?php
namespace App\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Crew;
use App\User;

class CrewsWithAdminUsersSeeder extends Seeder
{

public function run()
{
    DB::table('crews')->delete();
    DB::table('users')->delete();

    User::create(array(
        'firstname'         => 'Evan',
        'lastname'          => 'Hsu',
        'email'             => 'evanhsu@gmail.com',
        'encrypted_password'=> Hash::make('password'),
        'global_admin'      => 1
    ));

    $crew = Crew::create(array(
        'name'              => 'Grand Canyon Short Haul Crew',
        'address_street1'   => '3435 North Rim Dr.',
        'address_city'      => 'Canyon City',
        'address_state'     => 'AZ',
        'address_zip'       => '77890',
        'phone'             => '330-404-5050',
        'statusable_type'   => 'helicopter'
    ));
    User::create(array(
    	'firstname'               => 'Grant',
		'lastname'                => 'Kenyon',
		'email'                   => 'evanhsu@grandcanyon.com',
		'encrypted_password'      => Hash::make('password'),
		'crew_id'                 => $crew->id,
		'global_admin'            => 0
	));

    $crew = Crew::create(array(
        'name'              => 'Price Valley',
        'address_street1'   => '774 Valley Dr.',
        'address_city'      => 'Pricetown',
        'address_state'     => 'ID',
        'address_zip'       => '54678',
        'phone'             => '280-324-2909',
        'statusable_type'   => 'helicopter'
    ));
    User::create(array(
    	'firstname'           => 'Pete',
		'lastname'            => 'Valles',
		'email'               => 'evanhsu@pricevalley.com',
		'encrypted_password'  => Hash::make('password'),
		'crew_id'             => $crew->id,
		'global_admin'        => 0
	));

    $crew = Crew::create(array(
        'name'              => 'Prineville Hotshots',
        'address_street1'   => '995 Lamonta Rd.',
        'address_city'      => 'Prineville',
        'address_state'     => 'OR',
        'address_zip'       => '97754',
        'phone'             => '541-887-5477',
        'statusable_type'   => 'crew'
    ));
    User::create(array(
    	'firstname'	=> 'Prine',
		'lastname'	=> 'Vill',
		'email'		=> 'evanhsu@prineville.com',
		'encrypted_password'	=> Hash::make('password'),
		'crew_id' => $crew->id,
		'global_admin'	=>	0
	));
}

}