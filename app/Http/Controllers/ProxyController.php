<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

// Provided by ESRI, modified to work with Laravel MVC architecture. Contains multiple Class definitions.
require(dirname(__FILE__).'/../../Proxy.php');
// Settings are defined in app/proxy.config


class ProxyController extends Controller
{
    public function __construct()
    {
        // Nothing
    }

    public function proxy(Request $request) {
        

        //$proxyDataValid = new \App\DataValidUtil();

        $proxyConfig = new \App\ProxyConfig();
        $proxyConfig->useJSON();  
        // $proxyConfig->useXML();

        $proxyLog = new \App\ProxyLog($proxyConfig);

        $proxyObject = new \App\Proxy($proxyConfig, $proxyLog);
        $proxyObject->getResponse();

        return;
    }

    public function proxyVerify() {
        require(dirname(__FILE__).'/../../proxy-verification.php');
    }
}// END class()
