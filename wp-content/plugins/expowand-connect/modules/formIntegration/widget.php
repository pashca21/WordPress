<?php

	// load module core
	require_once( plugin_dir_path(__FILE__) .'/core.php');


	// Widget Functions  
	class FFFormIntegrationWidget extends WP_Widget  {
		
		// class constructor
		public function __construct() {
			$widgetOps = array( 
				'classname' 	=> 'FFFormIntegrationWidget',
				'description' 	=> 'Zur Einbindung von Integrationen aus FLOWFACT Saleautomat',
			);
			parent::__construct('FFFormIntegrationWidget', 'FLOWFACT - Integrationen', $widgetOps );
				
			
		}

		// output the widget content on the front-end
		public function widget( $args, $instance ) {
			
			if(!empty($instance['integration']))
			{
				//$html 		= wp_remote_retrieve_body( wp_remote_get($instance['integration']));
				//print_r($html); ; 
				echo '<iframe src="'.$instance['integration'].'" style="height:400px; width:100%; border:0;" scrolling="auto"></iframe>';			
			}
			else
			{
				echo 'Keine Integration gewählt.';
			}	
		}

		// output the option form field in admin Widgets screen
		public function form( $instance )
		{
		
			// init module core
			$FFformIntegrationCore = new FFformIntegrationCore();
			$result = $FFformIntegrationCore->widget($instance);

			if(!empty($result))
			{

				echo '<p>';
					echo '<label for="'.esc_attr( $this->get_field_name( 'integration' )).'" >Bitte wählen Sie Ihre Integration</label>';
					echo '<select style="width:100%;" name="'.esc_attr( $this->get_field_name( 'integration' )).'">';
						echo '<option value="">Bitte wählen</option>';
						foreach($result as $key => $row)
						{
							//FIX: fix for default integration of the SA for FF Exporter.
						if($row["label"] != "FLOWFACT" and $row["label"] != "Importiert aus Flowfact")
							{
								echo '<option  value="'.$row["dataUris"]["FORM"].'"';
								if(!empty($instance['integration']) && $instance['integration'] == $row["dataUris"]["FORM"]){
									echo 'selected';
								}
								echo ' >'.$row["label"].'</option>';
							}
						}
					echo '</select>';	
				echo '</p>';	
			}	
			else
			{
				echo 'Es konnte keine verbindung zu Ihrem Saleautomat hergestellt werden.<br/>';
				echo 'Bitte prüfen Sie Ihren Platform Token';
			}	
	
		}

		// save options
		public function update( $new_instance, $old_instance ) 
		{
			$instance = array();
			$instance['integration'] = ( ! empty( $new_instance['integration'] ) ) ? strip_tags( $new_instance['integration'] ) : '';
			return $instance;
		}
	}

 
	// create Shortcode
	function FFdefaultFormShortcode( $atts ) {
		
	    // get addin
		$FFformIntegrationCore = new FFformIntegrationCore();
		$data = $FFformIntegrationCore->get_default_form($atts);
		echo $data;

	}
	add_shortcode( 'ff_formintegration_default_form_shortcode', 'FFdefaultFormShortcode' );
	
	
	
	// init widgets
	add_action( 'widgets_init', function(){
		register_widget( 'ff_formintegration' );
	});
	
	add_action( 'widgets_init', function(){
		register_widget( 'ff_formintegration_default_form_shortcode' );
	});
	