<?php
/*
Plugin Name: expowand-connect
Description: Verbinden Sie Ihr Wordpress mit Ihrem Expowand CRM.
Version: 0.0.2
Author: LeadValue GmbH
Author URI: http://www.leadvalue.de
Text Domain: expowand-connect
Plugin Slug: expowand-connect
Domain Path: /languages
License: GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Expowand Connect is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 2 of the License, or
any later version.

Expowand WP Connect is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with Expowand WP Connect. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

*/


defined( 'ABSPATH' ) or exit('Error');

class ExpowandConnect {
	public function __construct() {
		// load constants
		require_once plugin_dir_path(__FILE__).'core/constants/default.php';

		require_once plugin_dir_path(__FILE__).'core/constants/admin.php';

		require_once plugin_dir_path(__FILE__).'core/constants/api.php';

		// load api
		require_once plugin_dir_path(__FILE__).'core/api/api.php';

		// load admin panel
		require_once plugin_dir_path(__FILE__).'core/admin/settings.php';

		// add plugins routing to the ruleset
		add_action('init', [$this, 'ew_rewrite_rule'], 1);

		// add query vars
		add_action('query_vars', [$this, 'ew_add_query_vars_filter'], 1);

		// virtual page init
		add_action('init', [$this, 'ew_init'], 9);

		add_option('plugin_status');

		add_action('admin_notices', [$this, 'ew_activation_msg']);

	}
 
	public function ew_activation_msg() {
		// get plugin version
		$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);

