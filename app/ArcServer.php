<?php
namespace App;
use App\Status;
use Log;


class ArcServer {
	private static $base_url = "https://egp.nwcg.gov/arcgis/rest/services/FireCOP/ShortHaul/FeatureServer";
	private static $token = env("ARCGIS_TOKEN");
	/*
	private static $token = array(	"token"		=> "SpvE91TwwP5_I32kEmQrGFndu2kdXC5zepKLBOF-cJY3ZlLLLU0tyaaiMsceXcVB",
									"expires"	=> 1450541655362,	// Expires 12/21/2016
									//"referer"	=> "http://resourcestatus.smirksoftware.com");
									"referer"	=> "208.101.226.130"); 
	*/


	public static function testToken() {
		// This will make a query to the ArcGIS server to test the token.
		
		if(!env('APP_DEBUG')) return false; // Only allow this functionality for debugging!

		$params = array();
		$params['layerDefs']= "{\"0\":\"statusable_name!='uNlIkElYnaME'\"}";
		$params['token']	= self::$token['token'];
		$params['returnIdsOnly']	= 'true';
		$params['f']		= 'json';

		$url = self::$base_url."/query";

		$response = self::callAPI("GET",$url,$params);
		$json_response = json_decode($response,true);
		if(array_key_exists("error",$json_response)) {
			echo "Token is invalid<br />".PHP_EOL."token:".self::$token['token']."<br />".PHP_EOL;
		}
		else {
			echo "Token appears to be working<br />".PHP_EOL."token:".self::$token['token']."<br />".PHP_EOL;
		}
		echo var_export($response,true);
		return;
	}


	public static function findFeature($status) {
		// Check the ArcGIS server for an existing Feature Object that matches this tailnumber (or Crew ID)
		// Returns an array containing the ArcGIS Server OBJECT_ID of each matching Feature, if found.
		// Returns an empty array if no matching features are found.
		// Returns FALSE if an error occurs (including errors returned by the ArcGIS server).

		// Specify the REST endpoint to query
		$url = self::$base_url."/query";

		// Define the query that will be used on the ArcGIS server to retrieve the desired Features
		$layerDef = "statusable_id='$status->statusable_id'"
					." AND statusable_type='$status->statusable_type'";

		// Choose which layer to query on the Feature Server (i.e. Helicopters are on Layer 0, Crews are on Layer 1, etc.)
		$type = explode("\\",$status->statusable_type)[1]; // App\Helicopter ==> Helicopter
		switch($type) {
			case "Helicopter":
			default:
				$layer = 0;
				break;
		}

		// Set all of the query string parameters - these will be appended to the URL in the API call
		$params = array();
		$params['layerDefs']	= json_encode(array($layer=>$layerDef), JSON_FORCE_OBJECT); // Force Object to interpret numeric $layer values as strings
		$params['token']		= self::$token['token'];
		$params['returnIdsOnly']= 'true';
		$params['f']			= 'json';

		// Send the HTTP request
		$response = self::callAPI("GET",$url,$params);
		$json_response = json_decode($response,true);
		
		// Check the server response for success or failure
		if($response === false) {
			// An error occurred during the API call, this has already been logged by the callAPI method.
			$object_ids = false;
		}
		elseif(isset($json_response["layers"][$layer]) && array_key_exists("objectIds",$json_response['layers'][$layer])) {
			// The server found some matches
			$object_ids = $json_response["layers"][$layer]["objectIds"];
		}
		elseif(isset($json_response["error"])) {
			// The ArcGIS server returned an error code - log it and return FALSE
			Log::error('ArcGIS server error: '.$json_response['error']['message'],["File"=>__FILE__,"Method"=>__METHOD__,"URL"=>$url, "URL_params"=>$params, "ArcGIS Error Code"=>$json_response['error']['code']]);
			$object_ids = false;
		}
		else {
			// Some OTHER response was returned (i.e. an empty string, unexpected content, non-JSON or JSON doesn't contain the expected parameters)
			Log::error('Unexpected response from ArcGIS server. Expecting a FeatureSet, but received this instead:'.PHP_EOL.$response,["File"=>__FILE__,"Method"=>__METHOD__,"URL"=>$url, "URL_params"=>$params]);
			$object_ids = false;
		}
		return $object_ids;
	}

	public static function addFeature($status) {
		// Add a new Feature to the ArcGIS server using the AddFeatures REST endpoint
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

		// Build an array of attributes from this Status update that will be sent to the ArcGIS server
		$attributes = $status->toArray(); // Convert this Status object into an array

		// 'geometry' is required by the ArcGIS server to actually plot the point on the map.
		// (docs: https://egp.nwcg.gov/arcgis/sdk/rest/index.html#/Geometry_Objects/02ss0000008m000000/)
		// wkid: 4326 (GCS_WGS_1984) - geographic coordinate system (lat/lon)
		// wkid: 3857 (WGS_1984_Web_Mercator_Auxiliary_Sphere) - Projected coordinate system (x/y projected onto flat globe)
		$geometry = array(	
							"x"					=> floatval($status->longitude),
							"y"					=> floatval($status->latitude),
							"spatialReference"	=> array("wkid"	=> 4326)
						);

		$params = array();
		$params['token']	= self::$token['token'];
		$params['features'] = json_encode(array(array("geometry" => $geometry, "attributes" => $attributes)));
		$params['f']		= 'json';

		$response = self::callAPI("POST", $url, $params);

		// Check for errors, process response
		$json_response = json_decode($response);
		$output = new \stdClass();

		if(isset($json_response->addResults[0])) {
			// Inspect the response to determine if any errors occurred
			$result = $json_response->addResults[0];

			if(empty($result) || $result == "''") {
				$output->response = "";
				$output->error = "The server did not respond to 'addFeature'";
			}
			elseif(isset($result->success) && ($result->success == true)) {
				$output->response = $json_response;
				$output->error = "";
			}
			elseif(!is_null($result->success) && ($result->success === false)) {
				$output->response = $result;
				$output->error = $result->error->description;
			}
			else {
				$output->response = $json_response;
				$output->error = "The ArcGIS server returned an unexpected response to 'addFeature'";
			}
		}
		else {
			// The server response did not include a 'updateResults' index
			$output->response = $json_response;
			$output->error = "The ArcGIS server returned an empty response to 'addFeature'";
		}

		return $output;
	}

