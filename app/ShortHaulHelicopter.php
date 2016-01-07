<?php
namespace App;
use App\Aircraft;

class Shorthaulhelicopter extends Aircraft
{

    public function __construct ($attributes = array())
    {
        parent::__construct($attributes); // Call default constructor
        $this->statusable_type = "App\\Shorthaulhelicopter";
    }

    
}