		// admin panel notice 
		//de-DE
		$lng = get_bloginfo('language');
		if($lng == 'de-DE') {
			echo '<div class="notice notice-info is-dismissible"><p>Expowand WP Connect wurde auf die Version '.$plugin_data['Version'].' aktualisiert.<br>
			Bitte setzen Sie die Permalinks-Struktur auf "post-name" <a href="'. get_home_url() .'/wp-admin/options-permalink.php">hier</a> 
			</p></div>';
		} else {
			// en
			echo '<div class="notice notice-info is-dismissible"><p>Expowand WP Connect has been updated to the version '.$plugin_data['Version'].'<br>Please set the permalinks structure to the "post-name" <a href="'. get_home_url() .'/wp-admin/options-permalink.php">here</a> 
			</p></div>';
		}
	}
 
	public function ew_rewrite_rule() {
		add_rewrite_rule(
			'^ew/([^/]*)/([^/]*)/([^/]*)/([^/]*)?',
			'index.php?plugin=ew&module=$matches[1]&slug=$matches[2]&schema=$matches[3]&identifier=$matches[4]',
			'top'
		);
		add_rewrite_rule(
			'^ew/([^/]*)/([^/]*)/([^/]*)?',
			'index.php?plugin=ew&module=$matches[1]&schema=$matches[2]&identifier=$matches[3]',
			'top'
		);
		add_rewrite_rule(
			'^ew/([^/]*)/([^/]*)/?',
			'index.php?plugin=ew&module=$matches[1]&schema=$matches[2]',
			'top'
		);
		add_rewrite_rule(
			'^ew/([^/]*)?',
			'index.php?plugin=ew&module=$matches[1]',
			'top'
		);
	}
 
	public function ew_add_query_vars_filter($vars) {
		$vars[] = 'plugin';
		$vars[] = 'module';
		$vars[] = 'slug';
		$vars[] = 'schema';
		$vars[] = 'identifier';

		return $vars;
	}
 
	public function ew_init() {
		$uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
		foreach ($uriSegments as $segment) {
			if (EW_PLUGIN_ROUTE == $segment) {
				add_filter('the_posts', [$this, 'get_vitual_page_detect'], 1);
			}
		}
	}

	public function plugin_activate() { 
		// extend DB
		global $wpdb;
		$schemacharset_collate = $wpdb->get_charset_collate();

		require_once ABSPATH.'wp-admin/includes/upgrade.php';

		$sql = 'CREATE TABLE '.$wpdb->prefix."ew_entity_cache (
			entityId varchar(80) NOT NULL,
			schemaId varchar(80) NOT NULL,
			created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			json text NOT NULL,
			PRIMARY KEY (entityId)
			) {$schemacharset_collate};";
		dbDelta($sql);

		$sql = 'CREATE TABLE '.$wpdb->prefix."ew_general_cache (
			name varchar(80) NOT NULL,
			created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			json text NOT NULL,
			PRIMARY KEY (name)
			) {$schemacharset_collate};";
		dbDelta($sql);

		// set rewrite
		set_transient('ew_flush', 1, 60);
	}
 
	public function plugin_deactivate() {
		// cleanup DB
		global $wpdb;
		$schemacharset_collate = $wpdb->get_charset_collate();

		require_once ABSPATH.'wp-admin/includes/upgrade.php';

		$sql = 'DROP TABLE IF EXISTS '.$wpdb->prefix.'ew_general_cache';
		$wpdb->query($sql);

		$sql = 'DROP TABLE IF EXISTS '.$wpdb->prefix.'ew_entity_cache';
		$wpdb->query($sql);

		// flush rewrite rules
		flush_rewrite_rules();
	}
 
	public function debug_to_console($data) {
		$output = $data;
		if (is_array($output)) {
			$output = implode(',', $output);
		}
	}

	// parse POST/GET request
	public function get_vitual_page_detect($posts) {
		global $wp, $wp_query;

		if (!empty(get_query_var('plugin'))) {
			$plugin = get_query_var('plugin');
		}
		if (!empty(get_query_var('module'))) {
			$module = get_query_var('module');
		}
		if (!empty(get_query_var('slug'))) {
			$slug = get_query_var('slug');
		}
		if (!empty(get_query_var('schema'))) {
			$schema = get_query_var('schema');
		}
		if (!empty(get_query_var('identifier'))) {
			$identifier = get_query_var('identifier');
		}

		// global $get_vitual_page_detect; // used to stop double loading
		$get_vitual_page_url = $_SERVER['REQUEST_URI']; // URL of the fake page

		if (!empty($_GET['console']) and 'true' == $_GET['console']) {
			echo "<script>
				console.log( 'current URL: ".parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)."' );
				console.log( 'fake URL: ".$get_vitual_page_url."' );
				console.log( '- part plugin: ".$plugin."' );
				console.log( '- part module: ".$module."' );
				console.log( '- part slug: ".$slug."' );
				console.log( '- part schema: ".$schema."' );
				console.log( '- part identifier: ".$identifier."' );
				</script>";
		}

		if (!empty($plugin) and EW_PLUGIN_ROUTE == $plugin and !defined('EW_VIRTUAL_PAGE')) {
			define( 'EW_VIRTUAL_PAGE', true );

			// create a fake virtual page
			$post = new stdClass();
			$post->post_author = 1;
			$post->post_name = $get_vitual_page_url;
			$post->guid = get_bloginfo('wpurl').'/'.$get_vitual_page_url;

			if (!empty($plugin) && EW_PLUGIN_ROUTE == $plugin) {
				if (EW_ESTATEVIEW_ROUTE == $module) {
					if (!empty($identifier)) {
						$EWestateViewCore = new EWestateViewCore();
						$data = $EWestateViewCore->get_estate_details($schema, $identifier);
						$post->post_title = $data['title'];
						$post->post_content = $data['content'];
					} else {
						global $wp_query;
						$wp_query->set_404();
						status_header(404);
						get_template_part(404);
	
						exit;
					}
				} elseif (EW_ESTATEREFERENCE_ROUTE == $module) {
					$EWestateReferenceCore = new EWestateReferenceCore();
					$data = $EWestateReferenceCore->get_estate_reference_overview();
					$post->post_title = $data['title'];
					$post->post_content = $data['content'];
				} else {
					global $wp_query;
					$wp_query->set_404();
					status_header(404);
					get_template_part(404);

					exit;
				}
			}

			$post->post_type = EW_POST_TYPE;
			$post->post_status = 'static';
			$post->comment_status = 'closed';
			$post->ping_status = 'open';
			$post->comment_count = 0;
			$post->post_date = current_time('mysql');
			$post->post_date_gmt = current_time('mysql', 1);
			$posts = NULL;
			$posts[] = $post;

			// make wpQuery believe this is a real page too
			if (EW_POST_TYPE == 'page') {
				$wp_query->is_page = true;
				$wp_query->is_single = false;
			} else {
				$wp_query->is_page = false;
				$wp_query->is_single = true;
			}

			$wp_query->is_singular = true;
			$wp_query->is_home = false;
			$wp_query->is_archive = false;
			$wp_query->is_category = false;

			unset($wp_query->query["error"]);
			$wp_query->query_vars["error"] = "";
			$wp_query->is_404 = false;

			remove_filter('the_content', 'wpautop');
			remove_filter('the_excerpt', 'wpautop');

		}
		
		return $posts;
	}
 
	public function init_modules() {
		// init widgets EWestateViewWidget
		add_action('widgets_init', function () {
			require_once plugin_dir_path(__FILE__).'modules/estateView/config.php';

			require_once plugin_dir_path(__FILE__).'modules/estateView/widget.php';
			register_widget('EWestateViewWidget');
		});

		// init widgets EWestateReferenceWidget
		add_action('widgets_init', function () {
			require_once plugin_dir_path(__FILE__).'modules/estateReference/config.php';

			require_once plugin_dir_path(__FILE__).'modules/estateReference/widget.php';
			register_widget('EWestateReferenceWidget');
		});

	}

}

if (class_exists('ExpowandConnect')) {
	$expowandConnect = new ExpowandConnect();
	$expowandConnect->init_modules();
}

// activate
register_activation_hook(__FILE__, [$expowandConnect, 'plugin_activate']);

// deactivate
register_deactivation_hook(__FILE__, [$expowandConnect, 'plugin_deactivate']);
