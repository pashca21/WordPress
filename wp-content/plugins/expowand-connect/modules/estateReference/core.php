<?php

include_once __DIR__."/../../core/constants/dict.php";

class EWestateReferenceCore extends API {

	public function get_estate_reference_overview(){
		// syncronize database if needed
		$this->sync_db();
		$data = [];
		$result['title'] 	= "Immobilien Referenzen";
		$result['content'] 	= $this->get_search($data);
		return $result;
	}
	
	protected function get_search($data = NULL, $page = 1, $max_results = EW_ESTATEREFERENCE_MAX_RESULT) {
		$data["search"]['type'] 		= -1;
		$data["search"]['category'] 	= '';
		$data["search"]['sort'] 		= 'ID_DESC';
		$data["search"]['page_number'] 	= 1;
		if(!empty($_GET)){	
			foreach($_GET as $key => $value){
				$data["search"][sanitize_text_field($key)] = esc_html(sanitize_text_field($value));
			}
		}
		$data = $this->get_search_result($data);
		return $this->render_html("page-overview", EW_ESTATEVIEW_THEME, $data);
	}
	
    protected function get_search_result($data = NULL, $page = 1, $max_results = EW_ESTATEREFERENCE_MAX_RESULT) {
		if (empty($data["search"])){ return false; }
		if(!empty($data["search"]["page_number"])){
			$page = $data["search"]["page_number"];
		}
		global $wpdb;
		$query = "SELECT json FROM {$wpdb->prefix}ew_entity_cache WHERE schemaId='offer' ";
		if($data["search"]["type"] != -1){
			$query .= "AND json LIKE '%\"type\":\"".$data["search"]["type"]."\"%' ";
		}
		if($data["search"]["category"] != ''){
			$query .= "AND json LIKE '%\"category\":\"".$data["search"]["category"]."\"%' ";
		}
		$results_offers = $wpdb->get_results($query);
		$estates = [];
		$sort = $data["search"]["sort"];
		foreach($results_offers as $key => $value){
			$estate = new stdClass();
			$estate->offer = json_decode($value->json);
			$estate->offerdetails =  $this->get_entity_cache('offerdetails_'.$estate->offer->id);

			if($sort == 'PRICE_ASC' || $sort == 'PRICE_DESC'){
				if($estate->offer->type == 1){
					$estate->sort_field = $estate->offerdetails->baseRent;
				}else{
					$estate->sort_field = $estate->offer->immoprice;
				}
			}elseif($sort == 'AREA_ASC' || $sort == 'AREA_DESC'){
				if($estate->offer->category=='APARTMENT' || $estate->offer->category=='HOUSE' || $estate->offer->category=='APARTMENT_INT' || $estate->offer->category=='HOUSE_INT'){
					$estate->sort_field = $estate->offerdetails->livingSpace;
				}elseif($estate->offer->category=='LIVING_SITE' || $estate->offer->category=='LIVING_SITE_INT'){
					$estate->sort_field = $estate->offerdetails->plotArea;
				}elseif($estate->offer->category=='OFFICE'){
					$estate->sort_field = $estate->offerdetails->netFloorSpace;
				}else{ // all other Business Categories
					$estate->sort_field = $estate->offerdetails->totalFloorSpace;
				}
			}

			$estates[] = $estate;
		}
		if($sort == 'PRICE_ASC' || $sort == 'AREA_ASC'){
			usort($estates, function($a, $b) {
				return $a->sort_field <=> $b->sort_field;
			});
		}elseif($sort == 'PRICE_DESC' || $sort == 'AREA_DESC'){
			usort($estates, function($a, $b) {
				return $b->sort_field <=> $a->sort_field;
			});
		}elseif($sort == 'ID_DESC'){
			usort($estates, function($a, $b) {
				return $b->offer->id <=> $a->offer->id;
			});
		}else{					
			usort($estates, function($a, $b) {
				return $a->offer->id <=> $b->offer->id;
			});
		}

		$data["search"]["estates"]		= $estates;
		$data["search"]["total_count"]	= count((array) $estates);
		$data["search"]["page_max"] 	= ceil(count((array) $estates)/$max_results);
		$data["search"]["page"] 		= ($page>$data["search"]["page_max"])?$data["search"]["page_max"]:$page;
		$data["search"]["path"]		    = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . EW_ESTATEREFERENCE_ROUTE;
		$data["color"]["primary"]		= EW_PRIMARY_COLOR;
		$data["color"]["secondary"]		= EW_SECONDARY_COLOR;
		return $data; 
    }
	
    protected function render_html($page = NULL, $template = "default", $data = NULL) {
        if (empty($data) || empty($page)) { return false; }

		$this->loadCss($template);
		$path = plugin_dir_path(__FILE__) . "templates/" . $template;

        if (!file_exists($path . '/' . $page . '.php')) { return false; }

		$html = '';
		ob_start();
		$rows_per_page = EW_ESTATEREFERENCE_MAX_RESULT;
		$estates_total = $data['search']['estates'];
		$search = $data['search'];
		$list = new stdClass();
		$list->type 	= $search['type'];
		$list->category = $search['category'];
		$list->sort		= $search['sort'];
		$list->rows 	= count((array) $estates_total);
		$list->page		= $search['page'];
		$list->pages 	= ceil(count((array) $estates_total)/$rows_per_page);
		$list->records	= count((array) $estates_total);
		$offset = ($list->page - 1) * $rows_per_page ;
		if($offset < 0){
			$offset = 0;
		}
		$list->record_from = $offset + 1;
		$list->record_to = $list->record_from + $rows_per_page - 1;
		$estates = array_slice($estates_total, $list->record_from-1, $rows_per_page);
		include($path . '/' . $page . '.php');
		$html = ob_get_contents();
		ob_end_clean();
		return $html;
    }
		
    protected function loadCss($theme = 'default') {
		// Boostrap
		// JS
		wp_register_script('BS', plugins_url('/assets/js/bootstrap.bundle.min.js', __FILE__),'','1.0.0', false);
		wp_enqueue_script('BS');
		
		// CSS
		wp_register_style('BS', plugins_url('/assets/css/bootstrap.min.css', __FILE__),'','1.0.0', false);
		wp_enqueue_style('BS');

		// force load Jquery
		wp_enqueue_script( 'jquery');

		// load default css	
		wp_register_style('EW-EstateReference-Styles-' . $theme, plugins_url('/assets/css/' . $theme . '/ew-estatereference-styles.css', __FILE__),'','1.0.0', false);
		wp_enqueue_style('EW-EstateReference-Styles-' . $theme);
			
		// load default js
		wp_register_script('EW-EstateReference-Script-' . $theme, plugins_url('/assets/js/' . $theme . '/ew-estatereference-script.js', __FILE__),'','1.0.0', true);
		wp_enqueue_script( 'EW-EstateReference-Script-' . $theme );
    }
}
