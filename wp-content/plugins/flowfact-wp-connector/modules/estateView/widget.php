<?php 


	// load module core
	require_once( plugin_dir_path(__FILE__) .'/core.php');

	// Widget View Functions
	class FFestateViewWidget extends WP_Widget  {

		// class constructor
		public function __construct() {
			$widgetOps = array(
				'classname' 	=> 'FFestateView',
				'description' 	=> 'Zur Einbindung von Immobilien aus dem FLOWFACT Saleautomat',
			);
			parent::__construct('FFestateView', 'FLOWFACT - Immobiliensuche', $widgetOps );
		}

		// output the widget content on the front-end
		public function widget( $args, $instance ) {
			// init module core
			$FFestateViewCore = new FFestateViewCore();
			$result = $FFestateViewCore->widget();
			
			// wrap widget
			extract($args, EXTR_SKIP);
			$view = $before_widget;
			$view .= $before_title . esc_html((!empty($result['title']) ? $result['title'] : "")) . $after_title;
			$view .= "<div class='ff-prospectfinder-widget'>".$result['content']."</div>";
			$view .= $after_widget;
			echo $view;

		}

		// output the option form field in admin Widgets screen
		public function form( $instance )
		{}

		// save options
		public function update( $new_instance, $old_instance )
		{}
	}
	
		
	// create Shortcode
	function FFestateViewShortcode( $atts ) {
		
		// defaults
		$atts = shortcode_atts( array(
		  'schema' => false,
		  'show_search' => "on",
		  'location' => false
	    ), $atts );
	   
	    // get addin
		$FFestateViewCore = new FFestateViewCore();
		$data = $FFestateViewCore->get_estate_overview($atts);
		return $data['content'];
	}
	add_shortcode( 'ff_estateview_shortcode', 'FFestateViewShortcode' );


	
	// create Shortcode
	function FFestateViewSliderShortcode( $atts ) {
		// defaults
		$atts = shortcode_atts( array(
		  'schema' => false
	    ), $atts );
	   
	    // get addin
		$FFestateViewCore = new FFestateViewCore();
		$data = $FFestateViewCore->get_estate_slider($atts);
		return $data['content'];

	}
	add_shortcode( 'ff_estateview_slider_shortcode', 'FFestateViewSliderShortcode' );