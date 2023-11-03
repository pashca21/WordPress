<?php
/*
 Plugin Name: flowfact-wp-connector
 Description: Verbinden Sie Ihr Wordpress mit Ihrem FLOWFACT CRM.
 Version: 2.1.7
 Author: FLOWFACT
 Author URI: http://www.flowfact.de
 Text Domain: flowfact-wp-connector
 Plugin Slug: flowfact-wp-connector
 Domain Path: /languages
 License: GPL2
 License URI: https://www.gnu.org/licenses/gpl-2.0.html

 FLOWFACT WP Connector is free software: you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation, either version 2 of the License, or
 any later version.

 FLOWFACT WP Connector is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with FLOWFACT WP Connector. If not, see https://www.gnu.org/licenses/gpl-2.0.html.

 */

defined( 'ABSPATH' ) or exit('No script kiddies please!');

class FlowfactSalesautomatConnector
{
	public function __construct()
	{
		// load constants
		require_once plugin_dir_path(__FILE__).'core/constants/default.php';

		require_once plugin_dir_path(__FILE__).'core/constants/admin.php';

		require_once plugin_dir_path(__FILE__).'core/constants/api.php';

		// load api
		require_once plugin_dir_path(__FILE__).'core/api/api.php';

		// load admin panel
		require_once plugin_dir_path(__FILE__).'core/admin/settings.php';

		// load composer packs.
		require_once plugin_dir_path(__FILE__).'vendor/autoload.php';

		// add plugins routing to the ruleset
		add_action('init', [$this, 'ff_rewrite_rule'], 1);

		// add query vars
		add_action('query_vars', [$this, 'ff_add_query_vars_filter'], 1);

		// virtual page init
		add_action('init', [$this, 'ff_init'], 9);

		add_option('plugin_status');

		add_action('admin_notices', [$this, 'ff_activation_msg']);

	}

	public function ff_activation_msg() {
		// get plugin version
		$plugin_data = get_file_data(__FILE__, array('Version' => 'Version'), false);

		// admin panel notice 
		//de-DE
		$lng = get_bloginfo('language');
		if($lng == 'de-DE') {
			echo '<div class="notice notice-info is-dismissible"><p>FlowFact WP Connector wurde auf die Version '.$plugin_data['Version'].' aktualisiert.<br>
			Bitte setzen Sie die Permalinks-Struktur auf "post-name" <a href="'. get_home_url() .'/wp-admin/options-permalink.php">hier</a> 
			</p></div>';
		} else {
			// en
			echo '<div class="notice notice-info is-dismissible"><p>FlowFact WP Connector has been updated to the version '.$plugin_data['Version'].'<br>Please set the permalinks structure to the "post-name" <a href="'. get_home_url() .'/wp-admin/options-permalink.php">here</a> 
			</p></div>';
		}
	}

	public function ff_rewrite_rule()
	{
		add_rewrite_rule(
			'^ff/([^/]*)/([^/]*)/([^/]*)/([^/]*)?',
			'index.php?plugin=ff&module=$matches[1]&slug=$matches[2]&schema=$matches[3]&identifier=$matches[4]',
			'top'
		);
		add_rewrite_rule(
			'^ff/([^/]*)/([^/]*)/([^/]*)?',
			'index.php?plugin=ff&module=$matches[1]&schema=$matches[2]&identifier=$matches[3]',
			'top'
		);
		add_rewrite_rule(
			'^ff/([^/]*)/([^/]*)/?',
			'index.php?plugin=ff&module=$matches[1]&schema=$matches[2]',
			'top'
		);
		add_rewrite_rule(
			'^ff/([^/]*)?',
			'index.php?plugin=ff&module=$matches[1]',
			'top'
		);
	}

	public function ff_add_query_vars_filter($vars)
	{
		$vars[] = 'plugin';
		$vars[] = 'module';
		$vars[] = 'slug';
		$vars[] = 'schema';
		$vars[] = 'identifier';

		return $vars;
	}

	public function ff_init()
	{
		$uriSegments = explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

		foreach ($uriSegments as $segment) {
			if (FF_PLUGIN_ROUTE == $segment) {
				add_filter('the_posts', [$this, 'get_vitual_page_detect'], 1);
			}
		}
	}

	public function plugin_activate()
	{
		// extend DB
		global $wpdb;
		$schemaharset_collate = $wpdb->get_charset_collate();

		require_once ABSPATH.'wp-admin/includes/upgrade.php';

		$sql = 'CREATE TABLE '.$wpdb->prefix."ff_entity_cache (
			  entityId varchar(80) NOT NULL,
			  schemaId varchar(80) NOT NULL,
			  created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  json text NOT NULL,
			  PRIMARY KEY (entityId)
			  ) {$schemaharset_collate};";
		dbDelta($sql);

