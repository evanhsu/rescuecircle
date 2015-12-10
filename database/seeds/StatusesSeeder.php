<?php
namespace App\database\seeds;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Crew;
use App\Helicopter;
use App\User;
use App\Status;
use Carbon\Carbon;

class StatusesSeeder extends Seeder
{

public function run()
{
    DB::table('statuses')->delete();

    $heli = Helicopter::where("tailnumber","N1111")->first();
    $user = User::where("crew_id",$heli->crew_id)->first();
    $oldtime = Carbon::now()->subday(); // A timestamp from yesterday
    Status::create(array(
    	'statusable_type'   => "App\Helicopter",
        'statusable_id'     => $heli->id,
        'statusable_name'   => $heli->tailnumber,
        'latitude'          => 42.454223,
        'longitude'         => -123.310388,
        'staffing_value1'   => "3",
        'staffing_value2'   => "4",
        'manager_name'      => "Bob Nielson",
        'manager_phone'     => "789-566-4430",
        'comments1'         => "This is update 1 of 2 from the db seeder",
        'comments2'         => "This is upcoming",
        'assigned_fire_name'=> "Gasquet Complex",
        'assigned_fire_number'=>"WA-FRE-150038",
        'assigned_supervisor' =>"John Thompson",
        'assigned_supervisor_phone'=>"333-444-5555",
        'Distance'          => 100,
        'LabelText'         => ".",
        'created_by_name'   => $user->firstname." ".$user->lastname,
        'created_by_id'     => $user->id,
        'created_at'        => $oldtime,
        'updated_at'        => $oldtime
	));
    Status::create(array(
        'statusable_type'   => "App\Helicopter",
        'statusable_id'     => $heli->id,
        'statusable_name'   => $heli->tailnumber,
        'latitude'          => 42.464223,
        'longitude'         => -121.210388,
        'staffing_value1'   => "3",
        'staffing_value2'   => "4",
        'manager_name'      => "Bob Nielson",
        'manager_phone'     => "789-566-4430",
        'comments1'         => "This is update 2 of 2 from the db seeder",
        'comments2'         => "This is upcoming",
        'assigned_fire_name'=> "Gasquet Complex",
        'assigned_fire_number'=>"WA-FRE-150038",
        'assigned_supervisor' =>"John Thompson",
        'assigned_supervisor_phone'=>"333-444-5555",        
        'Distance'          => 100,
        'LabelText'         => ".",
        'created_by_name'   => $user->firstname." ".$user->lastname,
        'created_by_id'     => $user->id,
    ));




    $heli = Helicopter::where("tailnumber","N2222")->first();
    $user = User::where("crew_id",$heli->crew_id)->first();
    Status::create(array(
        'statusable_type'   => "App\Helicopter",
        'statusable_id'     => $heli->id,
        'statusable_name'   => $heli->tailnumber,
        'latitude'          => 44.084223,
        'longitude'         => -119.310388,
        'staffing_value1'   => "3",
        'staffing_value2'   => "4",
        'manager_name'      => "Jim Lewis",
        'manager_phone'     => "250-778-5443",
        'comments1'         => "This is update 1 of 1 from the db seeder",
        'comments2'         => "This is upcoming",
        'assigned_fire_name'=> "Big Windy",
        'assigned_fire_number'=>"OR-RSF-150208",
        'assigned_supervisor' =>"Bill Newman",
        'assigned_supervisor_phone'=>"333-444-5555",
        'Distance'          => 100,
        'LabelText'         => ".",
        'created_by_name'   => $user->firstname." ".$user->lastname,
        'created_by_id'     => $user->id,
    ));
    


    $heli = Helicopter::where("tailnumber","N4444")->first();
    $user = User::where("crew_id",$heli->crew_id)->first();
    Status::create(array(
        'statusable_type'   => "App\Helicopter",
        'statusable_id'     => $heli->id,
        'statusable_name'   => $heli->tailnumber,
        'latitude'          => 46.384223,
        'longitude'         => -115.310388,
        'staffing_value1'   => "3",
        'staffing_value2'   => "4",
        'manager_name'      => "Steve Borland",
        'manager_phone'     => "334-998-6756",
        'comments1'         => "This is update 1 of 1 from the db seeder",
        'comments2'         => "This is upcoming",
        'Distance'          => 100,
        'LabelText'         => ".",
        'created_by_name'   => $user->firstname." ".$user->lastname,
        'created_by_id'     => $user->id,
    ));

}//End run()

}