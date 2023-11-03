<?php 


	// load module core
	require_once( plugin_dir_path(__FILE__) .'/core.php');

	// Widget Functions
	class FFrecommendationWidget extends WP_Widget  {

		// class constructor
		public function __construct() {
			$widgetOps = array(
				'classname' 	=> 'FFrecommendation',
				'description' 	=> 'Zur Einbindung von Videobewertung aus dem FLOWFACT Saleautomat',
			);
			parent::__construct('FFrecommendation', 'FLOWFACT - Videobewertung', $widgetOps );
		}

		// output the widget content on the front-end
		public function widget( $args, $instance ) {
			
			// init module core
			$FFrecommendationWidget = new FFrecommendationWidget();
			echo $FFrecommendationWidget->widget($args, $instance);
		}

		// output the option form field in admin Widgets screen
		public function form( $instance )
		{}

		// save options
		public function update( $new_instance, $old_instance )
		{}
	}

	
	// create Shortcode
	function FFrecommendationShortcode( $atts ) {
		// defaults
		/*$a = shortcode_atts( array(
		  'name' => 'world'
	    ), $atts );*/
	   
	    // get addin
		$FFrecommendationCore = new FFrecommendationCore();
		$data = $FFrecommendationCore->get_recommendation_overview();
		return $data['content'];
	}
	
	add_shortcode( 'ff_recommendation_shortcode', 'FFrecommendationShortcode' );
