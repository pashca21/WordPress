<?php 


	// load module core
	require_once( plugin_dir_path(__FILE__) .'/core.php');

	// Widget View Functions
	class FFownerReportWidget extends WP_Widget  {

		// class constructor
		public function __construct() {
			$widgetOps = array(
				'classname' 	=> 'FFownerReport',
				'description' 	=> 'Owner report for estates',
			);
			parent::__construct('FFownerReport', 'FLOWFACT - Ownerreport', $widgetOps );
		}

		// output the widget content on the front-end
		public function widget( $args, $instance ) {
			// init module core
			
			
			// wrap widget
			extract($args, EXTR_SKIP);
			$view = $before_widget;
			$view .= $before_title . esc_html((!empty($result['title']) ? $result['title'] : "")) . $after_title;
			$view .= "<div class='ff-ownerreport-widget'>".do_shortcode('[ff_ownerreport_shortcode]')."</div>";
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
	function FFownerReportShortcode( $atts ) {
		
		// defaults
	  
		$FFownerReportCore = new FFownerReportCore();
		$data= $FFownerReportCore->get_login_page();
	
		return $data['content'];
	}
	add_shortcode( 'ff_ownerreport_shortcode', 'FFownerReportShortcode' );


	
	