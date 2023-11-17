<?php
include_once __DIR__."/../../core/dict.php";
class EWestateViewCore extends API{

	public function widget() {
		$data["mapping"] = json_decode(FF_ESTATEVIEW_SALESAUTOMATE_MAPPING, true);
		$data["search"]["path"] = get_bloginfo('wpurl') . '/' . EW_PLUGIN_ROUTE . '/' . FF_ESTATEVIEW_ROUTE;

		$result['title'] = "Immobilien Suche";
		$result['content'] = $this->get_html("widget", EW_ESTATEVIEW_THEME, $data);
		return $result;
	}

	public function ff_add_meta_to_header() {
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

		$offer = $this->get_entity_cache('offer_'.$id);
		$offerdetails = $this->get_entity_cache('offerdetails_'.$id);

		if (!empty($offer->name)) {
			$result['title'] = $offer->name;
		} else {
			$result['title'] = "Immobilie";
		}

		$meta_title = $offer->name;

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
			add_action('wp_head', array($this, 'ff_add_meta_to_header'), -1);
		}

		$data = new stdClass();
		$data->offer = $offer;
		$data->offerdetails = $offerdetails;

		$result['content'] = $this->get_html("page-details", EW_ESTATEVIEW_THEME, $data);
		// print_r($result);exit;

		return $result;

	}

    // return template
    public function get_html($page = NULL, $template = "default", $data = NULL, $path = NULL) {
		// print_r($data);exit;
        if (empty($data) && empty($page)) {
			return false;
		}

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

        // // load google maps js
        // if (!empty(FF_GG_API_MAPS))
        // {
        //     // load google maps cluster js
        //     wp_register_script('FF-EstateView-mapscluster-' . $theme, plugins_url('/assets/js/' . $theme . '/markerclusterer.js', __FILE__) , '', '1.0.0', true);
        //     wp_enqueue_script('FF-EstateView-mapscluster-' . $theme);

        //     wp_register_script('FF-EstateView-maps-' . $theme, 'https://maps.googleapis.com/maps/api/js?key=' . FF_GG_API_MAPS . '&libraries=places&callback=initMap', '', '1.0.0', true);
        //     wp_enqueue_script('FF-EstateView-maps-' . $theme);

        //     wp_register_script('FF-EstateView-maps-script' . $theme, plugins_url('/assets/js/' . $theme . '/map-scripts.js', __FILE__) , '', '1.0.0', true);
        //     wp_enqueue_script('FF-EstateView-maps-script' . $theme);
        // }

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

}
