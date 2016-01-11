<?php
namespace App;
use App\Aircraft;

class Shorthaulhelicopter extends Aircraft
{

    public function __construct ($attributes = array())
    {
        parent::__construct($attributes); // Call default constructor
        $this->statusable_type = "App\\Shorthaulhelicopter";
        $this->Distance = 100;  // This is the radius of the 'response ring' around a helicopter in nautical miles.  Default: 100;
        $this->LabelText= ".";  // This is a workaround to help ArcGIS server render the helicopter symbol in the center of its response circle. This should ALWAYS BE "."
    }

    
}
