<?php
namespace App;
use App\Status;
use Log;


class ArcServer {
	private static $base_url = "https://egp.nwcg.gov/arcgis/rest/services/FireCOP/ShortHaul/FeatureServer";
	private static $token_for_evanhsu = array(	"token"		=> "MkWG8SSDzY3KwP7Becy_Muv5Mu3LeDWC7pEo93Ra8jsb5oGzYkyj-iaoe1Z-Ku2WdoFAl6e0YH9e1YqF4a3KRXzm1AnalAZea58vc1UCUQU.",
												"expires"	=> 1449914879082); // evanhsu, referer: http://resourcestatus.smirksoftware.com Expires 12/18/2016



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
		$params['layerDefs']= "{\"".$layer."\":\"statusable_name='".$status->statusable_name."'\"}";
		$params['token']	= self::$token_for_evanhsu['token'];
		$params['returnIdsOnly']	= 'false';
		$params['f']		= 'json';

		$url = self::$base_url."/query";

		$response = self::callAPI("GET",$url,$params);
		$json_response = json_decode($response,true);
		
		
		var_dump($response);
		// echo "<br /><br />\n\n";
		// var_dump($json_response);
		// echo "<br /><br />\n\n";
		// var_dump($json_response['layers'][$layer]);
		
		// Check the server response for success or failure
		if(isset($json_response["layers"]) && isset($json_response["layers"][$layer])) {
			if(isset($json_response['layers'][$layer]['objectIds'])) {
				// The server found some matches
				$object_ids = $json_response["layers"][$layer]["objectIds"];
			}
			else {
				// The server did NOT find any matching features, but there were no errors
				$object_ids = null;
			}
		}
		elseif(array_key_exists("error",$json_response)) {
			// The ArcGIS server returned an error code - log it and return FALSE
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
		$type = explode("\\",$status->statusable_type)[1]; // App\Helicopter ==> Helicopter
		switch($type) {
			case "Helicopter":
			default:
				$layer = 0;
				break;
		}

		// Construct the URL to send the POST request to.
		$url = self::$base_url."/$layer/addFeatures";

		// Build a JSON representation of the Status update that we're going to send
		$attributes = $status->toArray(); // Convert this Status object into a JSON string

		// 'geometry' is required by the ArcGIS server to actually plot the point on the map.
		// (docs: https://egp.nwcg.gov/arcgis/sdk/rest/index.html#/Geometry_Objects/02ss0000008m000000/)
		// $geometry = array("x"=>230857,"y"=>-293768529738);
		// wkid: 4326 (GCS_WGS_1984) - geographic coordinate system (lat/lon)
		// wkid: 3857 (WGS_1984_Web_Mercator_Auxiliary_Sphere) - Projected coordinate system (x/y projected onto flat globe)
		$geometry = array(	
							"x"					=>floatval($status->longitude),
							"y"					=>floatval($status->latitude),
							"spatialReference"	=> array(
															"wkid"		=> 4326
														)
						);

		$params = array();
		$params['token']	= self::$token_for_evanhsu['token'];
		$params['features'] = json_encode(array(array("geometry" => $geometry, "attributes" => $attributes)));

		$response = self::callAPI("POST", $url, $params);

		// Check for errors, process response
		//var_dump($response);
		
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

	            if ($data) {
	            	// $data is an array of key=>value pairs
	            	$params = "";
	            	foreach($data as $key=>$val) {
	            		if($params != "") $params .= "&";
	            		$params .= $key."=".$val;
	            	}
	                curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
	            }
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
	    curl_setopt($curl, CURLOPT_REFERER, 'http://resourcestatus.smirksoftware.com'); //Needed to accompany the token for authentication

	    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); // Use IPv4 (not IPv6)

		curl_setopt($curl, CURLOPT_HEADER, false);

		//curl_setopt($curl, CURLOPT_HTTPGET, 1);
		//curl_setopt($curl, CURLINFO_HEADER_OUT, true);
		curl_setopt($curl, CURLOPT_VERBOSE, true); // Verbose for debugging

	    $result = curl_exec($curl);
		if (curl_errno($curl)) {
		    $result = false;
		    Log::error('cURL Error: '.curl_error($curl),["File"=>__FILE__,"Method"=>__METHOD__,"URL"=>$url]);
		}

		//Debugging
		$info = curl_getinfo($curl);
		Log::error($info);

	    curl_close($curl);

	   // echo $url;

	   return $result;
	}
}