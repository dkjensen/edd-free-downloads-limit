<?php
/**
 * Plugin Name:     Easy Digital Downloads - Free Downloads Limit
 * Description:     Limits the number of free downloads for a user in a given timeframe
 * Version:         1.0.0
 * Author:          David Jensen
 * Author URI:      https://dkjensen.com
 * Text Domain:     edd-free-downloads-limit
 */


if( ! defined( 'ABSPATH' ) ) {
	exit;
}

if( ! defined( 'EDD_FREE_DOWNLOAD_INTERVAL' ) )
	define( 'EDD_FREE_DOWNLOAD_INTERVAL', 60 * 60 * 24 );


if( ! defined( 'EDD_FREE_DOWNLOAD_PURCHASE_LIMIT' ) )
	define( 'EDD_FREE_DOWNLOAD_PURCHASE_LIMIT', 3 );



function edd_free_downloads_limit_init() {
	if( class_exists( 'Easy_Digital_Downloads' ) ) {
		require_once 'includes/edd-free-downloads-limit-functions.php';
		require_once 'includes/edd-free-downloads-limit-hooks.php';
	}
}
add_action( 'plugins_loaded', 'edd_free_downloads_limit_init' );
