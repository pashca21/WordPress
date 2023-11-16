<?php

include_once __DIR__."/../../core/dict.php";

class EWestateReferenceCore extends API {
	// get widget
    public function widget(){
		return FF_ESTATEREFERENCE_SALESAUTOMATE_MAPPING;
	}
	
	// get Immo List
	public function get_estate_reference_overview(){
		$data["mapping"] = json_decode(FF_ESTATEREFERENCE_SALESAUTOMATE_MAPPING, true);
	
		// get html
		$result['title'] 	= "Immobilien Referenzen";
		$result['content'] 	= $this->get_search($data);
		return $result;
	}
	
	// get search	
	protected function get_search($data = NULL, $page = 1, $max_results = EW_ESTATEREFERENCE_MAX_RESULT) {
		if(!empty($data)) {		
			// default
			$data["search"]["search"]["schema"] = "default";
	
			if( !empty($_GET))	{	
				foreach($_GET as $key => $value){
					$data["search"]["search"][sanitize_text_field($key)] = esc_html(sanitize_text_field($value));
				}
			}
			// print_r($data);exit;
			$data = $this->get_search_result($data);
			// $data['list']['type'] = 0;
			// $data['list']['category'] = 'APARTMENT';
			return $this->render_html("page-overview", EW_ESTATEVIEW_THEME, $data);

		}else{
			return;
		}	
	}
	
	// return search
    protected function get_search_result($data = NULL, $page = 1, $max_results = EW_ESTATEREFERENCE_MAX_RESULT) {
		// print("<pre>".print_r($data,true)."</pre>");exit;

		if (!empty($data["search"]["search"])){
				if(!empty($data["search"]["search"]["page"])){
					$page = $data["search"]["search"]["page"];
				}
				
				// get search query
				$search = $this->get_search_query($data);

				$lastChangeDateEW = API::get_last_change_date();
				$lastChangeDateWP = '2023-01-21 01:02:03'; // TODO: save and get from DB

				// print("<pre>".print_r($lastChangeDateEW,true)."</pre>");exit;

				$result = API::get_entities_by_search($search, $max_results, $page);
				// print_r($result); exit;

				$data["search"]["total_count"]	= $result->totalCount;
				$data["search"]["page_max"] 	= ceil($result->totalCount/$max_results);
				$data["search"]["page"] 		= $page;
				
				// get immos
				// print("<pre>".print_r($data,true)."</pre>");exit;
				// $result = $this->getFields($data["mapping"]["list"], $data["search"]["search"]["schema"], $result["immos"]);
				// $multimedia = array("entities" => array("0" => array("assignments" => array())));

				// print("<pre>".print_r($result,true)."</pre>");exit;
				// if (!empty($result)) {
				// 		// attached new multimedia images to entity
				// 		$multimedia = API::get_estate_images(array_keys($result));
				// }

				// // create new multimedia array with main images & IDs
				// $main_imageArray = [];
				// foreach($multimedia["entities"] as $entity) {
				// 	if(!empty($entity["assignments"]["main_image"][0]["multimedia"]["entityId"])) {
				// 		$id = $entity["assignments"]["main_image"][0]["multimedia"]["entityId"];
				// 	}
				// 	if(!empty($entity["assignments"]["main_image"][0]["multimedia"]["fileReference"])) {
				// 		$main_imageArray[$id] = $entity["assignments"]["main_image"][0]["multimedia"]["fileReference"];
				// 	}					
				// };

				// // assign main image url to results
				// foreach($main_imageArray as $key => $mainImage) {
				// 	$result[$key]["mainImage"]["mainImage"][0]["value"] = $mainImage;
				// }
					
				$data["search"]["results"]			= $result;
				$data["search"]["path"]		    	= get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . EW_ESTATEREFERENCE_ROUTE;
				$data["color"]["primary"]			= FF_PRIMARY_COLOR;
				$data["color"]["secondary"]			= FF_SECONDARY_COLOR;
				$data["api"]["cloudimage"]["url"] 	= FF_CLOUDIMAGE_IO_URL;
				$data["api"]["maps"]["key"]			= FF_GG_API_MAPS;
				$data["api"]["maps"]["path"]		= plugin_dir_url( dirname( __FILE__ ) )."/estateView/assets/img/".EW_ESTATEVIEW_THEME."/";
				
				// print("<pre>".print_r($data,true)."</pre>");exit;
				
				return $data; 
      }
    }
	
	 // return search_query
    protected function get_search_query($data = NULL){
		if(!empty($data)) {
			$search["target"] = "ENTITY";
			$search["fetch"] = array();
			
			foreach ($data["mapping"]["list"] as $key => $schema) {
				
				if($key == $data["search"]["search"]["schema"]) 
				{
					array_push($search["fetch"], "id");
					array_push($search["fetch"], "_metadata");
					
					foreach ( $schema as $fields) {
						foreach ( $fields as $key2 => $field) 
						{
							array_push($search["fetch"], $key2);
						}
					}
				}	
			}

			// check if publish flag set
			$search["conditions"][0]["type"] = "HASFIELDWITHVALUE";
			$search["conditions"][0]["field"] = FF_ESTATEREFERENCE_SALESAUTOMATE_PUBLISH_FLAG;
			$search["conditions"][0]["value"] = true;
			
			// sorting search 
			if(!empty($data["mapping"]["sort"])) 
			{
			    // if array element does not exist, initialize it as empty array
			    if(!isset( $data["search"]["search"]["sort"])) {
                     $data["search"]["search"]["sort"] =  array();
                }
			
				$sort = $data["search"]["search"]["sort"];

				if(!empty($sort))
				{
					if(!empty($data["mapping"]["sort"]["default"][$sort]))
					{	
						$a = 0;
						foreach($data["mapping"]["sort"]["default"][$sort]["fields"] as $field)
						{
							$search["sorts"][$a]["field"] 		= $field["field"];		
							$search["sorts"][$a]["direction"] 	= $field["sort"];	
							
							$a++;
						}	
					}
				}
				else
				{

					if(!empty($data["mapping"]["sort"]["default"]))
					{	
						$a = 0;
						$sort = $data["search"]["search"]["sort"];
						if(!empty($data["search"]["search"]["sort"]))
						{
							$element = $data["mapping"]["sort"]["default"][$sort];
						}
						else
						{
						    // avoiding notice ('Notice: Only variables should be passed by reference')
						    $tmp23 = array_slice($data["mapping"]["sort"]["default"], 0, 1);
							$element = array_shift($tmp23);
						}	
						
						foreach($element["fields"] as $field)
						{
							$search["sorts"][$a]["field"] 		= $field["field"];		
							$search["sorts"][$a]["direction"] 	= $field["sort"];	
							
							$a++;
						}	
					}
				}	
				
			}
			return $search;
		}
    }

    protected function render_html($page = NULL, $template = "default", $data = NULL) {
        if (!empty($data) && !empty($page)) {
			$this->loadCss($template);
			$path = plugin_dir_path(__FILE__) . "templates/" . $template;

            if (file_exists($path . '/' . $page . '.php')) {
				$html = '';
				ob_start();
				$results = $data['search']['results'];
				$search = $data['search']['search'];
				$list = new stdClass();
				$list->type = $search['type'];
				$list->category = $search['category'];
				include($path . '/' . $page . '.php');
				$html = ob_get_contents();
				ob_end_clean();
                return $html;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
		
    // load css for plugin
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
