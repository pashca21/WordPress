<?php

/* SEITENTYP HINZUFÜGEN */
function wpi_create_post_type()
{
    $post_type_slug = get_option('wpi_post_type_slug');
    $menu_icon = 'dashicons-admin-home';

    $labels = array(
    'name' => __('Immobilien'), // Name im Plural
    'singular_name' => __('Immobilie'), //Singular Name
    'menu_name' => __('Immobilien'), //Name im Menu
    'all_items' => __('Alle Objekte'),
    'add_new' => __('Neues Objekt hinzufügen'),
    'add_new_item' => __('Neues Objekt hinzufügen'),
    'edit_item' => __('Objekt bearbeiten'),
    'new_item' => __('Neues Objekt hinzufügen'),
    'view_item' => __('Dieses Objekt ansehen'),
    'search_items' => __('Alle Objekte durchsuchen'),
    'not_found' => __('Keine Objekte gefunden'),
    'not_found_in_trash' => __('Keine Objekte im Papierkorb gefunden')
);

    $args = array(
        'labels' => $labels,
        'description' => __('Description.', 'Post-Type für Immobilien'),
        'public' => true,
        'publicly_queryable' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => array('slug' => $post_type_slug),
        'capability_type' => 'post',
        'has_archive' => true,
        'hierarchical' => false,
        'menu_position' => 5,
        'menu_icon' => $menu_icon,
        'show_in_rest' => true,
        'rest_base' => 'immobilien',
        'rest_controller_class' => 'WP_REST_Posts_Controller',
        'supports' => array('title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments')
    );

    register_post_type('wpi_immobilie', $args);
    // Refresh Permalinks
    flush_rewrite_rules();

}

// Taxonomie Vermarktung z.B. "Mieten" "Kaufen" "Pacht" etc.
function immo_tax_vermarktung()
{
    $labels = array(
        'name' => _x('vermarktungsart', 'General Name'),
        'singular_name' => _x('Vermarktungsart', 'Singular Name'),
        'search_items' => __('Nach Vermarktungsart suchen'),
        'all_items' => __('Vermarktungsarten'),
        'edit_item' => __('Vermarktungsart bearbeiten'),
        'update_item' => __('Vermarktungsart aktualisieren'),
        'add_new_item' => __('Neuen Vermarktungstyp anlegen'),
        'new_item' => __('Neue Vermarktungsart'),
        'menu_name' => __('Vermarktungsart'),
        'parent_item' => __(''),
        'parent_item_colon' => __(''),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'vermarktung'),
        'show_in_rest' => true,
        'rest_base' => 'vermarktung',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('vermarktungsart', 'wpi_immobilie', $args);
	// Refresh Permalinks
	flush_rewrite_rules();

}

// Taxonomie Objekttyp z.B. "Wohnung" "Haus" etc.
function immo_tax_objekttyp()
{
    $labels = array(
        'name' => _x('objekttyp', 'General Name'),
        'singular_name' => _x('Objekttyp', 'Singular Name'),
        'search_items' => __('Nach Objekttyp suchen'),
        'all_items' => __('Objekttypen'),
        'edit_item' => __('Objekttyp bearbeiten'),
        'update_item' => __('Objekttyp aktualisieren'),
        'add_new_item' => __('Neuen Objekttyp anlegen'),
        'new_item' => __('Neuer Objekttyp'),
        'menu_name' => __('Objekttyp'),
    );

    $args = array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'rewrite' => array('slug' => 'objekttyp'),
        'show_in_rest' => true,
        'rest_base' => 'objekttyp',
        'rest_controller_class' => 'WP_REST_Terms_Controller',
    );

    register_taxonomy('objekttyp', 'wpi_immobilie', $args);
	// Refresh Permalinks
	flush_rewrite_rules();

}

// Taxonomie Immobiliengruppen z.B. "Referenzen" "Top-Häuser" etc.
function immo_gruppen()
{
	$labels = array(
		'name' => _x('immobiliengruppe', 'General Name'),
		'singular_name' => _x('Immobiliengruppe', 'Singular Name'),
		'search_items' => __('Nach Gruppe suchen'),
		'all_items' => __('Immobiliengruppen'),
		'edit_item' => __('Immobiliengruppe bearbeiten'),
		'update_item' => __('Immobiliengruppe aktualisieren'),
		'add_new_item' => __('Neue Immobiliengruppe anlegen'),
		'new_item' => __('Neue Immobiliengruppe'),
		'menu_name' => __('Immobiliengruppen'),
	);

	$args = array(
		'hierarchical' => true,
		'labels' => $labels,
		'show_ui' => true,
		'show_admin_column' => true,
		'rewrite' => array('slug' => 'immobiliengruppe'),
		'show_in_rest' => true,
		'rest_base' => 'immobiliengruppe',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	);

	register_taxonomy('immobiliengruppe', 'wpi_immobilie', $args);
	// Refresh Permalinks
	flush_rewrite_rules();

}

add_action('init', 'wpi_create_post_type');
add_action('init', 'immo_tax_vermarktung', 2);
add_action('init', 'immo_tax_objekttyp', 2);
add_action('init', 'immo_gruppen', 2);