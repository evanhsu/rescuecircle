<?php
namespace App\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Crew;
use App\Helicopter;

class HelicoptersSeeder extends Seeder
{

public function run()
{
    DB::table('helicopters')->delete();

    $crew = Crew::where("name","Grand Canyon Short Haul Crew")->first();
    Helicopter::create(array(
    	'tailnumber'  => 'N1111',
		'model'       => 'Astar B350',
		'crew_id'     => $crew->id
	));
    Helicopter::create(array(
        'tailnumber'  => 'N2222',
        'model'       => 'MD-900',
        'crew_id'     => $crew->id
    ));


    $crew = Crew::where("name","Price Valley")->first();
    Helicopter::create(array(
        'tailnumber'  => 'N3333',
        'model'       => 'Bell 407',
        'crew_id'     => $crew->id
    ));


    //This helicopter is not assigned to a Crew
    Helicopter::create(array(
        'tailnumber'  => 'N4444',
        'model'       => 'Bell 205',
        'crew_id'     => $crew->id
    ));

}//End run()

}