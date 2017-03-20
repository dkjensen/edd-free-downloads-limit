<?php

if( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Disable the auto-download function if users free download limit is reached
 * 
 * @return type
 */
function edd_free_downloads_limit_disable_download() {
	$download_id = intval( $_GET['download_id'] );

	$download = new EDD_Download( $download_id );

	if( $download && $download->is_free() && edd_free_downloads_limit_get_remaining() >= EDD_FREE_DOWNLOAD_PURCHASE_LIMIT ) {
		wp_redirect( apply_filters( 'edd_free_downloads_limit_reached_redirect', get_permalink( $download_id ) ) );
		exit;
	}

	// Create a payment for this download to track downloads by IP
	if( empty( $_GET['payment-id'] ) ) {
		$purchase_data = array(
			'price'        => edd_format_amount( 0 ),
			'tax'          => edd_format_amount( 0 ),
			'post_date'    => date( 'Y-m-d H:i:s', current_time( 'timestamp' ) ),
			'purchase_key' => strtolower( md5( uniqid() ) ),
			'user_email'   => '',
			'user_info'    => array(
				'id'         => '-1',
				'email'      => '',
				'first_name' => '',
				'last_name'  => '',
				'discount'   => 'none'
			),
			'currency'     => edd_get_currency(),
			'downloads'    => array( $download_id ),
			'cart_details' => array( array(
				'name'     => get_the_title( $download_id ),
				'id'       => $download_id,
				'price'    => edd_format_amount( 0 ),
				'subtotal' => edd_format_amount( 0 ),
				'quantity' => 1,
				'tax'      => edd_format_amount( 0 )
			) ),
			'gateway'      => 'manual',
			'status'       => 'pending'
		);

		$payment_id = edd_insert_payment( $purchase_data );
	}
}
add_action( 'edd_free_downloads_process_download', 'edd_free_downloads_limit_disable_download', 5 );


/**
 * Remove the purchase / download link if users free download limit is reached
 * 
 * @return type
 */
function edd_free_downloads_limit_purchase_template() {
	if( ! is_singular( 'download' ) || is_admin() )
		return;

	$download = new EDD_Download( get_the_ID() );

	if( $download && $download->is_free() && edd_free_downloads_limit_get_remaining() >= EDD_FREE_DOWNLOAD_PURCHASE_LIMIT ) {
		remove_action( 'edd_after_download_content', 'edd_append_purchase_link' );
	}
}
add_action( 'the_post', 'edd_free_downloads_limit_purchase_template' );