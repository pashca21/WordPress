<?php

class API
{
	/********************************
	 *								*
	 *		API Calls				*
	 *								*
	 ********************************/
	 
	// get Views
    protected function get_all_views_by_group(){
		// set url
		$url = FF_API_VIEW_DEFINITION_SERVICE . '/fields/' . urlencode($name);

		// get token
		$token = API::get_token();
		$result = API::get_content($token, $url);
		
		if(!empty($result) && $result['response']['code'] == 200) {

			// add token to cache
			$this->set_general_cache($name, $result['body']);

			// retrun
			return json_decode($result['body']);
		} else {
			return false;
		}
       
    }  

    
    // get Views
    protected function get_views($name = NULL){

        if (!empty($name)) {

            global $wpdb;
            $result = $wpdb->get_results("SELECT json
                                      FROM
                                        {$wpdb->prefix}ff_general_cache
                                      WHERE
                                           name = '".$name."'
                                      and  created > (now() - INTERVAL ".FF_CACHE." Minute)");

            if (!empty($result)) {
                return json_decode($result[0]->json);
            } else {
                // set url
                $url = FF_API_VIEW_DEFINITION_SERVICE . '/fields/' . urlencode($name);

                // get token
                $token = API::get_token();
                $result = API::get_content($token, $url);
				
                if(!empty($result) && $result['response']['code'] == 200) {

                    // add token to cache
                    $this->set_general_cache($name, $result['body']);

                    // retrun
                    return json_decode($result['body']);
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }    
	
	
	public function create_contact($schema, $data = NULL){
        if (!empty($data)) {
			
            // set url
            $url = FF_API_ENTITY_SERVICE_ENTITY . '/'.$schema;

            // get token
            $token = API::get_token();
            $result = API::post_content($token, $url, $data);
		
			if(!empty($result) && $result['response']['code'] == 200) {
                return $result['body'];
            } else {
                return false;
            }
        }
    }

    public function modify_entity($schema, $entityId, $data = null) {
        if (empty($data)) {
            return null;
        }

        $url = FF_API_ENTITY_SERVICE_ENTITY . '/' . $schema . '/entities/' . $entityId;

        // get token
        $token = API::get_token();
        $result = API::patch_content($token, $url, $data);

        if(!empty($result) && $result['response']['code'] == 200) {
            return $result['body'];
        }
        return false;
    }
	
	protected function create_view($data = NULL){
        if (!empty($data)) {

            // set url
            $url = FF_API_VIEW_DEFINITION_SERVICE . '/';

            // get token
            $token = API::get_token();
            $result = API::post_content($token, $url, $data);

            if(!empty($result) && $result['response']['code'] == 200) {
                return json_decode($result['body']);
            } else {
                return false;
            }
        }
    }
	
	// get exsisting Portals for Portalpublishing
    public function get_portals() {
       
		// set url
		$url = FF_API_PORTAL_MANAGEMENT_SERVICE ;
		
		// get token
		$token = API::get_token();
		$result = API::get_content($token, $url);

		if(!empty($result) && $result['response']['code'] == 200) {
			return json_decode($result['body'],true);
		} else {
			return false;
		}

    }	
		
		
	// get exsisting Portals for Portalpublishing
    public function get_company_data() {
       
		// set url
		$url = FF_API_COMPANY_SERVICE ;
		
		// get token
		$token = API::get_token();
		$result = API::get_content($token, $url);

		if(!empty($result) && $result['response']['code'] == 200) {
			return json_decode($result['body'],true);
		} else {
			return false;
		}

    }	
	
	// get exsisting Portals for Portalpublishing
    public function get_portals_publsidhed_estates_by_portal_id($id) {
      
		// set url
		$url = FF_API_PORTAL_MANAGEMENT_SERVICE.'/'.$id.'/estates?onlyReturnIfCurrentlyOnline=true' ;
		
		// get token
		$token = API::get_token();
		$result = API::get_content($token, $url);

		if(!empty($result) && $result['response']['code'] == 200) {
			return json_decode($result['body'],true);
		} else {
			return false;
		}

    }


    // check if given estate is published for given portal
    public function get_portals_publsidhed_estates($id, $portalId)
    {
        $url = FF_API_PORTAL_MANAGEMENT_SERVICE . '/' . $portalId . '/estates/' . $id . '?onlyReturnIfCurrentlyOnline=true';
        $token = API::get_token();
        $result = API::get_content($token, $url);
        if (!empty($result) && $result['response']['code'] == 200) {
            return json_decode($result['body'], true);
        }
        return false;
    }
		
	
	// get Portals data
    public function get_portals_data($id) {
      
		// set url
		$url = FF_API_PORTAL_MANAGEMENT_SERVICE.'/'.$id ;
		
		// get token
		$token = API::get_token();
		$result = API::get_content($token, $url);

		if(!empty($result) && $result['response']['code'] == 200) {
			return json_decode($result['body'],true);
		} else {
			return false;
		}

    }
	
	// get rent price of a estate
	protected function get_estate_valuation_by_quality($search) {
		
		
        if (!empty($search)) {
            // set url
            $url = FF_API_ESTATE_VALIDATION."/equipment";

            // get token
            $token = API::get_token();
            $result = API::post_content($token, $url, $search);
			
			if (!empty($result) && $result['response']['code'] == 200) {
				return json_decode($result['body'],true);
			} else {
				return false;
			}
        } else {
			return false;
		}
    }

	// get schemas by schema Group
    protected function get_schemas_by_schema_group($group = NULL) {
        if (!empty($group)) {

            global $wpdb;
            $result = $wpdb->get_results("SELECT json
                                      FROM
                                        {$wpdb->prefix}ff_schema_cache
                                      WHERE
                                           schemaName = 'FF ".$group." Schemas'
                                      and  created > (now() - INTERVAL ".FF_CACHE." Minute)");

            if (!empty($result)) {
                return json_decode($result[0]->json);

            } else {
                // set url
                $url = FF_API_SCHEMA_SERVICE . '/estates?transform&resolveGroup=true';

                // get token
                $token = API::get_token();
                $result = API::get_content($token, $url);

                if(!empty($result) && $result['response']['code'] == 200) {

                    // add token to cache
                    //$this->set_schema_cached('FF '.$group.' Schemas', 'LOCAL', $result['body'] );

                    // retrun
                    return json_decode($result['body']);
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    protected function isSchemaGroupAvailable($groupName) {
        $url = FF_API_SCHEMA_SERVICE_BASE_PATH . '/global/groups/assignments';
        $token = API::get_token();
        $result = API::get_content($token, $url);
        if(!empty($result) && $result['response']['code'] == 200) {
            $assignedGroups = json_decode($result['body']);
            return array_search($groupName, array_column($assignedGroups->globalGroupAssignments, 'groupName')) !== false;
        }

        return false;
    }

    // get schemas by name
    protected function get_schemas_by_name($name = NULL) {
        if (!empty($name)) {
            // set url
            $url = FF_API_SCHEMA_SERVICE . '/'.$name;
			
            // get token
            $token = API::get_token();
            $result = API::get_content($token, $url);
			
            if(!empty($result) && $result['response']['code'] == 200) {
                return json_decode($result['body'],true);
            } else {
                return false;
            }
        }
    }

    public function get_users() {
       
        // set url
        $url = FF_API_USER_SERVICE;
        $users = get_transient( 'ff_team_transient' );
           
        if( $users == false ){
            $token = API::get_token();
            $result = API::get_content($token, $url);
 
            if(!empty($result) && $result['response']['code'] == 200) {
              
                $users = json_decode($result['body'],true);
                $users =  $this->filter_active_users($users);
                
				//set cached data
                set_transient( 'ff_team_transient', $users, 120 );
                   
                return $users;
            } else {
              
                return false;
            }
        } else {
            return $this->filter_active_users($users);
        }
    }

    public function get_users_no_cache() {
        // set url
        $url = FF_API_USER_SERVICE;
        
        $token = API::get_token();
        $result = API::get_content($token, $url);

        if(!empty($result) && $result['response']['code'] == 200) {
            $users = json_decode($result['body'],true);

            //$users =  $this->filter_active_users($users);
                
            return $users;
        } else {
            return false;
        }
    }
	
function filter_active_users($users){
    $active_users= array();
    foreach($users as $user){

        if($user['active']){

			$active_users[]=$user; 
        }
		       
    }
    return $active_users;

}
    protected function create_schema($data = NULL){
        if (!empty($data)) {

            // set url
            $url = FF_API_SCHEMA_SERVICE . '/';

            // get token
            $token = API::get_token();
            $result = API::post_content($token, $url, $data);

            if(!empty($result) && $result['response']['code'] == 200) {
                return json_decode($result['body']);
            } else {
                return false;
            }
        }
    }    
	
	protected function delete_schemas_by_id($id = NULL) {
        if (!empty($id)) {

            // set url
            $url = FF_API_SCHEMA_SERVICE . '/'.$name;

            // get token
            $token = API::get_token();
            $result = API::get_content($token, $url);

            if(!empty($result) && $result['response']['code'] == 200) {
                return true;
            } else {
                return false;
            }
        }
    }
		
    // search via rest
    protected function get_entities_by_search($schemaId = NULL , $search = NULL,$max_results = NULL, $page = 1) {
		
		
        if(!empty($schemaId))
        {
            // get token
            $token  = API::get_token();
            $url    = FF_API_SEARCH_SERVICE.'/schemas/'.$schemaId.'?page='.$page.'&size='.$max_results;

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

                if (!empty($result) && $result['response']['code'] == 200) {			  
				   return json_decode($result['body'],true);
                } else {
                    return false;
                }
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
        if (!empty($result) && $result['response']['code'] == 200) {
            return json_decode($result['body'], true);
        }

        return false;
    }

    protected function getCountOfEntitiesBySchemaId($schemaId = NULL , $search = NULL) {
        if (!empty($schemaId)) {
            // get token
            $token = API::get_token();
            $url = FF_API_SEARCH_SERVICE . '/schemas/' . $schemaId . '/count';

            if (!empty($search) && !empty($url)) {
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
                if (!empty($result) && $result['response']['code'] == 200) {
                    return json_decode($result['body'], true);
                }
                return false;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

	//get entity by id
    protected function get_entity_by_id($schemaId = NULL , $id = NULL){
        if (empty($schemaId) || empty($id)) {
            return false;
        }

        $token  = API::get_token();
        $url    = FF_API_ENTITY_SERVICE_ENTITY.'/'.$schemaId.'/entities/'.$id;
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
        if (!empty($result) && $result['response']['code'] == 200) {
            $this->set_entity_cache($id,  $schemaId, $result['body']);

            return json_decode($result['body'],true);
        }

        return false;
    }

	// get all integration
	protected function get_all_integration() {
		
		// set url
		$url = FF_API_SCHEMA_SERVICE_INTIGRATION;

		// get token
		$token = API::get_token();
		$result = API::get_content($token, $url);

		if (!empty($result) && $result['response']['code'] == 200) {
			return json_decode($result['body'],true);
		} else {
			return false;
		}	
    }

	
	// get all integration
	function get_all_nylas_accounts() {
		
		// set url
		$url = FF_API_NYLAS_SERVICE."/config";

		// get token
		$token = API::get_token();
		$result = API::get_content($token, $url);

		if (!empty($result) && $result['response']['code'] == 200) {
			return json_decode($result['body'],true);
		} else {
			return false;
		}
    }


	// get entitlement
	function get_entitlement($product = NULL) {
		
		if($product) {
		
			// set url
			$url = FF_API_ENTITLEMENT_SERVICE.$product;

			// get token
			$token = API::get_token();
			$result = API::get_content($token, $url);
			
			
			if (!empty($result) && $result['response']['code'] == 200) {
				return json_decode($result['body'],true);
			} else {
				return false;
			}
		}
		return false;
    }


    // send mail by nylas
    protected function send_mail_by_nylas($mail, $content)
    {
        if (empty($mail)) {
            return false;
        }

        $url = FF_API_NYLAS_SERVICE . '/nylas/send?email=' . $mail;
        $token = API::get_token();
        $result = API::post_content($token, $url, $content);
        if (!empty($result) && $result['response']['code'] == 200) {
            return json_decode($result['body'], true);
        }

        return false;
    }
	
	// add attachment to nylas
	protected function send_mail_attachent_by_nylas($mail, $content) {

        if (!empty($mail) && !empty($content)) {
            // set url
            $url = FF_API_NYLAS_SERVICE."/nylas/files";

            // get token
            $token = API::get_token();
            $result = API::post_content($token, $url, $content);

			if (!empty($result) && $result['response']['code'] == 200) {
				return json_decode($result['body'],true);
			} else {
				return false;
			}
        } else {
			return false;
		}
    }
	

	// get valuation of a estate
	protected function get_estate_valuation($search) {

        if (!empty($search)) {
            // set url
            $url = FF_API_ESTATE_VALIDATION;

            // get token
            $token = API::get_token();
            $result = API::post_content($token, $url, $search);
			
			if (!empty($result) && $result['response']['code'] == 200) {
				return json_decode($result['body'],true);
			} else {
				return false;
			}
        } else {
			return false;
		}
    }
	
	// get valuation of a estate
	protected function get_estate_valuation_ipi($search) {
		
		
        if (!empty($search)) {
            // set url
            $url = FF_API_ESTATE_VALIDATION."/ipi";

            // get token
            $token = API::get_token();
            $result = API::post_content($token, $url, $search);
			
			if (!empty($result) && $result['response']['code'] == 200) {
				return json_decode($result['body'],true);
			} else {
				return false;
			}
        } else {
			return false;
		}
    }	
	
	// get rent price of a estate
	protected function get_estate_valuation_rent($search) {
		
		
        if (!empty($search)) {
            // set url
            $url = FF_API_ESTATE_VALIDATION_RENT;

            // get token
            $token = API::get_token();
            $result = API::post_content($token, $url, $search);
		
			if (!empty($result) && $result['response']['code'] == 200) {
				return json_decode($result['body'],true);
			} else {
				return false;
			}
        } else {
			return false;
		}
    }
	
	// get rent prices of a estate
	protected function get_estate_valuation_rent_ipi($search) {
		
		
        if (!empty($search)) {
            // set url
            $url = FF_API_ESTATE_VALIDATION_RENT."/ipi";

            // get token
            $token = API::get_token();
            $result = API::post_content($token, $url, $search);
			
			if (!empty($result) && $result['response']['code'] == 200) {
				return json_decode($result['body'],true);
			} else {
				return false;
			}
        } else {
			return false;
		}
    }
	
	
	
	// post create openimmo portal
	function create_openimmo($name = "") {
		
		
        if (!empty($name)) {
            // set url
            $url = FF_API_OPENIMMO_SERVICE;
			$data["user"] 	= $name;			
			$data["status"] = 1;			
			
			
            // get token
            $token = API::get_token();
            $result = API::post_content($token, $url, $data);
			
			if (!empty($result) && $result['response']['code'] == 200) {
				return json_decode($result['body'],true);
			} else {
				return false;
			}
        } else {
			return false;
		}
    }
		
	
	// post get estate Images
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
	
	    protected function get_reportdata_by_id($estateId ){


          /* if (!empty($estateId)) {
            // set url
            $url = FF_API_REPORT_DATA;
    
            // get token
            $token = API::get_token();
            $data['estateId']=$estateId;
            $result = API::post_content($token, $url, $data);
            
            if (!empty($result) && $result['response']['code'] == 200) {
                return json_decode($result['body'],true);
            } else {
                return false;
            }
        } else {
            return false;
        }*/
    
    
        //Temporary, untill API finished
        ob_start();
         include('object-tracking.json');
         $result['body']=ob_get_contents();
         ob_end_clean();
         
        return json_decode($result['body'], true);
    }

    protected function get_estates_by_objecttracking($code){
         if(isset($_GET['pg'])){
                $page = intval($_GET['pg']);
            }
            else{
                $page=1;
        }
        $entities=get_transient( "ff_report_code_transient_{$code}_{$page}" );
        
      
        if(!$entities){
            $search["conditions"][0]["type"] = "HASFIELDWITHVALUE";
            $search["conditions"][0]["field"] = "xtnsn_objecttrackingaccessid_objecttrackingaccessid";
            //ObjectTracking Code
            $search['fetch']=array();
            $search["conditions"][0]["value"] = $code;
            $search["target"] = "ENTITY";
           

            $data["mapping"] = json_decode(FF_OWNERREPORT_MAPPING, true);
            $newschema = array();
            foreach ($data["mapping"]["list"] as $key => $schema) {
                

                array_push($search["fetch"], "id");
                array_push($search["fetch"], "schema");
                array_push($search["fetch"], "_metadata");

                foreach ( $schema as $fields) {


                    foreach ( $fields as $key2 => $field) 
                    {
                        $newschema[$key2]=$field;
                        array_push($search["fetch"], $key2);
                    }
                }

            }  
            
            $entities =API::get_entities_by_search("estates", $search, 10,$page );
        
            set_transient( "ff_report_code_transient_{$code}_{$page}", $entities, 120  );

            return $entities;
        }
        

        return $entities;
    }
	

	/********************************
	 *								*
	 *		Helper classes			*
	 *								*
	 ********************************/
	
	// Get API TOKEN
    protected function get_token(){
		
		global $wpdb;
		$result = $wpdb->get_results("SELECT json
									  FROM
										{$wpdb->prefix}ff_general_cache
									  WHERE
									  name = 'FF TOKEN'
									  and  created > (now() - INTERVAL ".FF_CACHE." Minute)");
		if (!empty($result)) {
			
			// get cached token
			return $result[0]->json;
			
		} else {
			
			if(!empty(get_option('ff-token')))
			{
				// get token
				$call = API::get_content(FF_TOKEN, FF_API_ADMIN_TOKEN_SERVICE_AUTH, "token");
				
				// add token to cache
				$this->set_general_cache('FF TOKEN', $call['body']);
			}
			else
			{
				// set demo token
				$call = API::get_content("e96fd307-9019-4cf3-a9c4-c5490a7fbd76",FF_API_ADMIN_TOKEN_SERVICE_AUTH, "token");
			}	

			// retrun
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

    protected function patch_content($token, $url, $data, $type = "cognitoToken") {
        $args = [
            'method' => 'PATCH',
            'timeout' => 60,
            'redirection' => 60,
            'user-agent' => 'FF SA WEBMODULE -' . home_url(),
            'headers' => [$type => $token, 'Content-Type' => 'application/json', 'charset' => 'utf-8'],
            'cookies' => [],
            'body' => json_encode($data, JSON_UNESCAPED_UNICODE),
        ];

        $http = new WP_Http();
        $response = $http->request($url, $args);
        if (is_array($response)) {
            return $response;
        }
        return false;
    }

    // delete content via rest
    protected function delete_content($token, $url, $type = "cognitoToken") {

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

        $response = wp_remote_delete($url, $args);

        if (is_array($response)) {
            return $response;
        } else {
            return false;
        }
    }	
	
	// set general cache
    protected function set_general_cache($name = NULL, $data = NULL) {
        if (!empty($name) && !empty($data)) {
            global $wpdb;
            $sql = "INSERT INTO {$wpdb->prefix}ff_general_cache (created,name,json) VALUES (now(),'" . $name . "','" . $data . "') ON DUPLICATE KEY UPDATE created = now(), json = '" . $data . "';";
            $wpdb->query($sql);
            return;
        } else {
            return false;
        }
    }
	
	// set entity cache
    protected function set_entity_cache($entityId = NULL, $schemaId = NULL, $data = NULL) {
        if (!empty($entityId) && !empty($schemaId) && !empty($data)) {
            global $wpdb;
            $sql = "INSERT INTO {$wpdb->prefix}ff_entity_cache (created,entityId,schemaId,json) VALUES (now(),'" . $entityId . "','" . $schemaId . "','" . $data . "') ON DUPLICATE KEY UPDATE created = now(), json = '" . $data . "';";
            $wpdb->query($sql);

            return;
        } else {
            return false;
        }
    }
	
	// set schema cache
    protected function set_schema_cache( $schemaId = NULL, $data = NULL) {
        if (!empty($schemaId) && !empty($data) && !empty($schemaId)) {
            global $wpdb;
            $sql = "INSERT INTO {$wpdb->prefix}ff_schema_cache (created,schemaId,json) VALUES (now(),'" . $schemaId . "','" . $data . "') ON DUPLICATE KEY UPDATE created = now(), json = '" . $data . "';";
            $wpdb->query($sql);

            return;
        } else {
            return false;
        }
    }
		
	// get formatted fields
	function get_formated_fields($field = NULL, $field_data = NULL, $data = NULL, $estateId = NULL) {
		
		if(!empty($field_data["type"]))
		{

			switch ($field_data["type"]) {

				case "text":
					$result["type"]    = $field_data["type"];
					$result["caption"] = $field_data["caption"];
					$result["unit"]    = $field_data["unit"];
					$result["value"]   = $data[$field]["values"][0];
					break;

				case "number":
					if(isset($data[$field]["values"][0]["from"])=== true)
					{
						if( empty($data[$field]["values"][0]["to"]) or ($data[$field]["values"][0]["from"] == $data[$field]["values"][0]["to"]))
						{
							$result["type"]    = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["value"]   = $data[$field]["values"][0]["from"];
						}
						else
						{
							$result["type"]    = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["from"]    = $data[$field]["values"][0]["from"];
							$result["to"]      = $data[$field]["values"][0]["to"];
						}
					}
					else
					{
						$result["type"] 	= $field_data["type"];
						$result["caption"] 	= $field_data["caption"];
						$result["unit"]    	= $field_data["unit"];
						$result["value"] 	= $data[$field]["values"][0];
					}

					break;

				case "number_formatted":
					if(isset($data[$field]["values"][0]["from"])=== true && (!empty($data[$field]["values"][0]["from"]) OR !empty($data[$field]["values"][0]["to"])))
					{
						if(empty($data[$field]["values"][0]["to"])or ($data[$field]["values"][0]["from"] == $data[$field]["values"][0]["to"]))
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["value"] = number_format($data[$field]["values"][0]["from"],0,",",".");
						}
						else
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["from"] = number_format($data[$field]["values"][0]["from"],0,",",".");
							$result["to"] = number_format($data[$field]["values"][0]["to"],0,",",".");
						}
					}
					else
					{
						if(!empty($data[$field]["values"][0]))
						{
							if(is_numeric($data[$field]["values"][0]) === true)
							{
								$result["type"] 	= $field_data["type"];
								$result["caption"] 	= $field_data["caption"];
								$result["unit"]    	= $field_data["unit"];
								$result["value"] 	= number_format($data[$field]["values"][0],0,",",".");
							}
							else
							{
								$result["type"] 	= $field_data["type"];
								$result["caption"] 	= $field_data["caption"];
								$result["unit"]    	= $field_data["unit"];
								$result["value"] 	= $data[$field]["values"][0];
							}
						}	
					}
					break;
				case "area":
					if (isset($data[$field]["values"][0]["from"]) === true) {
						if (!empty($data[$field]["values"][0]["from"]) OR !empty($data[$field]["values"][0]["to"]) AND ($data[$field]["values"][0]["to"] != $data[$field]["values"][0]["from"])) {
							$result["type"]		= $field_data["type"];
							$result["caption"] 	= $field_data["caption"];
							$result["unit"] 	= $field_data["unit"];
							$result["from"] 	= (!empty($data[$field]["values"][0]["from"]))? number_format($data[$field]["values"][0]["from"], 0, ",", "."):"";
							$result["to"] 		= (!empty($data[$field]["values"][0]["to"]))? number_format($data[$field]["values"][0]["to"], 0, ",", "."):"";
						} else {
							$result["type"] 	= $field_data["type"];
							$result["caption"] 	= $field_data["caption"];
							$result["unit"] 	= $field_data["unit"];
							$result["value"] 	= (!empty($data[$field]["values"][0]["from"]))?number_format($data[$field]["values"][0]["from"], 0, ",", "."):"";
							
						}
					} else {
						if (is_numeric($data[$field]["values"][0]) === true) {
							$result["type"] 	= $field_data["type"];
							$result["caption"] 	= $field_data["caption"];
							$result["unit"] 	= $field_data["unit"];
							$result["value"] 	= number_format($data[$field]["values"][0], 0, ",", ".");
						} else {
							$result["type"] 	= $field_data["type"];
							$result["caption"] 	= $field_data["caption"];
							$result["unit"] 	= $field_data["unit"];
							$result["value"]	= $data[$field]["values"][0];
						}

					}
					break;

				case "image":
					foreach($data[$field]["values"] as $element => $element_value)
					{
						$result[$element]["type"] = $field_data["type"];
						$result[$element]["caption"] = $field_data["caption"];
						$result[$element]["value"] = $element_value["uri"];
					}
					break;

				case "currence":
					
					if(isset($data[$field]["values"][0]["from"])=== true)
					{
						if(empty($data[$field]["values"][0]["to"])or ($data[$field]["values"][0]["from"] == $data[$field]["values"][0]["to"]))
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["value"] = (!empty($data[$field]["values"][0]["from"]))?  number_format($data[$field]["values"][0]["from"],2,",","."):"";
						}
						else
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["from"] = (!empty($data[$field]["values"][0]["from"]))? number_format($data[$field]["values"][0]["from"],2,",","."):"";
							$result["to"] = (!empty($data[$field]["values"][0]["to"]))? number_format($data[$field]["values"][0]["to"],2,",","."):"";
						}
					}
					else
					{
						if(is_numeric($data[$field]["values"][0]) === true)
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["value"] = number_format($data[$field]["values"][0],2,",",".");
						}
						else
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["unit"]    = $field_data["unit"];
							$result["value"] = $data[$field]["values"][0];
						}

					}
					break;

				case "date":
						if(!empty($data[$field]["values"][0]+7200))
						{
							$result["type"] = $field_data["type"];
							$result["caption"] = $field_data["caption"];
							$result["value"] = gmdate("d.m.Y", (int)$data[$field]["values"][0]+7200);
						}
					break;

				case "option":
						if(!empty($data[$field]["values"][0]) or $data[$field]["values"][0] == "0")
						{
							foreach($data[$field]["values"] as $key => $val)
							{
								$result["type"] = $field_data["type"];
								$result["caption"] = $field_data["caption"];

                                if(!empty( $field_data["option"][$data[$field]["values"][$key]])) {
                                    $result["value"][$key] =  $field_data["option"][$data[$field]["values"][$key]];
                                } else {
                                    $result["value"][$key] =  "";
                                }
							}
						}
					break;

				case "yesno":
					if($data[$field]["values"][0] == 1)
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
					$result["value"] = $data[$field]["values"][0];
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

    function fetch_shape_data($latitude, $longitude) {
        $url = 'https://box-shp.alias.s24cloud.net/shape?latitude=' . $latitude . '&longitude=' . $longitude . '&types=region,city,district,borough,town';
    
        $response = wp_remote_get($url);
    
        if (is_wp_error($response)) {
            // Handle error
            return false;
        }
    
        $body = wp_remote_retrieve_body($response);
    
        // Process the retrieved data
        // ...
    
        return $body;
    }
}

//ob_get_clean();
if (class_exists('API')) {
    $API = new API();
}
