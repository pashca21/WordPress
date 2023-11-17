<?php

include_once __DIR__."/../../core/dict.php";

class EWestateReferenceCore extends API {

	public function widget(){
		return FF_ESTATEREFERENCE_SALESAUTOMATE_MAPPING;
	}
	
	public function get_estate_reference_overview(){
		// check if need to sync database
		$lastChangeDateEW = API::get_last_change_date();
		$lastChangeDateWP = $this->get_general_cache('lastChangeDateWP');
		// print("<pre>".print_r($lastChangeDateWP,true)."</pre>");exit;

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
					$this->set_entity_cache('offer_'.$offer->id, 'offer', json_encode($offer));
					$this->set_entity_cache('offerdetails_'.$offer->id, 'offerdetails', json_encode($offerdetails));
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

		$data = [];
		$result['title'] 	= "Immobilien Referenzen";
		$result['content'] 	= $this->get_search($data);
		return $result;
	}
	
	protected function get_search($data = NULL, $page = 1, $max_results = EW_ESTATEREFERENCE_MAX_RESULT) {
		if(empty($_GET)){	
			$data["search"]['type'] 	= -1;
			$data["search"]['category'] = '';
		}else{
			foreach($_GET as $key => $value){
				$data["search"][sanitize_text_field($key)] = esc_html(sanitize_text_field($value));
			}
		}
		$data = $this->get_search_result($data);
		return $this->render_html("page-overview", EW_ESTATEVIEW_THEME, $data);
	}
	
    protected function get_search_result($data = NULL, $page = 1, $max_results = EW_ESTATEREFERENCE_MAX_RESULT) {
		// print("<pre>".print_r($data,true)."</pre>");exit;
		if (!empty($data["search"])){
				if(!empty($data["search"]["page"])){
					$page = $data["search"]["page"];
				}
				global $wpdb;
				$results_offers = $wpdb->get_results("SELECT json FROM {$wpdb->prefix}ew_entity_cache WHERE schemaId='offer' "); //AND json LIKE '%\"type\":1%'
				$estates = [];
				foreach($results_offers as $key => $value){
					$estate = new stdClass();
					$estate->offer = json_decode($value->json);
					$offerdetails = $wpdb->get_var("SELECT json FROM {$wpdb->prefix}ew_entity_cache WHERE schemaId='offerdetails' AND entityId='offerdetails_".$estate->offer->id."'");
					$offerdetails = str_replace("\r\n", '\r\n', $offerdetails);
					$offerdetails = str_replace("\n", '\n', $offerdetails);
					$offerdetails = str_replace("\r", '\r', $offerdetails);
					// $offerdetails = preg_replace('/[[:cntrl:]]/', '', $offerdetails);
					$estate->offerdetails = json_decode($offerdetails);
					// if(empty($estate->offerdetails)) {
					// 	echo $estate->offer->id;
					// 	print_r($offerdetails);
					// 	exit;
					// }
					$estates[] = $estate;
				}
				// print("<pre>".print_r($estates,true)."</pre>");exit;

				$data["search"]["estates"]		= $estates;
				$data["search"]["total_count"]	= 777;
				$data["search"]["page_max"] 	= ceil(777/$max_results);
				$data["search"]["page"] 		= $page;
				$data["search"]["path"]		    = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . EW_ESTATEREFERENCE_ROUTE;
				$data["color"]["primary"]		= FF_PRIMARY_COLOR;
				$data["color"]["secondary"]		= FF_SECONDARY_COLOR;
				// print("<pre>".print_r($data,true)."</pre>");exit;
				return $data; 
      }
    }
	
    protected function render_html($page = NULL, $template = "default", $data = NULL) {
		// print("<pre>".print_r($data,true)."</pre>");exit;

        if (empty($data) || empty($page)) { return false; }

		$this->loadCss($template);
		$path = plugin_dir_path(__FILE__) . "templates/" . $template;

        if (!file_exists($path . '/' . $page . '.php')) { return false; }

		$html = '';
		ob_start();
		$estates = $data['search']['estates'];
		$search = $data['search'];
		$list = new stdClass();
		$list->type = $search['type'];
		$list->category = $search['category'];
		include($path . '/' . $page . '.php');
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
    }
		
    protected function loadCss($theme = 'default') {
		// JS
		wp_register_script('BS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js');
		wp_enqueue_script('BS');

		// CSS
		wp_register_style('BS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
		wp_enqueue_style('BS');

		// load default css	
		// wp_register_style('FF-EstateReference-Styles-' . $theme, plugins_url('/assets/css/' . $theme . '/ff-estatereference-styles.css', __FILE__),'','1.0.0', false);
		// wp_enqueue_style('FF-EstateReference-Styles-' . $theme);

		// force load Jquery
		wp_enqueue_script( 'jquery');    
			
		// load default js
		// wp_register_script('FF-EstateReference-Script-' . $theme, plugins_url('/assets/js/' . $theme . '/ff-estatereference-script.js', __FILE__),'','1.0.0', true);
		// wp_enqueue_script( 'FF-EstateReference-Script-' . $theme );
    }
}
