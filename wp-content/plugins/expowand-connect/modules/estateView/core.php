<?php
include_once __DIR__."/../../core/dict.php";
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
		// print_r($inquiry);exit;
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

		// print("<pre>".print_r($data,true)."</pre>");exit;
		$html = '';
		ob_start();
		$results = $data;
		include($path . '/' . $page . '.php');
		$html = ob_get_contents();
		ob_end_clean();
		// print("<pre>".print_r($html,true)."</pre>");exit;
		return $html;
    }

   
	protected function loadCss($theme = 'default') {
		// JS
		wp_register_script('BS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js');
		wp_enqueue_script('BS');

		// CSS
		wp_register_style('BS', 'https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css');
		wp_enqueue_style('BS');

		// force load Jquery
		wp_enqueue_script( 'jquery');    
		
        // // load slick css
        // wp_register_style('FF-EstateView-slick-' . $theme, plugins_url('/assets/css/' . $theme . '/slick.css', __FILE__) , '', '1.0.0', false);
        // wp_enqueue_style('FF-EstateView-slick-' . $theme);

        // // load icons css
        // wp_register_style('FF-EstateView-icons-' . $theme, plugins_url('/assets/css/' . $theme . '/flaticon.css', __FILE__) , '', '1.0.0', false);
        // wp_enqueue_style('FF-EstateView-icons-' . $theme);

        // // load chosen css
        // wp_register_style('FF-EstateView-chosen-' . $theme, plugins_url('/assets/css/' . $theme . '/chosen.min.css', __FILE__) , '', '1.0.0', false);
        // wp_enqueue_style('FF-EstateView-chosen-' . $theme);

        // // load slick theme css
        // wp_register_style('FF-EstateView-slick-theme-' . $theme, plugins_url('/assets/css/' . $theme . '/slick-theme.css', __FILE__) , '', '1.0.0', false);
        // wp_enqueue_style('FF-EstateView-slick-theme-' . $theme);

        // // load thickbox from wp
        // wp_enqueue_style('thickbox', '/' . WPINC . '/js/thickbox/thickbox.css', null, '1.0');
        // wp_enqueue_script('thickbox');
        // wp_enqueue_style('thickbox');

        // // load default css
        // wp_register_style('FF-EstateView-Styles-' . $theme, plugins_url('/assets/css/' . $theme . '/ff-estateview-styles.css', __FILE__) , '', '1.0.2', false);
        // wp_enqueue_style('FF-EstateView-Styles-' . $theme);

        // // load slick js
        // wp_register_script('FF-EstateView-slick-' . $theme, plugins_url('/assets/js/' . $theme . '/slick.js', __FILE__) , '', '1.0.0', true);
        // wp_enqueue_script('FF-EstateView-slick-' . $theme);

        // // load chosen
        // wp_register_script('FF-EstateView-chosen-' . $theme, plugins_url('/assets/js/' . $theme . '/chosen.jquery.min.js', __FILE__) , '', '1.0.0', true);
        // wp_enqueue_script('FF-EstateView-chosen-' . $theme);

        // // load default js
        // wp_register_script('FF-EstateView-Script-' . $theme, plugins_url('/assets/js/' . $theme . '/ff-estateview-script.js', __FILE__) , '', '1.0.2', true);
        // wp_enqueue_script('FF-EstateView-Script-' . $theme);

        // wp_localize_script('FF-EstateView-Script-' . $theme, 'ffdata', array(
        //     'ajaxurl' => admin_url('admin-ajax.php')
        // ));

        // load Slider Pro
        // css 
        // wp_register_style('FF-EstateView-slider-pro', plugins_url('/assets/css/slider-pro-master/slider-pro.min.css', __FILE__) , '', '1.0.0', false);
        // wp_enqueue_style('FF-EstateView-slider-pro');

        // JS
		// wp_register_script('FF-EstateView-slider-pro-js', plugins_url('/assets/js/slider-pro-master/jquery.sliderPro.min.js', __FILE__) , '', '1.0.0', true);
        // wp_enqueue_script('FF-EstateView-slider-pro-js');

        // Leaflet Maps
        // CSS
        // wp_register_style('leaflet', 'https://unpkg.com/leaflet@1.7.1/dist/leaflet.css',true);
        // wp_enqueue_style('leaflet');

        // // JS
 		// wp_register_script('leaflet','https://unpkg.com/leaflet@1.7.1/dist/leaflet.js', array('jquery'), '3.3.5', true );
 		// wp_enqueue_script( 'leaflet' );
    }

	protected function sync_agent($agent_id){
		$upload_dir = wp_upload_dir();
		$upload_path = $upload_dir['basedir'];
		$agent = API::get_agent_data($agent_id);
		$this->set_entity_cache('agent_'.$agent->id, 'agent', $agent);
		if(is_dir($upload_path . '/agents/' . $agent->id)){
			$files = glob($upload_path . '/agents/' . $agent->id . '/*'); // get all file names
			foreach($files as $file){ // iterate files
				if(is_file($file))
					unlink($file); // delete file
			}
		}else{
			mkdir($upload_path . '/agents/' . $agent->id, 0777, true);
		}
		$perphoto_url = EW_BASE_URL.'/expose/persphoto/'.$agent->id;
		$perphoto_path = $upload_path . '/agents/' . $agent->id . '/' . $agent->persphoto;
		file_put_contents($perphoto_path, file_get_contents($perphoto_url));
		$logo_url = EW_BASE_URL.'/expose/logo/'.$agent->id;
		$logo_path = $upload_path . '/agents/' . $agent->id . '/' . $agent->logo;
		file_put_contents($logo_path, file_get_contents($logo_url));
		return $agent;
	}

}
