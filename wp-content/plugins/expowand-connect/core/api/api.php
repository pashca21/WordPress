<?php

class API {	 

    protected function sync_db(){
        // check if need to sync database
		$lastChangeDateEW = API::get_last_change_date();
		$lastChangeDateWP = $this->get_general_cache('lastChangeDateWP');
		// print("<pre>".print_r($lastChangeDateEW,true)."</pre>");exit;

		if($lastChangeDateWP == false || $lastChangeDateWP == '0000-00-00 00:00:00' || $lastChangeDateWP < $lastChangeDateEW){
			$response = API::get_offers_list_sync($lastChangeDateWP);
			// print("<pre>".print_r($response,true)."</pre>");exit;
			if(isset($response->estates)){
				foreach($response->estates as $key => $estate){
					$offer = $estate->offer;
					$offerdetails = $estate->offerdetails;
					$upload_dir = wp_upload_dir();
					$upload_path = $upload_dir['basedir'];
					if($offer->active == 0) {
						$this->delete_entity_cache('offer_'.$offer->id);
						$this->delete_entity_cache('offerdetails_'.$offer->id);	
						if(is_dir($upload_path . '/estates/' . $offer->id)){
							$files = glob($upload_path . '/estates/' . $offer->id . '/*'); // get all file names
							foreach($files as $file){ // iterate files
								if(is_file($file))
									unlink($file); // delete file
							}
							rmdir($upload_path . '/estates/' . $offer->id);
						}
						continue;
					}
					$this->set_entity_cache('offer_'.$offer->id, 'offer', $offer);
					$this->set_entity_cache('offerdetails_'.$offer->id, 'offerdetails', $offerdetails);
					if(is_dir($upload_path . '/estates/' . $offer->id)){
						$files = glob($upload_path . '/estates/' . $offer->id . '/*'); // get all file names
						foreach($files as $file){ // iterate files
							if(is_file($file))
								unlink($file); // delete file
						}
					}else{
						mkdir($upload_path . '/estates/' . $offer->id, 0777, true);
					}
					foreach($offerdetails->pictures as $pic){
						$pic_url = EW_BASE_URL.'/www/pictures/'.$offer->id.'/'.$pic->filename;
						$pic_path = $upload_path . '/estates/' . $offer->id . '/' . $pic->filename;
						file_put_contents($pic_path, file_get_contents($pic_url));
					}

				}
			}
		}
		$this->set_general_cache('lastChangeDateWP', date('Y-m-d H:i:s'));
    }

    protected function get_agent_data($agent_id) {
		$token  = API::get_token();
		$url    = EW_API_AGENT . '/' . $agent_id;
        if (empty($token) || empty($url)) { return false; }
		$result = API::get_content($token, $url);
		if(empty($result)) { return false; }
		return $result;
    }

    protected function get_agent_last_change($agent_id){
		$token  = API::get_token();
		$url    = EW_API_AGENT_LAST_CHANGE . '/' . $agent_id;
        if (empty($token) || empty($url)) { return false; }
		$result = API::get_content($token, $url);
		if(empty($result)) { return false; }
		return $result;
    }

