<?php
define( 'EW_API_DEV_MODE', true );

if(EW_API_DEV_MODE === true) {
    define('EW_BASE_URL', 'http://work-expowand-dev.local' );
    define('EW_API_BASE_URL', EW_BASE_URL.'/api' );
} else {
    define('EW_BASE_URL', 'https://expowand.de' );
    define('EW_API_BASE_URL', EW_BASE_URL.'/api' );
}

define('EW_API_OFFERS_LAST_CHANGE', EW_API_BASE_URL . '/getOffersLastChange');
define('EW_API_OFFERS_LIST_SYNC', EW_API_BASE_URL . '/getOffersListChangedFromDate');
define('EW_API_OFFERS_LIST', EW_API_BASE_URL . '/offers');
define('EW_API_AGENT', EW_API_BASE_URL . '/agent');
define('EW_API_AGENT_LAST_CHANGE', EW_API_BASE_URL . '/getAgentLastChange');
define('EW_API_INQUIRY', EW_API_BASE_URL . '/postInquiry');
