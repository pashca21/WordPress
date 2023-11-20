<?php 

		
	/*********************
	*   estateView
	*********************/
	
	
	// estateView setting
	(!empty(get_option('ff-estateView-template')))? define('EW_ESTATEVIEW_THEME', get_option('ff-estateView-template') ) : define('EW_ESTATEVIEW_THEME', 'default' ) ;
	(!empty(get_option('ff-estateView-publish')))? define('FF_ESTATEVIEW_PUBLISH', get_option('ff-estateView-publish') ) : define('FF_ESTATEVIEW_PUBLISH', '8e2cd4a3-c462-470c-9c0f-e38797dfdb4b' ) ;	

	//seo setting
	(!empty(get_option('ff-estateView-seo-slug')))? define('FF_ESTATEVIEW_SEO_SLUG', get_option('ff-estateView-seo-slug') ) : define('FF_ESTATEVIEW_SEO_SLUG', '' ) ;
	(!empty(get_option('ff-estateView-seo')))? define('FF_ESTATEVIEW_SEO', get_option('ff-estateView-seo') ) : define('FF_ESTATEVIEW_SEO', 'on' ) ;
	define('FF_ESTATEVIEW_SEO_SITEMAP', get_home_url().'/'.EW_PLUGIN_ROUTE.'/'.EW_ESTATEVIEW_ROUTE );
	
	// estatereference search setting
	(!empty(get_option('ff-estateView-max-result')))? define('FF_ESTATEVIEW_MAX_RESULT', get_option('ff-estateView-max-result') ) : define('FF_ESTATEVIEW_MAX_RESULT', 15 ) ;
	
	// allow estate sharing to socialmedia
	(!empty(get_option('ff-estateView-show-socialmedia-links')))? define('FF_ESTATEVIEW_SHOW_SOCIALMEDIA_LINKS', get_option('ff-estateView-show-socialmedia-links') ) : define('FF_ESTATEVIEW_SHOW_SOCIALMEDIA_LINKS', 0 ) ;
	
	define('FF_ESTATEVIEW_SALESAUTOMATE_PUBLISH_FLAG', 'web_display' );