	public static function updateFeature($objectid,$status) {
		// Update an existing Feature on the ArcGIS server using the UpdateFeatures REST endpoint
		// (docs: https://egp.nwcg.gov/arcgis/sdk/rest/index.html#/Update_Features/02ss00000096000000/)
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
		$url = self::$base_url."/$layer/updateFeatures";

		// Build an array of attributes from this Status update that will be sent to the ArcGIS server
		$attributes = $status->toArray(); 		// Convert this Status object into an array
		$attributes["OBJECTID"] = $objectid;	// Append the ArcGIS Server OBJECTID so we know which Feature to update

		// 'geometry' is required by the ArcGIS server to actually plot the point on the map.
		// (docs: https://egp.nwcg.gov/arcgis/sdk/rest/index.html#/Geometry_Objects/02ss0000008m000000/)
		// wkid: 4326 (GCS_WGS_1984) - geographic coordinate system (lat/lon)
		// wkid: 3857 (WGS_1984_Web_Mercator_Auxiliary_Sphere) - Projected coordinate system (x/y projected onto flat globe)
		$geometry = array(	
							"x"					=> floatval($status->longitude),
							"y"					=> floatval($status->latitude),
							"spatialReference"	=> array("wkid"	=> 4326)
						);

		$params = array();
		$params['token']	= self::$token['token'];
		$params['features'] = json_encode(array(array("geometry" => $geometry, "attributes" => $attributes)));
		$params['f']		= 'json';

		$response = self::callAPI("POST", $url, $params);

		// Check for errors, process response
		$json_response = json_decode($response);
		$output = new \stdClass();

		if(isset($json_response->updateResults[0])) {
			// Inspect the response to determine if any errors occurred
			$result = $json_response->updateResults[0];

			if(empty($result) || $result == "''") {
				$output->response = "";
				$output->error = "The server did not respond to 'updateFeature'";
			}
			elseif(isset($result->success) && ($result->success == true)) {
				$output->response = $json_response;
				$output->error = "";
			}
			elseif(!is_null($result->success) && ($result->success === false)) {
				$output->response = $result;
				$output->error = $result->error->description;
			}
			else {
				$output->response = $json_response;
				$output->error = "The ArcGIS server returned an unexpected response to 'updateFeature'";
			}
		}
		else {
			// The server response did not include a 'updateResults' index
			$output->response = $json_response;
			$output->error = "The ArcGIS server returned an empty response to 'updateFeature'";
		}

		return $output;
		
	}

	public static function deleteFeature($objectid) {
		// Delete the feature on the ArcGIS server with the OBJECTID specified by $objectid

		$layer = 0; // Helicopters are drawn on layer 0
		$url = self::$base_url."/$layer/deleteFeatures";


		$params = array();
		$params['token']	= self::$token['token'];
		$params['objectIds'] = $objectid;
		$params['f']		= 'json';

		$response = self::callAPI("POST", $url, $params);

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
		try {
		    $curl = curl_init();

		    switch ($method)
		    {
		        case "POST":
		            curl_setopt($curl, CURLOPT_POST, 1);
		            if ($data) curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		            break;

		        case "GET":
		        default:
		            if ($data) $url = sprintf("%s?%s", $url, http_build_query($data));
		            break;
		    }

		    curl_setopt($curl, CURLOPT_URL, $url);
		    curl_setopt($curl, CURLOPT_REFERER, self::$token['referer']); //Needed to accompany the token for authentication

		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		    curl_setopt($curl, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 ); // Use IPv4 (not IPv6)

			curl_setopt($curl, CURLOPT_HEADER, false);
			curl_setopt($curl, CURLINFO_HEADER_OUT, true);

			// Send the request!
		    $result = curl_exec($curl);

    	    // Log errors
			if (curl_errno($curl)) {
			    //$result = false;
			    throw Exception('cURL Error: '.curl_error($curl));
			    Log::error('cURL Error: '.curl_error($curl).PHP_EOL,["File"=>__FILE__,"Method"=>__METHOD__,"URL"=>$url]);
			}

			// Log debugging output
			if(env('APP_DEBUG')) {
				Log::debug("Request sent with cURL:".PHP_EOL,["File"=>__FILE__,"Method"=>__METHOD__, "Params"=>($data ? http_build_query($data) : ""),"curl_getInfo"=>curl_getinfo($curl)]);
				Log::debug("Response from ArcGIS Server:".PHP_EOL.var_export($result,true));
			}

		    
		} catch (Exception $e) {
			$result = false;
			Log::error($e->getMessage().PHP_EOL,["File"=>__FILE__,"Method"=>__METHOD__]);
		}

		curl_close($curl);
		return $result;
	}
}