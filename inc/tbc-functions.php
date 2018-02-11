<?php

/**
 * 
 * @global Object $wpdb
 * @param array $data
 */
function tbc_update_event_data( $data ) {
	global $wpdb;
	$table_name		 = $wpdb->prefix . 'cf_ticket_booking';
	$booking_data	 = $wpdb->get_results( "SELECT name, value FROM $table_name", ARRAY_A );
	$unfiltered_data = array_map( function($d) use($data) {
		if ( array_key_exists( $d[ 'name' ], $data ) ) {
			return $d[ 'name' ];
		}
	}, $booking_data );
	if ( !empty( $unfiltered_data ) ) {
		$filtered_data = array_filter( $unfiltered_data );
		foreach ( $filtered_data as $key => $single_data ) {
			$wpdb->query( $wpdb->prepare( " UPDATE $table_name SET value = %d WHERE name = %s", 1, $single_data ) );
		}
	}
}
