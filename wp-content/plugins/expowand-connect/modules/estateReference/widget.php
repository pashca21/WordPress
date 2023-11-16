<?php 

	// load module core
	require_once( plugin_dir_path(__FILE__) .'/core.php');

	// Widget Functions
	class EWestateReferenceWidget extends WP_Widget  {

		// class constructor
		public function __construct() {
			$widgetOps = array(
				'classname' 	=> 'FFestateReference',
				'description' 	=> 'Zur Einbindung von Immobilien Referencen aus dem Expowand',
			);
			parent::__construct('FFestateReference', 'FLOWFACT - Immobilien Referenzen', $widgetOps );
		}

		// output the widget content on the front-end
		public function widget( $args, $instance ) {
			
			// init module core
			$EWestateReferenceWidget = new EWestateReferenceWidget();
			echo $EWestateReferenceWidget->widget($args, $instance);
		}

		// output the option form field in admin Widgets screen
		public function form( $instance )
		{}

		// save options
		public function update( $new_instance, $old_instance )
		{}
	}
	
	// create Shortcode
	function EWestateReferenceShortcode( $atts ) {
		// defaults
		/*$a = shortcode_atts( array(
		  'name' => 'world'
	    ), $atts );*/
	   
	    // get addin
		$EWestateReferenceCore = new EWestateReferenceCore();
		$data = $EWestateReferenceCore->get_estate_reference_overview();
		return $data['content'];
	}
	
	add_shortcode( 'ew_estatereference_shortcode', 'EWestateReferenceShortcode' );

