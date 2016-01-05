<?php
namespace App;
use App\Aircraft;

class ShortHaulHelicopter extends Aircraft
{
    public function __construct ($attributes = array())
    {
        parent::__construct($attributes); // Calls Default Constructor
        $this->statusable_type = "App\\ShortHaulHelicopter";
    }
}
