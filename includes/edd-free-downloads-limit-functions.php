<?php

if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 
 * 
 * @param type $user_ip 
 * @return type
 */
function edd_free_downloads_limit_get_remaining( $user_ip = '' ) {
	global $wpdb;

	if( empty( $user_ip ) )
		$user_ip = edd_get_ip();

	$purchases = $wpdb->get_var(
		$wpdb->prepare(
			"SELECT COUNT(*) FROM $wpdb->postmeta
			JOIN $wpdb->posts
			ON $wpdb->posts.ID = $wpdb->postmeta.post_id
			WHERE meta_key = '_edd_payment_user_ip' 
			AND meta_value = '%s'
			AND UNIX_TIMESTAMP( post_date + INTERVAL %d SECOND ) >= '%s'
			ORDER BY post_date DESC 
			",
			$user_ip,
			EDD_FREE_DOWNLOAD_INTERVAL,
			current_time( 'timestamp' )
		)
	);

	$allotted = EDD_FREE_DOWNLOAD_PURCHASE_LIMIT;

	$remaining = intval( $allotted ) - intval( $purchases );

	return $remaining > 0 ? $remaining : 0;
}