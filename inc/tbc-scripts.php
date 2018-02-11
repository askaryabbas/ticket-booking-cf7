<?php

// Exit if accessed directly.
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Class for enqueue styles and scripts.
 */
if ( !class_exists( 'TBC_Scripts' ) ) {

	class TBC_Scripts {

		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'tbc_enqueue_scripts' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'tbc_admin_enqueue_scripts' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'tbc_enqueue_styles' ) );
			add_action( 'admin_enqueue_scripts', array( $this, 'tbc_admin_enqueue_styles' ) );
		}

		/**
		 * Enqueue front end scripts 
		 * @version 1.0.0
		 */
		public function tbc_enqueue_scripts() {
			wp_enqueue_script( TBC_TEXT_DOMAIN . '-script', TBC_PLUGIN_URL . 'assets/js/ticket-booking-cf7.js', array( 'jquery' ), TBC_VERSION );
			wp_localize_script( TBC_TEXT_DOMAIN . '-script', 'tbc_obj', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ) ) );
		}

		/**
		 * Enqueue admin scripts 
		 * @version 1.0.0
		 */
		public function tbc_admin_enqueue_scripts() {
			wp_enqueue_script( TBC_TEXT_DOMAIN . '-admin-script', TBC_PLUGIN_URL . 'assets/js/ticket-booking-cf7-admin.js', array( 'jquery' ), TBC_VERSION );
		}

		/**
		 * Enqueue front end style 
		 * @version 1.0.0
		 */
		public function tbc_enqueue_styles() {
			wp_enqueue_style( TBC_TEXT_DOMAIN . '-style', TBC_PLUGIN_URL . 'assets/css/ticket-booking-cf7.css', array(), TBC_VERSION, 'all' );
		}

		/**
		 * Enqueue admin scripts
		 * @version 1.0.0
		 */
		public function tbc_admin_enqueue_styles() {
			wp_enqueue_style( TBC_TEXT_DOMAIN . '-admin-style', TBC_PLUGIN_URL . 'assets/css/ticket-booking-cf7-admin.css', array(), TBC_VERSION, 'all' );
		}

	}

	new TBC_Scripts();
}