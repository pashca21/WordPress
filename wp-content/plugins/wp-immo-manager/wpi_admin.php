<?php

// Setup Textdomain
class Wp_language_manager {

	public function __construct() {
		load_plugin_textdomain( WPI_PLUGIN_NAME, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}
}

$wp_immo_manager = new Wp_language_manager();
$registerOptions = new wpi\wpi_classes\WpOptionsClass();
$admin           = new wpi\wpi_classes\AdminClass();

// create custom plugin settings menu
add_action( 'admin_menu', 'wpi_plugin_menu' );

function wpi_plugin_menu() {

	//create new top-level menu
	add_menu_page( 'WP Immo Manager', 'WP Immo Manager', 'manage_options', 'wpi_dashboard_page', 'wpi_dashboard_page', 'dashicons-admin-generic' );
	//create submenu's
	add_submenu_page( 'wpi_dashboard_page', 'Dashboard', 'Dashboard', 'manage_options', 'wpi_dashboard_page', 'wpi_dashboard_page' );
	add_submenu_page( 'wpi_dashboard_page', 'General', __( 'General', WPI_PLUGIN_NAME ), 'manage_options', 'wpi_general_page', 'wpi_general_page' );
	add_submenu_page( 'wpi_dashboard_page', 'Post-Type', __( 'Post-Type', WPI_PLUGIN_NAME ), 'manage_options', 'wpi_posttype_page', 'wpi_posttype_page' );
	add_submenu_page( 'wpi_dashboard_page', 'Single-View', __( 'Single-View', WPI_PLUGIN_NAME ), 'manage_options', 'wpi_single_page', 'wpi_single_page' );
	add_submenu_page( 'wpi_dashboard_page', 'List-View', __( 'List-View', WPI_PLUGIN_NAME ), 'manage_options', 'wpi_list_page', 'wpi_list_page' );
	add_submenu_page( 'wpi_dashboard_page', 'Shortcodes', __( 'Shortcodes', WPI_PLUGIN_NAME ), 'manage_options', 'wpi_shortcodes_page', 'wpi_shortcodes_page' );
	add_submenu_page( 'wpi_dashboard_page', 'Features', __( 'Features', WPI_PLUGIN_NAME ), 'manage_options', 'wpi_features_page', 'wpi_features_page' );

	//call register settings function
	add_action( 'admin_init', 'register_wpi_options' );

}


function register_wpi_options() {
	global $registerOptions;

	/**************************/
	/* Optionen registrieren */

	/*************************/

	return $registerOptions->wpi_register_settings();

}

/**
 * Set Shortcode-Field to POST-Type "Immobilien" only in Admin-View
 */
add_filter( 'manage_wpi_immobilie_posts_columns', 'wpi_immobilie_columns_id', 1 );
add_action( 'manage_wpi_immobilie_posts_custom_column', 'wpi_immobilie_custom_id_columns', 4, 2 );

function wpi_immobilie_columns_id( $defaults ) {

	return array(
		'cb'                       => '<input type="checkbox" />',
		'title'                    => __( 'Title' ),
		'wpi_immobilie_id'         => __( 'ID' ),
		'wpi_immobilie_shortcode'  => __( 'Shortcode' ),
		'author'                   => __( 'Autor' ),
		'taxonomy-vermarktungsart' => __( 'Vermarktungsart' ),
		'taxonomy-objekttyp'       => __( 'Objekttyp' ),
		'date'                     => __( 'Datum' ),
		'comments'                 => __( 'Kommentare' ),

	);;
}

function wpi_immobilie_custom_id_columns( $column_name, $id ) {

	if ( $column_name === 'wpi_immobilie_id' ) {
		echo $id;
	}
	if ( $column_name === 'wpi_immobilie_shortcode' ) {
		echo '[immobilien id=' . $id . ']';
	}
}


// Hook the Dashboard Widget
add_action( 'wp_dashboard_setup', 'wpi_dashboard_widget' );
function wpi_dashboard_widget() {
	global $wp_meta_boxes;

	wp_add_dashboard_widget( 'wpi_widget', 'Meine Immobilien', '\wpi\wpi_classes\AdminClass::wpi_dashboard_text' );
}

// Hook the Styles
function wpi_set_styles() {
	wp_enqueue_style( 'wp_immo_manager_css', WPI_PLUGIN_URL . 'scss/main.css' );
}

add_action( 'wp_enqueue_scripts', 'wpi_set_styles' );

// Register Bootstrap Styles

function wpi_bootstrap_styles() {
	// Lokale Bootstrap-Dateien
	$css     = WPI_PLUGIN_URL . 'bootstrap-3.3.0/dist/css/bootstrap.css';
	$script  = WPI_PLUGIN_URL . 'bootstrap-3.3.0/dist/js/bootstrap.js';
	$awesome = 'https://use.fontawesome.com/a043743ff2.js';
	$mainjs  = WPI_PLUGIN_URL . 'js/main.js';

	// Laden von jQuery
	wp_enqueue_script( 'jquery' );

	if ( wp_script_is( 'bootstrap', 'enqueued' ) ):
		return;
	else:
		wp_enqueue_style( 'bootstrap_css', $css );
		//wp_enqueue_style('bootstrap_theme_css', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css');

		wp_enqueue_script( 'bootstrap', $script, array( 'jquery' ), '3.3', true );
		wp_enqueue_script( 'wp_immo_manager_js', $mainjs, array( 'jquery' ), '', true );
		wp_enqueue_script( 'awesome_fonts', $awesome, array(), '', true );

	endif;
}

// Hook into the 'wp_enqueue_scripts' action
if ( 'active' === get_option( 'wpi_bootstrap_styles' ) ) {
	add_action( 'wp_enqueue_scripts', 'wpi_bootstrap_styles' );
}

// Register Bootstrap Styles für Admin-Page
function bootstrap_admin_enqueue( $hook ) {
	wp_enqueue_script( 'bootstrap_admin', WPI_PLUGIN_URL . 'bootstrap-3.3.0/dist/js/bootstrap.js', array( 'jquery' ), '', true );
}

if ( 'active' === get_option( 'wpi_bootstrap_styles' ) ) {
	add_action( 'admin_init', 'bootstrap_admin_enqueue' );
}

/*Dashboard Zählung Immobilien bei "Auf einen Blick" */
add_filter( 'dashboard_glance_items', 'immobilien_auf_einen_blick' );

function immobilien_auf_einen_blick( $items = array() ) {
	$post_types = array( 'wpi_immobilie' );
	foreach ( $post_types as $type ) {
		if ( ! post_type_exists( $type ) ) {
			continue;
		}
		$num_posts = wp_count_posts( $type );
		if ( $num_posts ) {
			$published = intval( $num_posts->publish );
			$post_type = get_post_type_object( $type );
			$text      = _n( '%s ' . $post_type->labels->singular_name, '%s ' . $post_type->labels->name, $published, WPI_PLUGIN_NAME );
			$text      = sprintf( $text, number_format_i18n( $published ) );
			if ( current_user_can( $post_type->cap->edit_posts ) ) {
				$output = '<a href="edit.php?post_type=' . $post_type->name . '">' . $text . '</a>';
				echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
			} else {
				$output = '<span>' . $text . '</span>';
				echo '<li class="post-count ' . $post_type->name . '-count">' . $output . '</li>';
			}
		}
	}

	return $items;
}

/**
 * Weiterlesen Link für Excerpts
 */
add_filter( 'excerpt_more', 'wpi_excerpt_more_link' );

function wpi_excerpt_more_link( $text ) {
	global $post;
	if ( get_post_type() == 'wpi_immobilie' ):
		return '... <a class="read-more-link" href="' . get_permalink( $post->ID ) . '">Read more</a>';
	endif;
}

//Funktion zum trimen eines Array_Values
function trim_value( &$value ) {
	$value = trim( $value );
}

/*****************************
 **** Validation Function ****
 *****************************/

add_action( 'wp_ajax_wpi_valid_status', 'wpi_valid_status' ); // Site Admin
add_action( 'wp_ajax_nopriv_wpi_valid_status', 'wpi_valid_status' ); // Site Admin


function wpi_valid_status() {
	if ( ! empty( $_POST ) ) {
		if ( $_POST['status'] == '1' && $_POST['valid'] == 'true' ) {
			update_option( 'wpi_pro', 'true' );
			setcookie( 'wpi_validated', 'true' );
			$status = 'reload';
		} else {
			update_option( 'wpi_pro', 'false' );
			setcookie( 'wpi_validated', 'false' );
			$status = 'noreload';
		}
	}
	echo $status;

}

/**
 * Admin-Notice to v3 Version
 */
function v3_admin_notice() {
	$notice = '<p><strong>Hinweis!!!</strong> Die Entwicklung des <strong>WP Immo Managers</strong> wird in der Version 3 mit noch mehr Features und Funktionen weitergeführt, die als Downloadlink erst nach Kauf einer Lizenz freigeschaltet wird. <br>
Sollten Sie als Kaufkunde noch keine Benachrichtigung von uns erhalten haben, so bitten wir Sie mit uns Kontakt aufzunehmen.</p>
<p><em>Die neue v3 Version benötigt min. PHP 7 oder höher und es wurden einige Änderungen an Templates vorgenommen, daher wird diese nicht über Wordpress-Update zur Verfügung gestellt.<br>
 Planen Sie die Umstellung evtl. erst zur nächsten Wartung Ihrer Seite. Wir bitten um Ihr Verständnis ! <a href="https://media-store.net">Ihr Media-Store.net Team</a></em></p>';
	?>
    <div class="notice notice-info is-dismissible">
        <p><?php _e( $notice, WPI_PLUGIN_NAME ); ?></p>
    </div>
	<?php
}

if ( get_option( 'wpi_pro' ) == 'true' ):
	add_action( 'admin_notices', 'v3_admin_notice' );
endif;