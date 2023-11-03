<?php 


	// load module core
	require_once( plugin_dir_path(__FILE__) .'/core.php');

	// Widget Functions
	class FFvaluationWidget extends WP_Widget  {

		// class constructor
		public function __construct() {
			$widgetOps = array(
				'classname' 	=> 'FFvaluation',
				'description' 	=> 'Zur Einbindung von Suchprofilen aus dem FLOWFACT Saleautomat',
			);
			parent::__construct('FFvaluation', 'FLOWFACT - Immobilienbewertung', $widgetOps );
		}

		// output the widget content on the front-end
		public function widget( $args, $instance ) {
			
			// init module core
			$FFvaluationCore = new FFvaluationCore();
			$result = $FFvaluationCore->widget();
			
			
			// wrap widget
			extract($args, EXTR_SKIP);
			$view = $before_widget;
			$view .= $before_title . esc_html((!empty($result['title']) ? $result['title'] : "")) . $after_title;
			$view .= "<div class='ff-valuation-widget'>".$result['content']."</div>";
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
	function FFvaluationShortcode( $atts ) {
   
	    // get addin
		$FFvaluationCore = new FFvaluationCore();
		$data = $FFvaluationCore->get_overview();
		return $data['content'];
	}
	
	add_shortcode( 'ff_valuation_shortcode', 'FFvaluationShortcode' );

