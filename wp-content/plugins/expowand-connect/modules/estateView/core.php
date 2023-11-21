<?php
include_once __DIR__."/../../core/constants/dict.php";
class EWestateViewCore extends API{

	public function widget() {
		$data["search"]["path"] = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . EW_ESTATEVIEW_ROUTE;

		$result['title'] = "Immobilien";
		$result['content'] = $this->render_html("widget", EW_ESTATEVIEW_THEME, $data);
		return $result;
	}

	public function ew_add_meta_to_header() {
		if(defined('EW_META_TITLE')) {
			echo '<meta property="og:title" content="'.EW_META_TITLE.'">';
			echo '<meta property="og:image:alt" content="'.EW_META_TITLE.'">';
			echo '<meta property="twitter:title" content="'.EW_META_TITLE.'">';
		}
		
		if(defined('EW_META_DESCRIPTION')) {
			echo '<meta property="og:type" content="article" />';
			echo '<meta name="description" content="'.EW_META_DESCRIPTION.'">';
			echo '<meta property="og:description" content="'.EW_META_DESCRIPTION.'">';
			echo '<meta property="twitter:description" content="'.EW_META_DESCRIPTION.'">';
		}

		if(defined('EW_META_IMAGE')) {
			echo '<meta property="og:image" content="'.EW_META_IMAGE.'">';
			echo '<meta property="twitter:image" content="'.EW_META_IMAGE.'">';
		}
	}

	public function get_estate_details($schemaId, $id) {
		if (empty($id) || empty($schemaId)) { return false; }

		// inquiry form
		$inquiry = [];
		if(!empty($_POST)){	
			foreach($_POST as $key => $value){
				$inquiry[sanitize_text_field($key)] = esc_html(sanitize_text_field($value));
			}
		}
		$inquiry_success = false;
		if(!empty($inquiry)){
			if($this->post_inquiry($inquiry)){
				$inquiry_success = true;
			}
		}

		// syncronize database if needed
		$this->sync_db();

		$offerdetails	= $this->get_entity_cache('offerdetails_'.$id);
		$offer 			= $this->get_entity_cache('offer_'.$id);

		$agent			= $this->get_entity_cache('agent_'.$offer->agent_id);
		
		if(empty($agent)){
			$agent = $this->sync_agent($offer->agent_id);
		}else{
			$agentLastChangeEW = $this->get_agent_last_change($offer->agent_id);
			if($agentLastChangeEW > $agent->modified){
				$agent = $this->sync_agent($offer->agent_id);
			}
		}

		if (empty($offer->name)) {
			$result['title'] = "Immobilie";
			$meta_title = "Immobilie";
		} else {
			$result['title'] = $offer->name;
			$meta_title = $offer->name;
		}

		if($meta_title) {
			define('EW_META_TITLE', $meta_title);
		}
		
		$meta_description = $offer->name;

		if($meta_description) {
			define('EW_META_DESCRIPTION', $meta_description);
		}
		
		$meta_image = $offerdetails->mainPic;

		if(!empty($meta_image)) {
			define('EW_META_IMAGE', $meta_image);
		}

		if(defined('EW_META_TITLE') or defined('EW_META_DESCRIPTION') or defined('EW_META_IMAGE')) {
			add_action('wp_head', array($this, 'ew_add_meta_to_header'), -1);
		}

		$data = new stdClass();
		$data->offer 		= $offer;
		$data->offerdetails = $offerdetails;
		$data->agent		= $agent;
		$data->inquiry_success = $inquiry_success;

		$result['content'] = $this->render_html("page-details", EW_ESTATEVIEW_THEME, $data);
		return $result;
	}

    // return template
    public function render_html($page = NULL, $template = "default", $data = NULL, $path = NULL) {
        if (empty($data) && empty($page)) { return false; }
		if (empty($path)) {	
			$path = plugin_dir_path(__FILE__) . "templates/view/" . $template; 
		}
		if (!file_exists($path . '/' . $page . '.php')) {
			return false;
		}				

        $this->loadCss(EW_ESTATEVIEW_THEME);		

		$html = '';
		ob_start();
		$results = $data;
		include($path . '/' . $page . '.php');
		$html = ob_get_contents();
		ob_end_clean();
		
		return $html;
    }

   
	protected function loadCss($theme = 'default') {
		// Bootstrap
		// JS
		wp_register_script('BS', plugins_url('/assets/js/bootstrap.bundle.min.js', __FILE__),'','1.0.0', false);
		wp_enqueue_script('BS');
		
		// CSS
		wp_register_style('BS', plugins_url('/assets/css/bootstrap.min.css', __FILE__),'','1.0.0', false);
		wp_enqueue_style('BS');

		// force load Jquery
		wp_enqueue_script( 'jquery');    

        // // load default css
        wp_register_style('EW-EstateView-Styles-' . $theme, plugins_url('/assets/css/' . $theme . '/ew-estateview-styles.css', __FILE__) , '', '1.0.0', false);
        wp_enqueue_style('EW-EstateView-Styles-' . $theme);

        // // load default js
        wp_register_script('EW-EstateView-Script-' . $theme, plugins_url('/assets/js/' . $theme . '/ew-estateview-script.js', __FILE__) , '', '1.0.0', true);
        wp_enqueue_script('EW-EstateView-Script-' . $theme);

    }

}
