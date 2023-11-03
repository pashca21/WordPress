<?php 


	// load module core
	require_once( plugin_dir_path(__FILE__) .'/core.php');

	// Widget Functions
	class FFteamOverviewWidget extends WP_Widget  {

		// class constructor
		public function __construct() {
			$widgetOps = array(
				'classname' 	=> 'FFteamOverveiw',
				'description' 	=> 'Zur Integration von Mitarbeitern aus dem FLOWFACT',
			);
			parent::__construct('FFteamOverview', 'FLOWFACT - Team Übersicht', $widgetOps );
		}

		// output the widget content on the front-end
		public function widget( $args, $instance ) {
			// init module core
			$FFteamOverviewCore = new FFteamOverviewCore();
		
			$result = $FFteamOverviewCore->get_team_overview();

			// wrap widget
			extract($args, EXTR_SKIP);
			$view = $before_widget;
			
			$view .= $before_title . "Team Übersicht" . $after_title;
			$view .= "<div class='ff-teamoverview-widget'>".$result."</div>";
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
	function FFteamOverviewShortcode( $atts ) {
		// defaults
		$atts = shortcode_atts( array(
		  'schema' => false,
		  'show_search' => "on"
	    ), $atts );
	   
	    // get addin
		$FFestateViewCore = new FFteamOverviewCore();

		$data = $FFestateViewCore->get_team_overview($atts);
		return $data;
	}
	
	add_shortcode( 'ff_teamoverview_shortcode', 'FFteamOverviewShortcode' );


