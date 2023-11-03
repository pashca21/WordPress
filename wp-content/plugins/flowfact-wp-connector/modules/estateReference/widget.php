<?php 


	// load module core
	require_once( plugin_dir_path(__FILE__) .'/core.php');

	// Widget Functions
	class FFestateReferenceWidget extends WP_Widget  {

		// class constructor
		public function __construct() {
			$widgetOps = array(
				'classname' 	=> 'FFestateReference',
				'description' 	=> 'Zur Einbindung von Immobilien Referencen aus dem FLOWFACT Saleautomat',
			);
			parent::__construct('FFestateReference', 'FLOWFACT - Immobilien Referenzen', $widgetOps );
		}

		// output the widget content on the front-end
		public function widget( $args, $instance ) {
			
			// init module core
			$FFestateReferenceWidget = new FFestateReferenceWidget();
			echo $FFestateReferenceWidget->widget($args, $instance);
		}

		// output the option form field in admin Widgets screen
		public function form( $instance )
		{}

		// save options
		public function update( $new_instance, $old_instance )
		{}
	}
	
	// create Shortcode
	function FFestateReferenceShortcode( $atts ) {
		// defaults
		/*$a = shortcode_atts( array(
		  'name' => 'world'
	    ), $atts );*/
	   
	    // get addin
		$FFestateReferenceCore = new FFestateReferenceCore();
		$data = $FFestateReferenceCore->get_estate_reference_overview();
		return $data['content'];
	}
	
	add_shortcode( 'ff_estatereference_shortcode', 'FFestateReferenceShortcode' );

