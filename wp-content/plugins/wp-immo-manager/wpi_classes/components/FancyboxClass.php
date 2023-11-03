<?php
/**
 * Created by Media-Store.net.
 * User: Artur
 * Date: 05.12.2017
 * Time: 22:25
 */

namespace wpi\wpi_classes\components;


class FancyboxClass {

	public $view;

	public function __construct() {

		add_action( 'wp_enqueue_scripts', array( $this -> add_scripts() ) );

	}

	private function add_scripts() {
		// Fancybox-Style CSS
		wp_enqueue_style( 'fancybox', WPI_PLUGIN_URL . 'vendors/fancybox/dist/jquery.fancybox.min.css', false, '1.3' );
		// Fancybox-Script JS
		wp_enqueue_script( 'fancybox', WPI_PLUGIN_URL . 'vendors/fancybox/dist/jquery.fancybox.min.js', array( 'jquery' ), 1.3, false );
		// Add the Script type
		/*wp_add_inline_script( 'fancybox',
			'jQuery(document).ready(function($){
			$("[data-fancybox]").fancybox({
		// Options will go here
	});
	});
'
		);*/

	}

}