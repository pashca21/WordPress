<?php 


	// load module core
	require_once( plugin_dir_path(__FILE__) .'/core.php');

	// Widget Functions
	class FFvaluationMasterWidget extends WP_Widget  {

		// class constructor
		public function __construct() {
			$widgetOps = array(
				'classname' 	=> 'FFvaluationMaster',
				'description' 	=> 'Zur Einbindung des Lead-Hunters in Ihr Wordpress',
			);
			parent::__construct('FFvaluationMaster', 'FLOWFACT - Lead-Hunter', $widgetOps );
		}

		// output the widget content on the front-end
		public function widget( $args, $instance ) {
			
			// init module core
			$FFvaluationMasterCore = new FFvaluationMasterCore();
			$result = $FFvaluationMasterCore->get_overview();
			
			// wrap widget
			extract($args, EXTR_SKIP);
			$view = $before_widget;
			$view .= $before_title . esc_html((!empty($result['title']) ? $result['title'] : "")) . $after_title;
			$view .= "<div class='ff-valuationMaster-widget'>".$result['content']."</div>";
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
	function FFvaluationMasterShortcode( $atts ) {
   
	    // get addin
		$FFvaluationMasterCore = new FFvaluationMasterCore();
		$data = $FFvaluationMasterCore->get_overview();
		return $data['content'];
	}
	
	add_shortcode( 'ff_valuationmaster_shortcode', 'FFvaluationMasterShortcode' );
