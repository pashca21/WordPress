<?php 
	
	$uri_path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
	$uri_segments = explode('/', $uri_path);

		
	
	/*********************
	*    Defaults
	*********************/

	(!empty(get_option('ew-token')))? define('EW_TOKEN', get_option('ew-token') ) : define('EW_TOKEN', '' ) ;
	define('EW_CACHE', 20 );
	define('FF_PATH', WP_PLUGIN_DIR . '/flowfact-saleautomat-connector' );
	(!empty(get_option('ff-post-type')))? define('FF_POST_TYPE', get_option('ff-post-type') ) : define('FF_POST_TYPE', 'page' ) ;
	

	/*********************
	*    Default colors
	*********************/

	(!empty(get_option('ff-primary-color')))? define('EW_PRIMARY_COLOR', get_option('ff-primary-color') ) : define('EW_PRIMARY_COLOR', 'rgba(0,0,0,.8)' ) ;
	(!empty(get_option('ff-secondary-color')))? define('EW_SECONDARY_COLOR', get_option('ff-secondary-color') ) : define('EW_SECONDARY_COLOR', 'rgba(255,255,255,.8)' ) ;
	
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

	(!empty(get_option('ff-imprint-url')))? define('FF_IMPRINT_URL', get_option('ff-imprint-url') ) : define('FF_IMPRINT_URL', '' ) ;
	(!empty(get_option('ff-privacy-url')))? define('FF_PRIVACY_URL', get_option('ff-privacy-url') ) : define('FF_PRIVACY_URL', '' ) ;
	
	
	/*********************
	*    smtp
	*********************/

	(!empty(get_option('ff-mail-server')))? define('FF_MAIL_SERVER', get_option('ff-mail-server') ) : define('FF_MAIL_SERVER', '' ) ;
	(!empty(get_option('ff-mail-port')))? define('FF_MAIL_PORT', get_option('ff-mail-port') ) : define('FF_MAIL_PORT', '25' ) ;
	(!empty(get_option('ff-mail-user')))? define('FF_MAIL_USER', get_option('ff-mail-user') ) : define('FF_MAIL_USER', '' ) ;
	(!empty(get_option('ff-mail-pass')))? define('FF_MAIL_PASS', get_option('ff-mail-pass') ) : define('FF_MAIL_PASS', '' ) ;
	(!empty(get_option('ff-mail-from')))? define('FF_MAIL_FROM', get_option('ff-mail-from') ) : define('FF_MAIL_FROM', '' ) ;
	

	/*********************
	 *   routes
	 *********************/
	(!empty(get_option('ff-routing-page-type')))? define('EW_PLUGIN_ROUTE_PAGE_TYPE', get_option('ff-routing-page-type') ) : define('EW_PLUGIN_ROUTE_PAGE_TYPE', 'page' ) ;
	(!empty(get_option('ff-plugin-url')))? define('EW_PLUGIN_ROUTE', get_option('ff-plugin-url') ) : define('EW_PLUGIN_ROUTE', 'ff' );
	(!empty(get_option('ff-recommendation-route')))? define('FF_RECOMMENDATION_ROUTE', get_option('ff-recommendation-route') ) : define('FF_RECOMMENDATION_ROUTE', 'videobewertungen' ) ;
	(!empty(get_option('ff-estateview-route')))? define('FF_ESTATEVIEW_ROUTE', get_option('ff-estateview-route') ) : define('FF_ESTATEVIEW_ROUTE', 'immobilien' ) ;
	(!empty(get_option('ff-estatereference-route')))? define('EW_ESTATEREFERENCE_ROUTE', get_option('ff-estatereference-route') ) : define('EW_ESTATEREFERENCE_ROUTE', 'immobilienreferenzen' ) ;
	(!empty(get_option('ff-estateTracking-route')))? define('FF_ESTATETRACKING_ROUTE', get_option('ff-estateTracking-route') ) : define('FF_ESTATETRACKING_ROUTE', 'immobilientracking' ) ;
	(!empty(get_option('ff-ownerreport-route')))? define('FF_OWNERREPORT_ROUTE', get_option('ff-ownerreport-route') ) : define('FF_OWNERREPORT_ROUTE', 'objekttracking' );

	/*********************
	 *   cloud.IO
	 *********************/	
	(!empty(get_option('ff-cloudimage-io-url')))? define('FF_CLOUDIMAGE_IO_URL', get_option('ff-cloudimage-io-url') ) : define('FF_CLOUDIMAGE_IO_URL', 'https://ax151qown.cloudimg.io/width/' ) ;
	
	
	 
	 /*********************
	 *   Google
	 *********************/
	 (!empty(get_option('ff-gg-api-maps')))? define('FF_GG_API_MAPS', get_option('ff-gg-api-maps') ) : define('FF_GG_API_MAPS', '' ) ;
	 
	 
	 
	 