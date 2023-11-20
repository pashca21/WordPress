<?php 

		
	/*********************
	*   estatereference
	*********************/
	
	
	// estatereference setting
	(!empty(get_option('ew-estatereference-theme')))? define('EW_estatereference_THEME', get_option('ew-estatereference-theme') ) : define('EW_estatereference_THEME', 'default' ) ;
	(!empty(get_option('ew-estatereference-sa-version')))? define('EW_ESTATEREFERENCE_SALESAUTOMATE_VIEW', get_option('ew-estatereference-sa-version') ) : define('EW_ESTATEREFERENCE_SALESAUTOMATE_VIEW', '1.0.0' ) ;
	
	(!empty(get_option('ew-estatereference-max-result')))? define('EW_ESTATEREFERENCE_MAX_RESULT', get_option('ew-estatereference-max-result') ) : define('EW_ESTATEREFERENCE_MAX_RESULT',10 ) ;
	
	define('EW_ESTATEREFERENCE_SALESAUTOMATE_PUBLISH_FLAG', 'showAsReference' );
