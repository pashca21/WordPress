<?php 

	// load module core
	require_once( plugin_dir_path(__FILE__) .'/core.php');

	// Widget View Functions
	class EWestateViewWidget extends WP_Widget  {

		// class constructor
		public function __construct() {
			$widgetOps = array(
				'classname' 	=> 'EWestateView',
				'description' 	=> 'Zur Einbindung von Immobilien aus dem Expowand',
			);
			parent::__construct('EWestateView', 'Expowand - Immobiliensuche', $widgetOps );
		}

		// output the widget content on the front-end
		public function widget( $args, $instance ) {
			// init module core
			$EWestateViewCore = new EWestateViewCore();
			$result = $EWestateViewCore->widget();
			
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
