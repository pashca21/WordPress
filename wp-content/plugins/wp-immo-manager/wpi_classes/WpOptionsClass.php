<?php
/**
 * User: Artur
 * Date: 19.09.2016
 * Time: 22:35
 */

namespace wpi\wpi_classes;

class WpOptionsClass {


	public function __construct() {

		$this -> wpi_options = array(
			'wpi_standard'                  => 'OpenImmo',
			'wpi_licence'                   => '',
			'wpi_admin'                     => '',
			'wpi_pro'                       => 'false',
			'wpi_bootstrap_styles'          => 'active',
			'wpi_xml_pfad'                  => WPI_UPLOAD_DIR . 'zip-archive/',
			'wpi_upload_pfad'               => WPI_UPLOADS_FOLDER,
			'wpi_upload_url'                => WPI_UPLOAD_URL,
			'wpi_post_type_slug'            => 'immobilien',
			'wpi_place_to_title'            => 'true',
			'wpi_list_options'              => array(
				'wpi_list_excerpt'              => 'true',
				'wpi_list_excerpt_length'       => '55',
				'wpi_list_detail'               => '',
				'wpi_list_view_column'          => 'list',
				'wpi_list_view_new_label'       => '2',
				'wpi_list_sidebar'              => 'true',
				'wpi_list_sidebar_name'         => '',
				'wpi_list_search'               => 'bar',
				'wpi_immogroup_sold'            => '1',
				'wpi_immogroup_sold_hide_price' => 'on',
				'wpi_immogroup_reserved'        => '2',
				'wpi_immogroup_top'             => '3',
			),
			'wpi_list_excerpt'              => 'true',
			'wpi_list_excerpt_length'       => '55',
			'wpi_list_detail'               => '',
			'wpi_list_view_column'          => 'list',
			'wpi_list_sidebar'              => 'true',
			'wpi_list_sidebar_name'         => '',
			'wpi_single_view'               => 'tabs',
			'wpi_single_view_tabs'          => array(
				'details'      => 'Objektdaten',
				'beschreibung' => 'Objektbeschreibung',
				'bilder'       => 'Bilder / Grundrisse',
				'kontakt'      => 'Kontaktperson',
			),
			'wpi_single_onePage'            => array(
				'beschreibung' => 'Objektbeschreibung',
				'details'      => 'Immobiliendetails',
				'kontakt'      => 'Kontaktperson',
				'preise'       => 'Kosten',
				'flaechen'     => 'Flächen',
				'zustand'      => 'Zustand',
				'ausstattung'  => 'Ausstattung',
				'energiepass'  => 'Energiepass',
				'dokumente'    => 'Dokumente',
				'map'          => 'Karte',
			),
			'wpi_single_sidebarPage_titles' => array(
				'details'      => 'Objektdetails',
				'beschreibung' => 'Objektbeschreibung',
				'lage'         => 'Lagedetails',
				'ausstattung'  => 'Ausstattung',
				'dokumente'    => 'Grundrisse',
				'meta'         => 'Erstellt und Aktualisiert',
			),
			'wpi_single_epass'              => array(
				'nicht_vorhanden' => 'Energieausweis ist in Bearbeitung',
				'nicht_benoetigt' => 'Für diese Immobilie ist kein Energieausweis erforderlich (z.B. Denkmalschutz)',
			),
			'wpi_single_sidebar'            => 'true',
			'wpi_single_sidebar_name'       => '',
			'wpi_single_preise'             => '',
			'wpi_single_flaechen'           => '',
			'wpi_single_hardfacts'          => '',
			'wpi_single_contacts'           => '',
			'wpi_avatar'                    => array(
				'active'     => 'true',
				'avatar_url' => '',
			),
			'wpi_slider'                    => array(
				'active_slider' => 'bootstrap',
				'title_in'      => '0',
				'lightbox_in'   => '0'
			),
			'wpi_shedule_time'              => 'hourly',
			'wpi_search_page'               => 'umkreissuche',
			'wpi_custom_css'                => '',
			'wpi_html_inject'               => '',
			'wpi_custom_html'               => '',
			'wpi_img_platzhalter'           => WPI_PLUGIN_URL . 'images/Fotolia_61039451_XS.jpg',
			'wpi_show_top_immo'             => '',
			'wpi_top_immo_source'           => '',
			'wpi_show_sold'                 => '',
			'wpi_sold_text'                 => 'Verkauft',
			'wpi_rented_text'               => 'Vermietet',
			'wpi_show_reserved'             => '',
			'wpi_reserved_text'             => 'Reserviert',
			'wpi_show_smartnav'             => 'false',
			'wpi_smartnav'                  => array(
				0 => array(
					'beschreibung' => 'Smart-Navigation',
					'title'        => 'Top',
					'link'         => '#top',
				),
				1 => array(
					'beschreibung' => '<i class="fa fa-eur" aria-hidden="true"></i>',
					'title'        => 'Kosten',
					'link'         => '#preise',
				),
				2 => array(
					'beschreibung' => '<i class="fa fa-cube" aria-hidden="true"></i>',
					'title'        => 'Flächen',
					'link'         => '#flaechen',
				),
				3 => array(
					'beschreibung' => '<i class="fa fa-user" aria-hidden="true"></i>',
					'title'        => 'Kontakt',
					'link'         => '#kontakt',
				),
				4 => array(
					'beschreibung' => '<i class="fa fa-list-alt" aria-hidden="true"></i>',
					'title'        => 'Ausstattung',
					'link'         => '#ausstattung',
				),
				5 => array(
					'beschreibung' => '<i class="fa fa-sun-o" aria-hidden="true"></i>',
					'title'        => 'Energiepass',
					'link'         => '#energiepass',
				),
				6 => array(
					'beschreibung' => '<i class="fa fa-map" aria-hidden="true"></i>',
					'title'        => 'Karte',
					'link'         => '#map',
				),
				7 => array(
					'beschreibung' => '',
					'title'        => '',
					'link'         => '',
				),
				8 => array(
					'beschreibung' => '',
					'title'        => '',
					'link'         => '',
				),
				9 => array(
					'beschreibung' => '',
					'title'        => '',
					'link'         => '',
				),
			),
			'wpi_show_article_navigation'   => 'true',
		);

		$this -> optionslist = $this -> wpi_get_options();

	}

