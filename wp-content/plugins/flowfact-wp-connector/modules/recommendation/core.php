<?php
// get config

class FFrecommendationCore extends API
{
    public function widget()
	{
		return FF_RECOMMENDATION_SALESAUTOMATE_MAPPING;
	}
	
	public function get_recommendation_overview()
	{
		$data["mapping"] = json_decode(FF_RECOMMENDATION_SALESAUTOMATE_MAPPING, true);
	
		// get html
		$result['title'] 	= "Video Bewertung";
		$result['content'] 	= $this->get_search($data);
		return $result;
	
	}
		
	protected function get_search($data = NULL, $page = 1, $max_results = FF_RECOMMENDATION_MAX_RESULT)
	{
		if(!empty($data))
		{		
			// default
			$data["search"]["search"]["schema"] = FF_RECOMMENDATION_SALESAUTOMATE_SCHEMA;
	
			// get search params
			if( !empty($_GET))
			{	
				foreach($_GET as $key => $value){
					$data["search"]["search"][sanitize_text_field($key)] = esc_html(sanitize_text_field($value));
				}
			}

			// get list
			$data = $this->get_search_result($data);
			
			// get html
			return $this->get_html("page-overview", FF_ESTATEVIEW_THEME, $data);
		}
		else
		{
			return;
		}	
	}
	
	// return search
    protected function get_search_result($data = NULL, $page = 1, $max_results = FF_RECOMMENDATION_MAX_RESULT)
    {
        if (!empty($data["search"]["search"])){

			// set page
			if(!empty($data["search"]["search"]["ffpage"]))
			{
				$page = $data["search"]["search"]["ffpage"];
			}
			
			// get search query
			$search = $this->get_search_query($data);
		
			// get entries
			$result = API::get_entities_by_search($data["search"]["search"]["schema"] , $search ,$max_results, $page);	
			$data["search"]["results"] 		= $this->getFields($data["mapping"]["list"],$data["search"]["search"]["schema"],  $result["entries"]);
			$data["search"]["total_count"]	= $result["totalCount"];
			$data["search"]["path"]		    = get_bloginfo('wpurl') . '/' . FF_PLUGIN_ROUTE . '/' . FF_RECOMMENDATION_ROUTE;
			$data["search"]["page"] 		= $page;
			$data["search"]["page_max"] 	= ceil($result["totalCount"]/$max_results);
			$data["color"]["primary"]		= FF_PRIMARY_COLOR;
			$data["color"]["secondary"]		= FF_SECONDARY_COLOR;
			
			// change view to frame if set
			if(!empty($_GET["iframe"]) and $_GET["iframe"] == "1")
			{
				$data["frame"]	= "1";
			}
			
			return $data; 
        }
    }
	
	 // return search query
    protected function get_search_query($data = NULL)
    {
		if(!empty($data))
		{
			$search["target"] = "ENTITY";
			$search["fetch"] = array();
			
			foreach ($data["mapping"]["list"] as $key => $schema) {
				array_push($search["fetch"], "id");
				array_push($search["fetch"], "_metadata");
				
				foreach ( $schema as $fields) {
					foreach ( $fields as $key2 => $field) 
					{
						array_push($search["fetch"], $key2);
					}
				}
			}	

			// check if publish flag set
			$search["conditions"][0]["type"] = "HASFIELDWITHVALUE";
			$search["conditions"][0]["field"] = FF_RECOMMENDATION_SALESAUTOMATE_PUBLISH_FLAG;
			$search["conditions"][0]["value"] = true;	

			$search["conditions"][1]["type"] = "HASFIELD";
			$search["conditions"][1]["field"] = "creatorVideoLink";

			
			// sorting search 
			if(!empty($data["mapping"]["sort"])) 
			{

                if(!empty($data["mapping"]["sort"][$key]))
                {
                    foreach ($data["mapping"]["sort"][$key]  as $field => $sort) {
                        $search["sort"]["field"] 	 = $sort["field"];
                        $search["sort"]["direction"] = $sort["sort"];
                    }
                }

			}

			return $search;	
		}
    }

	protected function getFields($data = NULL, $schema = NULL, $results = NULL)
    {
		if(!empty($data) && !empty($schema) && !empty($results))
		{
			foreach($data as $key => $row)
			{
				foreach ($results as $result)
				{			
					if($schema == $key)
					{
						foreach($row as $key2 => $row2)
						{
							foreach($row2 as $key3 => $row3)
							{
								if(!empty($row3["type"]))
								{	
									if(!empty($result[$key3]["values"]))
									{	
										$field[$result["id"]][$key2][$key3] = API::get_formated_fields($key3,$row3, $result, $key2);
									}
								}
							}
						}	
					}
				}
			}
			return $field;
		}
	}
	
	// return template
    protected function get_html($page = NULL, $template = "default", $data = NULL)
    {
		// load module assets
        $this->loadCss($template);
		
        if (!empty($data) && !empty($page)) {
            // set path
            $path = plugin_dir_path(__FILE__) . "templates/" . $template;


            if (file_exists($path . '/' . $page . '.html')) {
                $loader = new Twig_Loader_Filesystem($path);
                $twig = new Twig_Environment($loader);
				$html = $twig->render($page . '.html', $data);
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
		// load default css	
        wp_register_style('FF-recommendation-Styles-' . $theme, plugins_url('/assets/css/' . $theme . '/ff-recommendation-styles.css', __FILE__),'','1.0.0', false);
        wp_enqueue_style('FF-recommendation-Styles-' . $theme);
		
		// load default js	
		wp_register_script('FF-recommendation-Script-' . $theme, plugins_url('/assets/js/' . $theme . '/ff-recommendation-script.js', __FILE__),'','1.0.0', true);
		wp_enqueue_script( 'FF-recommendation-Script-' . $theme );
		
		// load video js	
		wp_register_script('FF-recommendation-video-Script-' . $theme, plugins_url('/assets/js/' . $theme . '/video.js', __FILE__),'','1.0.0', true);
		wp_enqueue_script( 'FF-recommendation-video-Script-' . $theme );
    }
}
