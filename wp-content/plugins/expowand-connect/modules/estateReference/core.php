<?php

include_once 'dict.php';

class EWestateReferenceCore extends API
{
	// get widget
    public function widget(){
		return FF_ESTATEREFERENCE_SALESAUTOMATE_MAPPING;
	}
	
	// get overview
	public function get_estate_reference_overview(){
		$data["mapping"] = json_decode(FF_ESTATEREFERENCE_SALESAUTOMATE_MAPPING, true);
	
		// get html
		$result['title'] 	= "Immobilien Referenzen";
		$result['content'] 	= $this->get_search($data);
		return $result;
	}
	
	// get search	
	protected function get_search($data = NULL, $page = 1, $max_results = FF_ESTATEREFERENCE_MAX_RESULT) {
		if(!empty($data)) {		
			// default
			$data["search"]["search"]["schema"] = "default";
	
			// get search params
			if( !empty($_GET))	{	
				foreach($_GET as $key => $value){
					$data["search"]["search"][sanitize_text_field($key)] = esc_html(sanitize_text_field($value));
				}
			}
			// get list
			$data = $this->get_search_result($data);
			// get html
			return $this->get_html("page-overview", FF_ESTATEVIEW_THEME, $data);

		}else{
			return;
		}	
	}
	
	// return search
    protected function get_search_result($data = NULL, $page = 1, $max_results = FF_ESTATEREFERENCE_MAX_RESULT) {
		// print("<pre>".print_r($data,true)."</pre>");exit;
		if (!empty($data["search"]["search"])){

				$schemaId = "estates";

				// set page
				if(!empty($data["search"]["search"]["ffpage"]))
				{
					$page = $data["search"]["search"]["ffpage"];
				}
				
				// get search query
				$search = $this->get_search_query($data);

				$result = API::get_entities_by_search($schemaId , $search ,$max_results, $page);
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
				$data["search"]["path"]		    	= get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_ESTATEREFERENCE_ROUTE;
				$data["color"]["primary"]			= FF_PRIMARY_COLOR;
				$data["color"]["secondary"]			= FF_SECONDARY_COLOR;
				$data["api"]["cloudimage"]["url"] 	= FF_CLOUDIMAGE_IO_URL;
				$data["api"]["maps"]["key"]			= FF_GG_API_MAPS;
				$data["api"]["maps"]["path"]		= plugin_dir_url( dirname( __FILE__ ) )."/estateView/assets/img/".FF_ESTATEVIEW_THEME."/";
				
				// change view to frame if set
				// if(!empty($_GET["iframe"]) and $_GET["iframe"] == "1")
				// {
				// 	$data["frame"]	== "1";
				// }

				// print("<pre>".print_r($data,true)."</pre>");exit;
				
				return $data; 
      }
    }
	
	 // return search_query
    protected function get_search_query($data = NULL){
		if(!empty($data))
		{
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

	protected function getFields($data = NULL, $schema = NULL, $results = NULL){
		if(!empty($data) && !empty($schema) && !empty($results))
		{
			foreach($data as $key => $row)
			{
				foreach ($results as $result)
				{			
					//  print_r($result['offerdetails']);exit;
					if($schema == $key)
					{
						foreach($row as $key2 => $row2)
						{
							// print_r($key2);
							foreach($row2 as $key3 => $row3)
							{
								// print_r($key3);
								// print_r($row3);exit;
								if(!empty($row3["type"]))
								{	
									if(isset($result['offerdetails'][$key3])){
										if(!empty($result['offerdetails'][$key3]))
										{
										// print_r($key3);
										// print_r($row3);
										// print_r($result);
										// print_r($key2);
											$field[$result["offer"]['id']][$key2][$key3] = API::get_formated_fields($key3,$row3, $result, $key2);
										}
									}
									else if(isset($result['offer'][$key3]))
									{
										if(!empty($result['offer'][$key3]))
										{
											$field[$result["offer"]['id']][$key2][$key3] = API::get_formated_fields($key3,$row3, $result, $key2);
										}
									}
								}
							}
						}
					}
				}
			}
			return $field;
		}
		return null;
	}
	
	// render template
    protected function get_html($page = NULL, $template = "default", $data = NULL) {
		// load module assets
        $this->loadCss($template);
		
        if (!empty($data) && !empty($page)) {
            // set path
            $path = plugin_dir_path(__FILE__) . "templates/" . $template;

            if (file_exists($path . '/' . $page . '.html')) {
				// print("<pre>".print_r($data,true)."</pre>");exit;
				$html = '';
				ob_start();
				$results = $data['search']['results'];
				include($path . '/' . $page . '.php');
				$html = ob_get_contents();
				ob_end_clean();
				// print("<pre>".print_r($html,true)."</pre>");exit;
                return $html;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
		
    // load css for plugin
    protected function loadCss($theme = 'default')
    {

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
