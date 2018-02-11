<?php

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class for hooking core functionalities.
 */
if ( !class_exists( 'TBC_Filters' ) ) {

	class TBC_Filters {

		/**
		 * Constructor
		 */
		public function __construct() {

			add_shortcode( 'ticket_book_cf7', array( $this, 'tbc_register_ticket_book_shortcode' ) );
			add_filter( 'wpcf7_form_elements', array( $this, 'tbc_do_shortcodes_wpcf7_form' ) );
			add_filter( 'wpcf7_posted_data', array( $this, 'tbc_action_wpcf7_posted_data' ), 10, 1 );
		}

		/**
		 * Enabling shortcode in contact form
		 * @param html $form
		 * @return html
		 */
		public function tbc_do_shortcodes_wpcf7_form( $form ) {
			$form = do_shortcode( $form );
			return $form;
		}

		/**
		 * 
		 * @param array $data
		 * @return array
		 * @since 1.0.0
		 */
		public function tbc_action_wpcf7_posted_data( $data ) {
			tbc_update_event_data( $data );
			return $data;
		}

		/**
		 * Shortcode callback
		 * @global Object $wpdb
		 * @return html
		 */
		public function tbc_register_ticket_book_shortcode() {
			global $wpdb;
			$table_name		 = $wpdb->prefix . 'cf_ticket_booking';
			$booking_data	 = $wpdb->get_results( "SELECT name, value FROM $table_name" );

			if ( !empty( $booking_data ) ) {
				$html	 = '';
				$html	 .= '<span class = "tbc-checkbox-grid">';
				foreach ( $booking_data as $booking ) {
					$disabled = '';
					if ( $booking->value == 1 ) {
						$disabled = 'disabled="disabled"';
					}
					$book_name	 = str_replace( "_", " ", $booking->name );
					$book_name	 = ucwords( esc_html( $book_name ) );
					$html		 .= '<span><input type="checkbox" name="' . $booking->name . '" value="1" ' . $disabled . '/><label for="' . $booking->name . '">' . $book_name . '</label></span>';
				}
				return $html .= '</span>';
			}
		}

	}

	new TBC_Filters();
}