		$sql = 'CREATE TABLE '.$wpdb->prefix."ff_schema_cache (
			  schemaId varchar(80) NOT NULL,
			  schemaName varchar(80) NOT NULL,
			  created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  json text NOT NULL,
			  PRIMARY KEY (schemaId)
			  ) {$schemaharset_collate};";
		dbDelta($sql);

		$sql = 'CREATE TABLE '.$wpdb->prefix."ff_general_cache (
			  name varchar(80) NOT NULL,
			  created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  json text NOT NULL,
			  PRIMARY KEY (name)
			  ) {$schemaharset_collate};";
		dbDelta($sql);

		$sql = 'CREATE TABLE '.$wpdb->prefix."ff_customer_cache (
			  id varchar(80) NOT NULL,
			  created datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
			  customer varchar(80) NOT NULL,
			  customerIp varchar(80) NOT NULL,
			  schemaId varchar(80) NOT NULL,
			  entityId varchar(80) NOT NULL,
			  value varchar(80) NOT NULL,
			  PRIMARY KEY (id)
			  ) {$schemaharset_collate};";
		dbDelta($sql);

		// set rewrite
		set_transient('ff_flush', 1, 60);
	}

	public function plugin_deactivate()
	{
		// cleanup DB
		global $wpdb;
		$schemaharset_collate = $wpdb->get_charset_collate();

		require_once ABSPATH.'wp-admin/includes/upgrade.php';

		$sql = 'DROP TABLE IF EXISTS '.$wpdb->prefix.'ff_general_cache';
		$wpdb->query($sql);

		$sql = 'DROP TABLE IF EXISTS '.$wpdb->prefix.'ff_schema_cache';
		$wpdb->query($sql);

		$sql = 'DROP TABLE IF EXISTS '.$wpdb->prefix.'ff_entity_cache';
		$wpdb->query($sql);

		$sql = 'DROP TABLE IF EXISTS '.$wpdb->prefix.'ff_customer_cache';
		$wpdb->query($sql);

		// flush rewrite rules
		flush_rewrite_rules();
	}

	public function debug_to_console($data)
	{
		$output = $data;
		if (is_array($output)) {
			$output = implode(',', $output);
		}
	}

	public function get_vitual_page_detect($posts)
	{
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

		if (!empty($plugin) and FF_PLUGIN_ROUTE == $plugin and !defined('FF_VIRTUAL_PAGE')) {
			define( 'FF_VIRTUAL_PAGE', true );

			// create a fake virtual page
			$post = new stdClass();
			$post->post_author = 1;
			$post->post_name = $get_vitual_page_url;
			$post->guid = get_bloginfo('wpurl').'/'.$get_vitual_page_url;

			if (!empty($plugin) && FF_PLUGIN_ROUTE == $plugin) {
				if (FF_ESTATEVIEW_ROUTE == $module) {
					// Substing added to allow to add time stamp to xml url to protect caching
					if ((((!empty($slug) and '.xml' === substr($slug, -4) and 'sitemap' === substr($slug, 0, 7)) or (!empty($schema) and '.xml' === substr($schema, -4) and 'sitemap' === substr($schema, 0, 7))) or ((!empty($slug) and '.txt' === substr($slug, -4) and 'sitemap' === substr($slug, 0, 7)) or (!empty($schema) and '.txt' === substr($schema, -4) and 'sitemap' === substr($schema, 0, 7)))) and FF_ESTATEVIEW_SEO == 'on') {
						if ('.xml' === substr($slug, -4) or '.xml' === substr($schema, -4)) {
							$type = 'xml';
						} elseif ('.txt' === substr($slug, -4) or '.txt' === substr($schema, -4)) {
							$type = 'txt';
						} else {
							$type = 'xml';
						}

						$FFestateViewCore = new FFestateViewCore();
						$data = $FFestateViewCore->get_estate_sitemap($type);
						header('Content-type: text/plain');
						echo $data['content'];

						exit;
					}
					if (!empty($identifier)) {
						$FFestateViewCore = new FFestateViewCore();
						$data = $FFestateViewCore->get_estate_details($schema, $identifier);
						$post->post_title = $data['title'];
						$post->post_content = $data['content'];
					} else {
						$FFestateViewCore = new FFestateViewCore();
						$data = $FFestateViewCore->get_estate_overview();
						$post->post_title = $data['title'];
						$post->post_content = $data['content'];
					}
				} elseif (FF_ESTATEREFERENCE_ROUTE == $module) {
					$FFestateReferenceCore = new FFestateReferenceCore();
					$data = $FFestateReferenceCore->get_estate_reference_overview();
					$post->post_title = $data['title'];
					$post->post_content = $data['content'];
				} elseif (FF_VALUATION_ROUTE == $module) {
					$FFvaluationCore = new FFvaluationCore();
					$data = $FFvaluationCore->get_overview();
					$post->post_title = $data['title'];
					$post->post_content = $data['content'];
				} elseif (FF_VALUATIONMASTER_ROUTE == $module) {
					if ((!empty($schema) and 'report' == $schema) or (!empty($slug) and 'report' == $slug)) {
						$FFvaluationMasterCore = new FFvaluationMasterCore();
						$data = $FFvaluationMasterCore->get_results_by_url();
						$post->post_title = $data['title'];
						$post->post_content = $data['content'];
					} elseif ((!empty($schema) and 'call' == $schema) or (!empty($slug) and 'call' == $slug)) {
						$FFvaluationMasterCore = new FFvaluationMasterCore();
						$data = $FFvaluationMasterCore->send_callback();
					} elseif ((!empty($schema) and 'store' == $schema) or (!empty($slug) and 'store' == $slug)) {
						$FFvaluationMasterCore = new FFvaluationMasterCore();
						if (!isset($GLOBALS['valuationMasterSent']) || !$GLOBALS['valuationMasterSent']) {
							$data = $FFvaluationMasterCore->get_result();
							$post->post_title = $data['title'];
							$post->post_content = $data['content'];
						}
					} elseif ((!empty($schema) and 'store&iframe=1' == $schema) or (!empty($slug) and 'store&iframe=1' == $slug)) {
						// iFrame fix
						$FFvaluationMasterCore = new FFvaluationMasterCore();
						if (!isset($GLOBALS['valuationMasterSent']) || !$GLOBALS['valuationMasterSent']) {
							$data = $FFvaluationMasterCore->get_result();
							$post->post_title = $data['title'];
							$post->post_content = $data['content'];

							wp_head();
							echo $post->post_content;
							wp_footer();

							exit;
						}
					} else {
						$FFvaluationMasterCore = new FFvaluationMasterCore();
						$data = $FFvaluationMasterCore->get_overview();
						$post->post_title = $data['title'];
						$post->post_content = $data['content'];
					}
				} else {
					global $wp_query;
					$wp_query->set_404();
					status_header(404);
					get_template_part(404);

					exit;
				}
			}

			$post->post_type = FF_POST_TYPE;
			$post->post_status = 'static';
			$post->comment_status = 'closed';
			$post->ping_status = 'open';
			$post->comment_count = 0;
			$post->post_date = current_time('mysql');
			$post->post_date_gmt = current_time('mysql', 1);
			$posts = NULL;
			$posts[] = $post;

			// make wpQuery believe this is a real page too
			if (FF_POST_TYPE == 'page') {
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

			if (!empty($_GET['iframe']) and '1' == $_GET['iframe']) {
				wp_head();
				echo $post->post_content;
				wp_footer();

				die();
			} else {
				return $posts;
			}
		} else {
			return $posts;
		}
	}

	public function init_modules()
	{
		// init widgets FFestateViewWidget
		add_action('widgets_init', function () {
			require_once plugin_dir_path(__FILE__).'modules/estateView/config.php';

			require_once plugin_dir_path(__FILE__).'modules/estateView/widget.php';
			register_widget('FFestateViewWidget');
		});

		// init widgets FFestateReferenceWidget
		add_action('widgets_init', function () {
			require_once plugin_dir_path(__FILE__).'modules/estateReference/config.php';

			require_once plugin_dir_path(__FILE__).'modules/estateReference/widget.php';
			register_widget('FFestateReferenceWidget');
		});

		// init widgets FFvaluationWidget
		add_action('widgets_init', function () {
			require_once plugin_dir_path(__FILE__).'modules/valuation/config.php';

			require_once plugin_dir_path(__FILE__).'modules/valuation/widget.php';
			register_widget('FFvaluationWidget');
		});

		// init widgets FFestateViewWidget
		add_action('widgets_init', function () {
			require_once plugin_dir_path(__FILE__).'modules/teamOverview/config.php';

			require_once plugin_dir_path(__FILE__).'modules/teamOverview/widget.php';
			register_widget('FFteamOverviewWidget');
		});

		// init widgets FFFormIntegrationWidget
		add_action('widgets_init', function () {
			require_once plugin_dir_path(__FILE__).'modules/formIntegration/config.php';

			require_once plugin_dir_path(__FILE__).'modules/formIntegration/widget.php';
			register_widget('FFFormIntegrationWidget');
		});

		// init widgets FFcompanyPlaceholder
		require_once plugin_dir_path(__FILE__).'modules/companyPlaceholder/config.php';

		require_once plugin_dir_path(__FILE__).'modules/companyPlaceholder/core.php';

		// init widgets FFvaluationMasterWidget
		add_action('widgets_init', function () {
			require_once plugin_dir_path(__FILE__).'modules/valuationMaster/config.php';

			require_once plugin_dir_path(__FILE__).'modules/valuationMaster/widget.php';
			register_widget('FFvaluationMasterWidget');
		});

		// init widgets FFownerReportWidget
		add_action('widgets_init', function () {
			require_once plugin_dir_path(__FILE__).'modules/ownerReport/config.php';

			require_once plugin_dir_path(__FILE__).'modules/ownerReport/widget.php';
			register_widget('FFownerReportWidget');
		});
	}
}

if (class_exists('FlowfactSalesautomatConnector')) {
	$flowfactSalesautomatConnector = new FlowfactSalesautomatConnector();
	$flowfactSalesautomatConnector->init_modules();
}

// activate
register_activation_hook(__FILE__, [$flowfactSalesautomatConnector, 'plugin_activate']);

// deactivate
register_deactivation_hook(__FILE__, [$flowfactSalesautomatConnector, 'plugin_deactivate']);
