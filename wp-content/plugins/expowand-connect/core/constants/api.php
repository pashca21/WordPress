<?php
//Plugin connect to a Salesautomat Developend Account
define( 'EW_API_DEV_MODE', true );

/*********************
 *   API resources
 *********************/

if(EW_API_DEV_MODE === true)
{
    define('EW_API_BASE_URL', 	'https://expowand-dev.de/api/' );
    define('EW_API_ENVIORMENT', 'latest' );
}
else
{
    define('EW_API_BASE_URL', 	'https://expowand.de/api/' );
    define('EW_API_ENVIORMENT', 'stable' );
}


define('FF_API_ADMIN_TOKEN_SERVICE_AUTH', EW_API_BASE_URL . '/authenticate');
// define('FF_API_ADMIN_TOKEN_SERVICE_AUTH', EW_API_BASE_URL . '/admin-token-service/' . EW_API_ENVIORMENT .'/public/adminUser/authenticate');
define('FF_API_SCHEMA_SERVICE_INTIGRATION', EW_API_BASE_URL . '/schema-service/' . EW_API_ENVIORMENT .'/integrations');
define('FF_API_SCHEMA_SERVICE', EW_API_BASE_URL . '/schema-service/' . EW_API_ENVIORMENT .'/schemas');
define('FF_API_SCHEMA_SERVICE_BASE_PATH', EW_API_BASE_URL . '/schema-service/' . EW_API_ENVIORMENT);
define('FF_API_USER_SERVICE', EW_API_BASE_URL . '/user-service/' . EW_API_ENVIORMENT .'/users');
define('FF_API_VIEW_DEFINITION_SERVICE', EW_API_BASE_URL . '/view-definition-service/' . EW_API_ENVIORMENT .'/views');
define('FF_API_ENTITY_SERVICE_ENTITY', EW_API_BASE_URL . '/entity-service/' . EW_API_ENVIORMENT .'/schemas');
define('FF_API_SEARCH_SERVICE', EW_API_BASE_URL . '/search-service/' . EW_API_ENVIORMENT);
define('FF_API_ESTATE_VALIDATION', EW_API_BASE_URL . '/sprengnetter-service/' . EW_API_ENVIORMENT .'/api/valuation');
define('FF_API_ESTATE_VALIDATION_RENT', EW_API_BASE_URL . '/sprengnetter-service/' . EW_API_ENVIORMENT .'/api/rent');
define('FF_API_PORTAL_MANAGEMENT_SERVICE', EW_API_BASE_URL . '/portal-management-service/' . EW_API_ENVIORMENT .'/portals');
define('FF_API_OPENIMMO_SERVICE', EW_API_BASE_URL . '/openimmo-ftp-access-service/' . EW_API_ENVIORMENT .'/ftp');
define('FF_API_COMPANY_SERVICE', EW_API_BASE_URL . '/company-service/' . EW_API_ENVIORMENT .'/company');
define('FF_API_NYLAS_SERVICE', EW_API_BASE_URL . '/nylas-service/' . EW_API_ENVIORMENT );
define('FF_API_MULTIMEDIA_SERVICE', EW_API_BASE_URL . '/multimedia-service/' . EW_API_ENVIORMENT .'/assigned/schemas/' );
define('FF_API_ENTITLEMENT_SERVICE', EW_API_BASE_URL . '/flowfact-entitlement-service/' . EW_API_ENVIORMENT .'/entitlements/' );