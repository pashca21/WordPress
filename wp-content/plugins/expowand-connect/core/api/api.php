<?php

class API {	 
    public function get_company_data() {
		$token = API::get_token();
		$url = FF_API_COMPANY_SERVICE ;
        if (empty($token) || empty($url)) { return false; }
		$result = API::get_content($token, $url);
		if(empty($result) || $result['response']['code'] != 200) { return false; }
		return json_decode($result['body'],true);
    }

    protected function get_last_change_date() {
        $token  = API::get_token();
        $url    = EW_API_OFFERS_LAST_CHANGE;
        if (empty($token) || empty($url)) { return false; }
        $args = array(
            'timeout' => 60,
            'redirection' => 60,
            'httpversion' => '1.0',
            'user-agent' => 'FF SA WEBMODULE -' . home_url(),
            'blocking' => true,
            'headers' => array("cognitoToken" => $token, "Content-Type" => "application/json"),
            'cookies' => array(),
            'body' => '',
            'compress' => false,
            'decompress' => true,
            'sslverify' => true, 
            'stream' => false,
            'filename' => null
        );
        $result = wp_remote_post($url, $args);
        if (empty($result) || $result['response']['code'] != 200) { return false; }
        return json_decode($result['body']);
    }
		
    protected function get_entities_by_search($search = NULL,$max_results = NULL, $page = 1) {
        $token  = API::get_token();
        $url    = FF_API_SEARCH_SERVICE.'/'.'?page='.$page.'&size='.$max_results;

        if (!empty($search) && !empty($url)) {
            $args = array(
                'timeout' => 60,
                'redirection' => 60,
                'httpversion' => '1.0',
                'user-agent' => 'FF SA WEBMODULE -' . home_url(),
                'blocking' => true,
                'headers' => array("cognitoToken" => $token, "Content-Type" => "application/json"),
                'cookies' => array(),
                'body' => json_encode($search , JSON_NUMERIC_CHECK),
                'compress' => false,
                'decompress' => true,
                'sslverify' => true, 
                'stream' => false,
                'filename' => null
            );
        
            $result = wp_remote_post($url, $args);
            // return $result;
        
            if (!empty($result) && $result['response']['code'] == 200) {			  
                return json_decode($result['body']);
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function findEstatesBySchemaIdAndSearch($schemaId, $search) {
        if (empty($schemaId) || empty($search)) {
            return false;
        }
        $token = API::get_token();
        $url = FF_API_SEARCH_SERVICE . '/schemas/' . $schemaId .'?size=1000&page=1';
        $args = array(
            'timeout' => 60,
            'redirection' => 60,
            'httpversion' => '1.0',
            'user-agent' => 'FF SA WEBMODULE -' . home_url(),
            'blocking' => true,
            'headers' => array("cognitoToken" => $token, "Content-Type" => "application/json"),
            'cookies' => array(),
            'body' => json_encode($search, JSON_NUMERIC_CHECK),
            'compress' => false,
            'decompress' => true,
            'sslverify' => true,
            'stream' => false,
            'filename' => null
        );
        $result = wp_remote_post($url, $args);
        if (empty($result) || $result['response']['code'] != 200) {
            return false;
        }
        return json_decode($result['body'], true);
    }

    protected function get_entity_by_id($schemaId = NULL , $id = NULL){
        if (empty($schemaId) || empty($id)) { return false; }

        $token  = API::get_token();
        $url    = FF_API_ENTITY_SERVICE_ENTITY.'/'.$id;
        $args = array(
            'timeout' => 60,
            'redirection' => 60,
            'httpversion' => '1.0',
            'user-agent' => 'FF SA WEBMODULE -' . home_url(),
            'blocking' => true,
            'headers' => array("cognitoToken" => $token, "Content-Type" => "application/json"),
            'cookies' => array(),
            'body' => null,
            'compress' => false,
            'decompress' => true,
            'sslverify' => true,
            'stream' => false,
            'filename' => null
        );

        $result = wp_remote_get($url, $args);
        // print_r($result);exit;
        if (!empty($result) && $result['response']['code'] == 200) {
            $this->set_entity_cache($id,  $schemaId, $result['body']);

            return json_decode($result['body']);
        }

        return false;
    }

	function get_estate_images ($entityId = NULL, $schema = "estates?albumName=homepage&showEmptyCategories=false&short=false") {
        if (empty($entityId)) {
            return false;
        }

        // set url
        $url 				= FF_API_MULTIMEDIA_SERVICE.$schema;
        $data["entities"] 	= $entityId;

        // get token
        $token = API::get_token();
        $result = API::post_content($token, $url, $data);

        if (!empty($result) && $result['response']['code'] == 200) {
            return json_decode($result['body'],true);
        }

        return false;
    }

	// Get API TOKEN
    protected function get_token(){
        return 9019; // TODO: remove this line
		global $wpdb;
		$result = $wpdb->get_results("SELECT json FROM {$wpdb->prefix}ew_general_cache WHERE name = 'EW_TOKEN' and  created > (now() - INTERVAL ".EW_CACHE." Minute)");
		if (!empty($result)) {
			// get cached token
			return $result[0]->json;
		} else {
			if(!empty(get_option('ew-token'))) {
				// get token
                $call = API::get_content("e96fd307-9019-4cf3-a9c4-c5490a7fbd76",FF_API_ADMIN_TOKEN_SERVICE_AUTH, "token");
				// $call = API::get_content(EW_TOKEN, FF_API_ADMIN_TOKEN_SERVICE_AUTH, "token");
				// add token to cache
				$this->set_general_cache('EW_TOKEN', $call['body']);
			}else{
				// set demo token
                $call = API::get_content("e96fd307-9019-4cf3-a9c4-c5490a7fbd76",FF_API_ADMIN_TOKEN_SERVICE_AUTH, "token");
			}
			return $call['body'];
		}	
    }

	// Get content via rest
    protected function get_content($token, $url, $type = "cognitoToken") {

        $args = array(
            'timeout' => 60,
            'redirection' => 60,
            'httpversion' => '1.0',
            'user-agent' => 'FF SA WEBMODULE -' . home_url(),
            'blocking' => true,
            'headers' => array($type => $token),
            'cookies' => array(),
            'body' => null,
            'compress' => false,
            'decompress' => true,
            'sslverify' => true,
            'stream' => false,
            'filename' => null
        );

        $response = wp_remote_get($url, $args);

        if (is_array($response)) {
            return $response;
        } else {
            return false;
        }
    } 
	
	// post content via rest
    protected function post_content($token, $url, $data, $type = "cognitoToken") {

        $args = array(
            'timeout' => 60,
            'redirection' => 60,
            'httpversion' => '1.0',
            'user-agent' => 'FF SA WEBMODULE -' . home_url(),
            'blocking' => true,
            'headers' => array($type => $token, 'Content-Type' => 'application/json', 'charset' => 'utf-8'),
            'cookies' => array(),
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE),
            'compress' => false,
            'decompress' => true,
            'sslverify' => true,
            'stream' => false,
            'filename' => null
        );
	
        $response = wp_remote_post($url, $args);
		
        if (is_array($response)) {
            return $response;
        } else {
            return false;
        }
    }
	
    protected function set_general_cache($name = NULL, $data = NULL) {
        if (empty($name) || empty($data)) { return false; }
        global $wpdb;
        $sql = "INSERT INTO {$wpdb->prefix}ew_general_cache (created,name,json) VALUES (now(),'" . $name . "','" . $data . "') ON DUPLICATE KEY UPDATE created = now(), json = '" . $data . "';";
        $wpdb->query($sql);
        return;
    }
	
    protected function set_entity_cache($entityId = NULL, $schemaId = NULL, $data = NULL) {
        if (empty($entityId) || empty($schemaId) || empty($data)) { return false; }
        global $wpdb;
        $sql = "INSERT INTO {$wpdb->prefix}ew_entity_cache (created,entityId,schemaId,json) VALUES (now(),'" . $entityId . "','" . $schemaId . "','" . $data . "') ON DUPLICATE KEY UPDATE created = now(), json = '" . $data . "';";
        $wpdb->query($sql);
        return;
    }
		
    // TODO: to delete
	function get_formated_fields($field = NULL, $field_data = NULL, $data = NULL, $estateId = NULL) {
		
		if(!empty($field_data["type"]))
		{

			switch ($field_data["type"]) {

				case "text":
					$result["type"]    = $field_data["type"];
					$result["caption"] = $field_data["caption"];
					$result["unit"]    = $field_data["unit"];
                    if(isset($data['offer'][$field])) $source_value = $data['offer'][$field];
                    else if(isset($data['offerdetails'][$field])) $source_value = $data['offerdetails'][$field];
					$result["value"]   = $source_value;
					break;

				case "number":
					if(isset($data['offerdetails'][$field]["from"])=== true)
					{
						if( empty($data['offerdetails'][$field]["to"]) or ($data['offerdetails'][$field]["from"] == $data['offerdetails'][$field]["to"]))
						{
							$result["type"]    = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["value"]   = $data['offerdetails'][$field]["from"];
						}
						else
						{
							$result["type"]    = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["from"]    = $data['offerdetails'][$field]["from"];
							$result["to"]      = $data['offerdetails'][$field]["to"];
						}
					}
					else
					{
						$result["type"] 	= $field_data["type"];
						$result["caption"] 	= $field_data["caption"];
						$result["unit"]    	= $field_data["unit"];
                        if(isset($data['offerdetails'][$field]))
						    $result["value"] 	= $data['offerdetails'][$field];
                        if(isset($data['offer'][$field]))
                            $result["value"] 	= $data['offer'][$field];
                        
					}

					break;

				case "number_formatted":
					if(isset($data['offerdetails'][$field]["from"])=== true && (!empty($data['offerdetails'][$field]["from"]) OR !empty($data['offerdetails'][$field]["to"])))
					{
						if(empty($data['offerdetails'][$field]["to"])or ($data['offerdetails'][$field]["from"] == $data['offerdetails'][$field]["to"]))
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["value"] = number_format($data['offerdetails'][$field]["from"],0,",",".");
						}
						else
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["from"] = number_format($data['offerdetails'][$field]["from"],0,",",".");
							$result["to"] = number_format($data['offerdetails'][$field]["to"],0,",",".");
						}
					}
					else
					{
						if(!empty($data['offerdetails'][$field]))
						{
							if(is_numeric($data['offerdetails'][$field]) === true)
							{
								$result["type"] 	= $field_data["type"];
								$result["caption"] 	= $field_data["caption"];
								$result["unit"]    	= $field_data["unit"];
								$result["value"] 	= number_format($data['offerdetails'][$field],0,",",".");
							}
							else
							{
								$result["type"] 	= $field_data["type"];
								$result["caption"] 	= $field_data["caption"];
								$result["unit"]    	= $field_data["unit"];
								$result["value"] 	= $data['offerdetails'][$field];
							}
						}	
					}
					break;
				case "area":
					if (isset($data['offerdetails'][$field]["from"]) === true) {
						if (!empty($data['offerdetails'][$field]["from"]) OR !empty($data['offerdetails'][$field]["to"]) AND ($data['offerdetails'][$field]["to"] != $data['offerdetails'][$field]["from"])) {
							$result["type"]		= $field_data["type"];
							$result["caption"] 	= $field_data["caption"];
							$result["unit"] 	= $field_data["unit"];
							$result["from"] 	= (!empty($data['offerdetails'][$field]["from"]))? number_format($data['offerdetails'][$field]["from"], 0, ",", "."):"";
							$result["to"] 		= (!empty($data['offerdetails'][$field]["to"]))? number_format($data['offerdetails'][$field]["to"], 0, ",", "."):"";
						} else {
							$result["type"] 	= $field_data["type"];
							$result["caption"] 	= $field_data["caption"];
							$result["unit"] 	= $field_data["unit"];
							$result["value"] 	= (!empty($data['offerdetails'][$field]["from"]))?number_format($data['offerdetails'][$field]["from"], 0, ",", "."):"";
							
						}
					} else {
						if (is_numeric($data['offerdetails'][$field]) === true) {
							$result["type"] 	= $field_data["type"];
							$result["caption"] 	= $field_data["caption"];
							$result["unit"] 	= $field_data["unit"];
							$result["value"] 	= number_format($data['offerdetails'][$field], 0, ",", ".");
						} else {
							$result["type"] 	= $field_data["type"];
							$result["caption"] 	= $field_data["caption"];
							$result["unit"] 	= $field_data["unit"];
							$result["value"]	= $data['offerdetails'][$field];
						}

					}
					break;

				case "image":
					foreach($data['offerdetails'][$field]["values"] as $element => $element_value)
					{
						$result[$element]["type"] = $field_data["type"];
						$result[$element]["caption"] = $field_data["caption"];
						$result[$element]["value"] = $element_value["uri"];
					}
					break;

				case "currence":
					
					if(isset($data['offerdetails'][$field]["from"])=== true)
					{
						if(empty($data['offerdetails'][$field]["to"])or ($data['offerdetails'][$field]["from"] == $data['offerdetails'][$field]["to"]))
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["value"] = (!empty($data['offerdetails'][$field]["from"]))?  number_format($data['offerdetails'][$field]["from"],2,",","."):"";
						}
						else
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["from"] = (!empty($data['offerdetails'][$field]["from"]))? number_format($data['offerdetails'][$field]["from"],2,",","."):"";
							$result["to"] = (!empty($data['offerdetails'][$field]["to"]))? number_format($data['offerdetails'][$field]["to"],2,",","."):"";
						}
					}
					else
					{
						if(is_numeric($data['offerdetails'][$field]) === true)
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["value"] = number_format($data['offerdetails'][$field],2,",",".");
						}
						else
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["value"] = $data['offerdetails'][$field];
						}

					}
					break;

				case "date":
						if(!empty($data['offerdetails'][$field]+7200))
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["value"] = gmdate("d.m.Y", (int)$data['offerdetails'][$field]+7200);
						}
					break;

				case "option":
						if(!empty($data['offerdetails'][$field]) or $data['offerdetails'][$field] == "0")
						{
							foreach($data['offerdetails'][$field]["values"] as $key => $val)
							{
								$result["type"] = $field_data["type"];
								$result["caption"] = $field_data["caption"];

                                if(!empty( $field_data["option"][$data['offerdetails'][$field]["values"][$key]])) {
                                    $result["value"][$key] =  $field_data["option"][$data['offerdetails'][$field]["values"][$key]];
                                } else {
                                    $result["value"][$key] =  "";
                                }
							}
						}
					break;

				case "yesno":
					if($data['offerdetails'][$field] == 1)
					{
						$result["type"] = $field_data["type"];
						$result["caption"] = $field_data["caption"];
						$result["value"] = "ja";
					}
					else
					{
						$result["type"] = $field_data["type"];
						$result["caption"] = $field_data["caption"];
						$result["value"] = "nein";
					}

					break;

				case "addresses":

					$result["type"] = $field_data["type"];
					$result["caption"] = $field_data["caption"];
					$result["value"] = $data['offerdetails'][$field];
					break;

				default:
			
			}	

			// return
			if(!empty($result))
			{
				return $result;
			}
			else
			{
				return NULL;
			}	
			
		}
		else
		{
			return NULL;
		}
		
	}

}

if (class_exists('API')) {
    $API = new API();
}
