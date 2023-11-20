<?php 
	
	$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri_segments = explode('/', $uri_path);

		
	
	/*********************
	*    Defaults
	*********************/
	(!empty(get_option('ew-token')))? define('EW_TOKEN', get_option('ew-token') ) : define('EW_TOKEN', '' ) ;
	(!empty(get_option('ew-post-type')))? define('EW_POST_TYPE', get_option('ew-post-type') ) : define('EW_POST_TYPE', 'page' ) ;
	

	/*********************
	*    Default colors
	*********************/

	(!empty(get_option('ew-primary-color')))? define('EW_PRIMARY_COLOR', get_option('ew-primary-color') ) : define('EW_PRIMARY_COLOR', 'rgba(0,0,0,.8)' ) ;
	(!empty(get_option('ew-secondary-color')))? define('EW_SECONDARY_COLOR', get_option('ew-secondary-color') ) : define('EW_SECONDARY_COLOR', 'rgba(255,255,255,.8)' ) ;
	
	list($r, $g, $b) = sscanf(EW_PRIMARY_COLOR, "#%02x%02x%02x");
	define('EW_PRIMARY_COLOR_TRANS', 'rgba('.$r.','.$g.','.$b.',.5)' ) ;
		
	// set color classes by css by inline css
	add_action( 'wp_head', 'add_stylesheet_to_head' );
	function add_stylesheet_to_head() {
		echo "<style type='text/css'>
				.ff-bg-primary {background:".EW_PRIMARY_COLOR." !important;}
				.ff-bg-secondary {background:".EW_SECONDARY_COLOR." !important;}	
				.ff-color-primary {color:".EW_PRIMARY_COLOR." !important;}
				.ff-color-secondary {color:".EW_SECONDARY_COLOR." !important;}
				.ff-border-color-primary {border-color:".EW_PRIMARY_COLOR." !important;}
			</style>";
	}

	/*********************
	*    GDPR
	*********************/
	(!empty(get_option('ff-imprint-url')))? define('EW_IMPRINT_URL', get_option('ff-imprint-url') ) : define('EW_IMPRINT_URL', '' ) ;
	(!empty(get_option('ff-privacy-url')))? define('EW_PRIVACY_URL', get_option('ff-privacy-url') ) : define('EW_PRIVACY_URL', '' ) ;
	
	/*********************
	 *   routes
	 *********************/
	(!empty(get_option('ew-routing-page-type')))? define('EW_PLUGIN_ROUTE_PAGE_TYPE', get_option('ew-routing-page-type') ) : define('EW_PLUGIN_ROUTE_PAGE_TYPE', 'page' ) ;
	(!empty(get_option('ew-plugin-url')))? define('EW_PLUGIN_ROUTE', get_option('ew-plugin-url') ) : define('EW_PLUGIN_ROUTE', 'ew' );
	(!empty(get_option('ew-estateview-route')))? define('EW_ESTATEVIEW_ROUTE', get_option('ew-estateview-route') ) : define('EW_ESTATEVIEW_ROUTE', 'immobilien' ) ;
	(!empty(get_option('ew-estatereference-route')))? define('EW_ESTATEREFERENCE_ROUTE', get_option('ew-estatereference-route') ) : define('EW_ESTATEREFERENCE_ROUTE', 'immobilienreferenzen' ) ;
