<?php
namespace App;
use App\Status;
use Log;


class ArcServer {
	private static $base_url = "https://egp.nwcg.gov/arcgis/rest/services/FireCOP/ShortHaul/FeatureServer";
	// private static $token_for_evanhsu = "ZbIpxHzxRH6xaRqUClS2ZHQodQfSgQnc1BbrWk2d6Wo7bULVhO4A6HvP5zsWQqBI"; // Expires 1/8/2016
	private static $token_for_evanhsu = "S1R9f4LzYbjxAxAXY96fQeEiFTfC6jCnzmHSC8LKStyZwjr7gnwpvB75PNzD4xC9"; // Expires 1/8/2016


	public static function featureExists($status) {
		// Check the ArcGIS server for an existing Feature Object that matches this tailnumber (or Crew ID)
		// Returns the ArcGIS Server OBJECT_ID if found.
		// Returns NULL if no matching features are found.
		// Returns FALSE if an error occurs (including errors returned by the ArcGIS server).

		$type = explode("\\",$status->statusable_type)[1]; // App\Helicopter ==> Helicopter

		// Choose which layer to query on the Feature Server (i.e. Helicopters are on Layer 0, Crews are on Layer 1, etc.)
		switch($type) {
			case "Helicopter":
			default:
				$layer = 0;
				break;
		}

		$params = array();
		// $params['layerDefs']= "{\"0\":\"statusable_type like '%$type' AND statusable_name='".$status->statusable_name."'\"}";
		$params['layerDefs']= "{\"$layer\":\"statusable_name='".$status->statusable_name."'\"}";
		$params['token']	= self::$token_for_evanhsu;
		$params['returnIdsOnly']	= 'false';
		$params['f']		= 'json';

		$url = self::$base_url."/query";

		$response = self::callAPI("GET",$url,$params);
		$json_response = json_decode($response,true);
		
		
		var_dump($response);
		echo "<br /><br />\n\n";
		var_dump($json_response);
		echo "<br /><br />\n\n";
		var_dump($json_response['layers'][0]);
		

		if(isset($json_response["layers"]) && isset($json_response["layers"][$layer])) {
			if(isset($json_response['layers'][$layer]['objectIds'])) {
				$object_ids = $json_response["layers"][$layer]["objectIds"];
			}
			else $object_ids = null;
		}
		elseif(array_key_exists("error",$json_response)) {
			Log::error('ArcGIS server error: '.$json_response['error']['message'],["File"=>__FILE__,"Method"=>__METHOD__,"URL"=>$url, "URL_params"=>$params, "ArcGIS Error Code"=>$json_response['error']['code']]);
			$object_ids = false;
		}
		return $object_ids;
	}

	public static function addFeature($status) {
		// Add a new Feature to the ArcGIS server using the the AddFeatures REST endpoint
		// (docs: https://egp.nwcg.gov/arcgis/sdk/rest/index.html#/Add_Features/02ss0000009m000000/)
		//

		// Choose which layer to use on the Feature Server (i.e. Helicopters are on Layer 0, Crews are on Layer 1, etc.)
		switch($type) {
			case "Helicopter":
			default:
				$layer = 0;
				break;
		}
		$url = self::$base_url."/$layer/addFeatures";
		

		$attributes = $status->to_json(); // Convert this Status object into a JSON string

		$geometry = array("x"=>230857,"y"=>-293768529738);
		$attributes =  array();

/*
		features = [
						0 =>[
								geometry =>	[
												x => 92738654798623,
												y => -2973868709549
											],
								attributes=>[
												OBJECTID => 1,
												id 		 => 23,
												statusabel_name	=> "App\Helicopter"
											]
							]
					]
*/
		$params = array();
		$params['token']		= self::$token_for_evanhsu;
		$params['features'][]	= "{ 
									\"geometry\"	: $geometry, 
									\"attributes\"	: $attributes
									}";

		$response = callAPI("POST", $url, $params);

		// Check for errors, process response
		var_dump($response);
		return $response;
	}

	// Method: POST, PUT, GET etc
	// Url: Should contain the portion of the URL prior to the ? (do NOT include the ? for urls with a query string)
	// Data: array("param" => "value") ==> index.php?param=value
	//
	// Return the entire response (without headers) on success
	// Return FALSE on failure
	public static function callAPI($method, $url, $data = false)
	{
	    $curl = curl_init();

	    switch ($method)
	    {
	        case "POST":
	            curl_setopt($curl, CURLOPT_POST, 1);

	            if ($data)
	                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
	            break;
	        case "PUT":
	            curl_setopt($curl, CURLOPT_PUT, 1);
	            break;
	        default:
	            // if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
		        if($data) {
			        $q = "";
		        	foreach($data as $key=>$val) {
		        		$q .= "$key=$val&";
		        	}
		        }
	            if ($data) $url = sprintf("%s?%s", $url, $q);
	    }

	    curl_setopt($curl, CURLOPT_URL, $url);
	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); // Use IPv4 (not IPv6)

		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_HTTPGET, 1);

	    $result = curl_exec($curl);
		if (curl_errno($curl)) {
		    $result = false;
		    Log::error('cURL Error: '.curl_error($curl),["File"=>__FILE__,"Method"=>__METHOD__,"URL"=>$url]);
		}
	    curl_close($curl);

	   // echo $url;

	   return $result;
	}
}