    protected function get_last_change_date() {
        $token  = API::get_token();
        $url    = EW_API_OFFERS_LAST_CHANGE;
        if (empty($token) || empty($url)) { return false; }
        $args = array(
            'timeout' => 60,
            'redirection' => 60,
            'httpversion' => '1.0',
            'user-agent' => 'EW WEBMODULE -' . home_url(),
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
        if($result instanceof WP_Error) { return false; }
        if (empty($result) || $result['response']['code'] != 200) { return false; }
        return json_decode($result['body']);
    }

    protected function get_offers_list_sync($lastChangeDateWP) {
        $token  = API::get_token();
        $url    = EW_API_OFFERS_LIST_SYNC;
        if (empty($token) || empty($url)) { return false; }
		$url .= '/'.strtotime($lastChangeDateWP);
        $args = array(
            'timeout' => 60,
            'redirection' => 60,
            'httpversion' => '1.0',
            'user-agent' => 'EW WEBMODULE -' . home_url(),
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
        if($result instanceof WP_Error) { return false; }
        if (empty($result) || $result['response']['code'] != 200) { return false; }
        return json_decode($result['body']);
    }

    protected function get_token(){
        // $token = $this->get_general_cache('EW_TOKEN');
        $token = EW_TOKEN;
        if (empty($token)) {
			// set demo token
            $token = '2e876f580652a210a4a7c2e5ca7406ac';
		}	
        return $token;
    }

    protected function post_inquiry($data){
        $token  = API::get_token();
        $url    = EW_API_INQUIRY;
        if (empty($token) || empty($url)) { return false; }
        $result = API::post_content($token, $url, $data);
        return $result;
    }

	// Get content via rest
    protected function get_content($token, $url, $type = "cognitoToken") {
        $args = array(
            'timeout' => 60,
            'redirection' => 60,
            'httpversion' => '1.0',
            'user-agent' => 'EW WEBMODULE -' . home_url(),
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
        // print_r($response);exit;
        if($response instanceof WP_Error) { return false; }
        if (empty($response) || $response['response']['code'] != 200) { return false; }
        return json_decode($response['body']);
    } 
	
	// Post content via rest
    protected function post_content($token, $url, $data, $type = "cognitoToken") {
        $args = array(
            'timeout' => 60,
            'redirection' => 60,
            'httpversion' => '1.0',
            'user-agent' => 'EW WEBMODULE -' . home_url(),
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
        if($response instanceof WP_Error) { return false; }
        if ($response['response']['code'] != 200) { return false; }
        return true;
    }
	
    protected function set_general_cache($name = NULL, $data = NULL) {
        if (empty($name) || empty($data)) { return false; }
        global $wpdb;
        $sql = "INSERT INTO {$wpdb->prefix}ew_general_cache (created,name,json) VALUES (now(),'" . $name . "','" . $data . "') ON DUPLICATE KEY UPDATE created = now(), json = '" . $data . "';";
        $wpdb->query($sql);
        return;
    }

	protected function get_general_cache($name = NULL){
        if (empty($name)) { return false; }
		global $wpdb;
		$result = $wpdb->get_results("SELECT json FROM {$wpdb->prefix}ew_general_cache WHERE name = '".$name."' ");
        if (empty($result)) { return false; }
        return ($result[0]->json);
    }

	protected function delete_general_cache($name = NULL){
		if (empty($name)) { return false; }
		global $wpdb;
		$wpdb->delete( $wpdb->prefix.'ew_general_cache', array( 'name' => $name ) );
		return;
	}
	
    protected function set_entity_cache($entityId = NULL, $schemaId = NULL, $data = NULL) {
        if (empty($entityId) || empty($schemaId) || empty($data)) { return false; }
        $prepared_data = json_encode($data, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_QUOT);
        global $wpdb;
        $sql = "INSERT INTO {$wpdb->prefix}ew_entity_cache (created,entityId,schemaId,json) VALUES (now(),'" . $entityId . "','" . $schemaId . "','" . ($prepared_data) . "') ON DUPLICATE KEY UPDATE created = now(), json = '" . $prepared_data . "';";
        $wpdb->query($sql);
        return;
    }

	protected function get_entity_cache($entityId = NULL){
        if (empty($entityId)) { return false; }
		global $wpdb;
		$result = $wpdb->get_results("SELECT json FROM {$wpdb->prefix}ew_entity_cache WHERE entityId = '".$entityId."' ");
        // print_r($result);exit;
        if (empty($result)) { return false; }
        $json = ($result[0]->json);
        $json = str_replace("\r\n", '\r\n', $json);
        $json = str_replace("\n", '\n', $json);
        $json = str_replace("\r", '\r', $json);
        $json = preg_replace('/[[:cntrl:]]/', '', $json);
        return json_decode($json);
    } 

	protected function delete_entity_cache($entityId = NULL){
		if (empty($entityId)) { return false; }
		global $wpdb;
		$wpdb->delete( $wpdb->prefix.'ew_entity_cache', array( 'entityId' => $entityId ) );
		return;
	}

}

if (class_exists('API')) {
    $API = new API();
}