	public function wpi_register_settings() {
		ob_start();

		/**************************/
		/* Optionen registrieren */
		/*************************/

		// General
		register_setting( 'wpi_standard_group', 'wpi_standard' );
		register_setting( 'wpi_standard_group', 'wpi_bootstrap_styles' );

		register_setting( 'wpi_licence_group', 'wpi_licence' );
		register_setting( 'wpi_licence_group', 'wpi_admin' );
		register_setting( 'wpi_licence_group', 'wpi_pro' );

		register_setting( 'wpi_xmlpath_group', 'wpi_xml_pfad' );
		register_setting( 'wpi_xmlpath_group', 'wpi_upload_pfad' );
		register_setting( 'wpi_xmlpath_group', 'wpi_upload_url' );
		// Post-Type
		register_setting( 'wpi_post_type_group', 'wpi_post_type_slug' );
		register_setting( 'wpi_post_type_group', 'wpi_place_to_title' );
		// Listen Ansicht Immobilien
		register_setting( 'wpi_post_list_view', 'wpi_list_options' );
		register_setting( 'wpi_post_list_view', 'wpi_list_excerpt' );
		register_setting( 'wpi_post_list_view', 'wpi_list_excerpt_length' );
		register_setting( 'wpi_post_list_view', 'wpi_list_detail' );
		register_setting( 'wpi_post_list_view', 'wpi_list_view_column' );
		register_setting( 'wpi_post_list_view', 'wpi_list_sidebar' );
		register_setting( 'wpi_post_list_view', 'wpi_list_sidebar_name' );

		// Single Ansicht Immobilien
		register_setting( 'wpi_post_single_view', 'wpi_single_view_tabs' );
		register_setting( 'wpi_post_single_view', 'wpi_single_onePage' );
		register_setting( 'wpi_post_single_view', 'wpi_single_sidebarPage_titles' );
		register_setting( 'wpi_post_single_view', 'wpi_single_view' );
		register_setting( 'wpi_post_single_view', 'wpi_single_sidebar' );
		register_setting( 'wpi_post_single_view', 'wpi_single_sidebar_name' );
		register_setting( 'wpi_post_single_view', 'wpi_single_preise' );
		register_setting( 'wpi_post_single_view', 'wpi_single_flaechen' );
		register_setting( 'wpi_post_single_view', 'wpi_single_hardfacts' );
		register_setting( 'wpi_post_single_view', 'wpi_single_contacts' );
		register_setting( 'wpi_post_single_view', 'wpi_single_epass' );
		register_setting( 'wpi_post_single_view', 'wpi_slider' );
		register_setting( 'wpi_post_single_view', 'wpi_avatar' );
		register_setting( 'wpi_post_single_view', 'wpi_show_smartnav' );
		register_setting( 'wpi_post_single_view', 'wpi_show_article_navigation' );
		register_setting( 'wpi_shedule_group', 'wpi_shedule_time' );
		// Shortcodes
		register_setting( 'wpi_shortcodes_group', 'wpi_search_page' );
		// Features Settings
		register_setting( 'wpi_features_group', 'wpi_custom_css' );
		register_setting( 'wpi_features_group', 'wpi_custom_html' );
		register_setting( 'wpi_features_group', 'wpi_html_inject' );
		register_setting( 'wpi_features_group', 'wpi_img_platzhalter' );
		register_setting( 'wpi_features_group', 'wpi_show_top_immo' );
		register_setting( 'wpi_features_group', 'wpi_top_immo_source' );

		register_setting( 'wpi_features_group', 'wpi_show_reserved' );
		register_setting( 'wpi_features_group', 'wpi_reserved_text' );
		register_setting( 'wpi_features_group', 'wpi_show_sold' );
		register_setting( 'wpi_features_group', 'wpi_sold_text' );

		register_setting( 'wpi_features_group', 'wpi_smartnav' );


		/*************************************/
		// Optionen standardmäßig hinzufügen //
		/*************************************/

		foreach ( $this -> wpi_options as $key => $value ) {
			add_option( $key, $value );
		}

		return ob_get_clean();

	}

	public function wpi_get_options() {
		$list = array();
		foreach ( $this -> wpi_options as $key => $value ) {
			$list[ $key ] = get_option( $key );
		}

		return $list;
	}
}
