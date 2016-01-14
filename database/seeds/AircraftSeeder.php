<?php
namespace App\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Crew;
use App\Shorthaulhelicopter;
use App\Rappelhelicopter;
use App\Smokejumperairplane;

class AircraftSeeder extends Seeder
{

public function run()
{
    DB::table('aircrafts')->delete();

    $crew = Crew::where("name","Grand Canyon Short Haul Crew")->first();
    Shorthaulhelicopter::create(array(
    	'tailnumber'  => 'N1111',
		'model'       => 'Astar B350',
		'crew_id'     => $crew->id
	));
    Shorthaulhelicopter::create(array(
        'tailnumber'  => 'N2222',
        'model'       => 'MD-900',
        'crew_id'     => $crew->id
    ));


    $crew = Crew::where("name","Price Valley")->first();
    Rappelhelicopter::create(array(
        'tailnumber'  => 'N3333',
        'model'       => 'Bell 407',
        'crew_id'     => $crew->id
    ));


    $crew = Crew::where("name","Redding Smokejumpers")->first();
    Smokejumperairplane::create(array(
        'tailnumber'  => 'J-89',
        'model'       => 'C-130',
        'crew_id'     => $crew->id
    ));
    Smokejumperairplane::create(array(
        'tailnumber'  => 'J-83',
        'model'       => 'DHC-6',
        'crew_id'     => $crew->id
    ));


    //This helicopter is not assigned to a Crew
    Rappelhelicopter::create(array(
        'tailnumber'  => 'N4444',
        'model'       => 'Bell 205',
        'crew_id'     => null
    ));

}//End run()

}